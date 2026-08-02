<?php

namespace App\Http\Controllers;

use App\Models\Akta;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk mengelola Arsip Akta Digital (Admin & Client)
 */
class AktaController extends Controller
{
    /**
     * Menampilkan daftar arsip akta digital pada panel admin.
     * Mendukung fitur pencarian dan paginasi data.
     */
    public function index(Request $request)
    {
        // Mendapatkan input pencarian dari request query string
        $search = $request->search;

        // Query data akta beserta relasi permintaan layanan, client, dan master layanan
        $akta = Akta::with([
            'permintaan.client.user',
            'permintaan.layanan'
        ])
            // Filter pencarian jika parameter kata kunci dikirimkan
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_akta', 'like', "%$search%")
                    ->orWhere('nama_akta', 'like', "%$search%")
                    ->orWhereHas('permintaan.client.user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%$search%");
                    });
            })
            ->latest() // Urutkan dari data paling baru
            ->paginate(10); // Batasi 10 data per halaman (paginasi)

        // Mengambil daftar permintaan layanan yang siap diproses untuk pembuatan akta
        $requests = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->whereIn('status', ['Diproses', 'Selesai'])
            ->latest()
            ->get();

        // Mengembalikan view panel arsip akta admin dengan data pendukung
        return view('admin.akta.index', compact(
            'akta',
            'requests',
            'search'
        ));
    }

    /**
     * Menampilkan form pembuatan akta baru berdasarkan permohonan layanan client.
     */
    public function create($permintaan)
    {
        // Cari data permohonan layanan berdasarkan ID permohonan
        $permintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])->findOrFail($permintaan);

        $akta = null;

        // Mengembalikan ke view form pembuatan draft akta baru
        return view('admin.akta.create', compact(
            'permintaan',
            'akta'
        ));
    }

    /**
     * Menyimpan arsip akta baru ke database dan meng-generate file PDF otomatis.
     */
    public function store(Request $request)
    {
        // Validasi input form wajib diisi
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_layanan,id',
            'nomor_akta'    => 'required|max:100',
            'nama_akta'     => 'required|max:100',
            'isi_akta'      => 'required',
            'tanggal_akta'  => 'required|date',
        ]);

        // Menyusun nama file PDF akta yang unik berdasarkan timestamp waktu
        $namaFile = 'akta_' . time() . '.pdf';

        // Render isi draf akta HTML menjadi file PDF secara otomatis menggunakan DomPDF
        $pdf = Pdf::loadView('admin.akta.pdf', [
            'nomor_akta'   => $request->nomor_akta,
            'nama_akta'    => $request->nama_akta,
            'tanggal_akta' => $request->tanggal_akta,
            'isi_akta'     => $request->isi_akta,
        ]);

        // Menyimpan file PDF hasil render ke direktori penyimpanan public (storage/deeds/)
        Storage::disk('public')->put(
            'deeds/' . $namaFile,
            $pdf->output()
        );

        // Menyimpan record data akta baru ke dalam database
        $akta = Akta::create([
            'permintaan_id' => $request->permintaan_id,
            'nomor_akta'    => $request->nomor_akta,
            'nama_akta'     => $request->nama_akta,
            'isi_akta'      => $request->isi_akta,
            'tanggal_akta'  => $request->tanggal_akta,
            'file_akta'     => 'deeds/' . $namaFile,
        ]);

        // Mengubah status permohonan layanan terkait secara otomatis menjadi "Selesai"
        if ($akta->permintaan) {
            $akta->permintaan->update(['status' => 'Selesai']);
        }

        // Kembali ke halaman index arsip akta dengan notifikasi sukses
        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil dibuat.');
    }

    /**
     * Memperbarui data akta (edit metadata, isi teks, atau mengganti berkas lampiran).
     */
    public function update(Request $request, $id)
    {
        // Cari data akta berdasarkan ID
        $akta = Akta::findOrFail($id);

        // Validasi parameter form edit
        $request->validate([
            'nomor_akta'   => 'required|max:100',
            'nama_akta'    => 'required|max:100',
            'tanggal_akta' => 'required|date',
            'file_akta'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'isi_akta'     => 'nullable',
        ]);

        $data = [
            'nomor_akta'   => $request->nomor_akta,
            'nama_akta'    => $request->nama_akta,
            'tanggal_akta' => $request->tanggal_akta,
        ];

        if ($request->has('isi_akta') && !is_null($request->isi_akta)) {
            $data['isi_akta'] = $request->isi_akta;
        }

        // KONDISI 1: Jika admin mengunggah file akta kustom secara manual
        if ($request->hasFile('file_akta')) {
            // Hapus file fisik akta lama dari server
            if ($akta->file_akta) {
                Storage::disk('public')->delete($akta->file_akta);
            }

            // Simpan file baru yang diunggah
            $file = $request->file('file_akta');
            $namaFile = 'akta_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('deeds', $namaFile, 'public');

            $data['file_akta'] = $path;
        } else {
            // KONDISI 2: Jika tidak upload manual, generate ulang PDF berdasarkan teks Isi Akta terkini
            if ($akta->file_akta) {
                Storage::disk('public')->delete($akta->file_akta);
            }

            $namaFile = 'akta_' . time() . '.pdf';
            $isi = $request->input('isi_akta', $akta->isi_akta);

            $pdf = Pdf::loadView('admin.akta.pdf', [
                'nomor_akta'   => $request->nomor_akta,
                'nama_akta'    => $request->nama_akta,
                'tanggal_akta' => $request->tanggal_akta,
                'isi_akta'     => $isi,
            ]);

            Storage::disk('public')->put(
                'deeds/' . $namaFile,
                $pdf->output()
            );

            $data['file_akta'] = 'deeds/' . $namaFile;
        }

        // Update data akta di database
        $akta->update($data);

        // Kembali ke halaman index arsip akta dengan notifikasi sukses
        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil diperbarui.');
    }

    /**
     * Menghapus arsip akta dari database beserta file fisik PDF pendukung.
     */
    public function destroy($id)
    {
        // Cari data akta berdasarkan ID
        $akta = Akta::findOrFail($id);

        // Hapus file fisik PDF akta dari folder storage
        if ($akta->file_akta) {
            Storage::disk('public')->delete($akta->file_akta);
        }

        // Mengembalikan status permohonan layanan kembali menjadi "Diproses" (karena akta dihapus)
        if ($akta->permintaan) {
            $akta->permintaan->update(['status' => 'Diproses']);
        }

        // Hapus record dari database
        $akta->delete();

        // Kembali ke halaman index arsip akta dengan notifikasi sukses
        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil dihapus.');
    }

    /**
     * Menampilkan daftar permintaan akta masuk dari client yang berstatus 'Diproses'.
     */
    public function permintaanAkta()
    {
        // Mengambil seluruh permohonan layanan berjenis kategori "Akta" yang siap dibuatkan draf akta
        $permintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->where('status', 'Diproses')
            ->whereDoesntHave('akta')
            ->whereHas('layanan', function ($q) {
                $q->where('kategori', 'akta');
            })
            ->latest()
            ->get();

        // Mengembalikan ke halaman list permintaan akta masuk
        return view(
            'admin.akta.permintaan',
            compact('permintaan')
        );
    }

    /**
     * Menampilkan daftar arsip akta khusus untuk akun Client yang bersangkutan.
     */
    public function clientIndex(Request $request)
    {
        $search = $request->input('search');
        $client = auth()->user()->client;

        // Validasi profil data client
        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        // Query data arsip akta yang diajukan oleh client login saat ini
        $akta = Akta::with(['permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            // Filter pencarian berdasarkan kata kunci
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_akta', 'like', "%$search%")
                        ->orWhere('nama_akta', 'like', "%$search%")
                        ->orWhereHas('permintaan.layanan', function ($qLayanan) use ($search) {
                            $qLayanan->where('nama_layanan', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate(10);

        // Mengembalikan ke view arsip akta mandiri milik client
        return view('client.akta.index', compact('akta', 'search'));
    }

    /**
     * Menampilkan halaman pratinjau (preview) berkas akta digital di panel admin.
     */
    public function preview($id)
    {
        // Cari data akta dengan relasi client dan layanan
        $akta = Akta::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        
        // Memetakan properti akta ke variabel preview dokumen bersama
        $documentType = 'akta';
        $title = $akta->nama_akta;
        $number = $akta->nomor_akta;
        $date = $akta->tanggal_akta;
        $content = $akta->isi_akta;
        $filePath = $akta->file_akta;
        $permintaan = $akta->permintaan;
        $isAdmin = true;

        // Mengarahkan ke template pratinjau dokumen terpadu
        return view('shared.document_preview', compact(
            'documentType',
            'title',
            'number',
            'date',
            'content',
            'filePath',
            'permintaan',
            'isAdmin'
        ));
    }

    /**
     * Menampilkan halaman pratinjau (preview) berkas akta digital di panel client.
     */
    public function clientPreview($id)
    {
        $client = auth()->user()->client;
        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        // Cari data akta milik client bersangkutan berdasarkan ID
        $akta = Akta::with(['permintaan.client.user', 'permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->findOrFail($id);

        // Memetakan properti ke variabel preview dokumen bersama
        $documentType = 'akta';
        $title = $akta->nama_akta;
        $number = $akta->nomor_akta;
        $date = $akta->tanggal_akta;
        $content = $akta->isi_akta;
        $filePath = $akta->file_akta;
        $permintaan = $akta->permintaan;
        $isAdmin = false;

        // Mengarahkan ke template pratinjau dokumen terpadu
        return view('shared.document_preview', compact(
            'documentType',
            'title',
            'number',
            'date',
            'content',
            'filePath',
            'permintaan',
            'isAdmin'
        ));
    }

    /**
     * Menampilkan form edit arsip akta (layout halaman edit statis alternatif).
     */
    public function edit($id)
    {
        $akta = Akta::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        $permintaan = $akta->permintaan;

        return view('admin.akta.edit', compact('akta', 'permintaan'));
    }
}
