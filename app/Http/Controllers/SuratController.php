<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller untuk mengelola Arsip Surat Resmi Digital (Admin & Client)
 */
class SuratController extends Controller
{
    /**
     * Menampilkan daftar arsip surat resmi di panel admin.
     * Dilengkapi filter pencarian dan list permohonan layanan terkait.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query data surat beserta relasi pemohon client dan jenis layanannya
        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])
            // Filter pencarian berdasarkan nomor surat, jenis surat, atau nama client pemohon
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('jenis_surat', 'like', "%{$search}%")
                    ->orWhereHas('permintaan.client.user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->orderBy('tanggal_surat', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->paginate(10); // Paginasi data 10 item per halaman

        // Mengambil seluruh permintaan layanan yang bertatus Diproses atau Selesai untuk dihubungkan
        $requests = PermintaanLayanan::with(['client.user', 'layanan'])
            ->whereIn('status', ['Diproses', 'Selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan ke view panel arsip surat admin
        return view('admin.surat.index', compact('surat', 'requests', 'search'));
    }

    /**
     * Menampilkan daftar permintaan surat resmi masuk dari client yang berstatus 'Diproses'.
     */
    public function permintaanSurat()
    {
        // Mengambil seluruh permohonan layanan berjenis kategori "Surat" yang siap dibuatkan berkas suratnya
        $permintaan = PermintaanLayanan::with(['client.user', 'layanan'])
            ->where('status', 'Diproses')
            ->whereDoesntHave('surat')
            ->whereHas('layanan', function ($q) {
                $q->where('kategori', 'surat');
            })
            ->latest()
            ->get();

        // Mengembalikan ke view list permintaan surat masuk
        return view('admin.surat.permintaan', compact('permintaan'));
    }

    /**
     * Menampilkan form pembuatan surat resmi baru berdasarkan permohonan client.
     */
    public function create($permintaan_id)
    {
        // Cari permohonan layanan berdasarkan ID
        $permintaan = PermintaanLayanan::with(['client.user', 'layanan'])
            ->findOrFail($permintaan_id);

        // Mengembalikan ke view form pembuatan draf surat baru
        return view('admin.surat.create', compact('permintaan'));
    }

    /**
     * Menyimpan surat baru ke database dan meng-generate file PDF otomatis.
     */
    public function store(Request $request)
    {
        // Validasi input wajib form
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_layanan,id',
            'nomor_surat' => 'required|string|max:100',
            'jenis_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'isi_surat' => 'required|string',
        ]);

        // Menyusun nama file PDF yang unik berbasis timestamp
        $namaFile = 'surat_' . time() . '.pdf';

        // Render isi template surat HTML menjadi file PDF secara otomatis menggunakan DomPDF
        $pdf = Pdf::loadView('admin.surat.pdf', [
            'nomor_surat'   => $request->nomor_surat,
            'jenis_surat'   => $request->jenis_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'isi_surat'     => $request->isi_surat,
        ]);

        // Menyimpan file PDF hasil render ke disk public server (storage/letters/)
        Storage::disk('public')->put(
            'letters/' . $namaFile,
            $pdf->output()
        );

        // Menyimpan record data surat resmi baru ke database
        $surat = Surat::create([
            'permintaan_id' => $request->permintaan_id,
            'nomor_surat' => $request->nomor_surat,
            'jenis_surat' => $request->jenis_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'file_surat' => 'letters/' . $namaFile,
            'keterangan' => $request->isi_surat, // Menyimpan isi teks surat
        ]);

        // Mengubah status permohonan layanan terkait secara otomatis menjadi "Selesai"
        if ($surat->permintaan) {
            $surat->permintaan->update(['status' => 'Selesai']);
        }

        // Kembali ke panel utama arsip surat admin dengan notifikasi sukses
        return redirect()->route('admin.surat.index')->with('success', 'Surat resmi berhasil dibuat!');
    }

    /**
     * Memperbarui data surat (edit nomor surat, jenis, tanggal, teks, atau mengupload file manual).
     */
    public function update(Request $request, $id)
    {
        // Cari data surat berdasarkan ID
        $surat = Surat::findOrFail($id);

        // Validasi input edit surat
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'jenis_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'isi_surat' => 'nullable|string',
        ]);

        $data = [
            'nomor_surat' => $request->nomor_surat,
            'jenis_surat' => $request->jenis_surat,
            'tanggal_surat' => $request->tanggal_surat,
        ];

        if ($request->has('isi_surat') && !is_null($request->isi_surat)) {
            $data['keterangan'] = $request->isi_surat;
        }

        // KONDISI 1: Jika admin mengunggah berkas surat kustom secara manual
        if ($request->hasFile('file_surat')) {
            // Hapus file fisik surat lama dari server
            if ($surat->file_surat) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            // Simpan berkas baru
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('letters', $filename, 'public');

            $data['file_surat'] = $path;
        } else {
            // KONDISI 2: Jika tidak upload manual, generate ulang PDF berdasarkan teks isi surat terkini
            if ($surat->file_surat) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            $namaFile = 'surat_' . time() . '.pdf';
            $isi = $request->input('isi_surat', $surat->keterangan);

            $pdf = Pdf::loadView('admin.surat.pdf', [
                'nomor_surat'   => $request->nomor_surat,
                'jenis_surat'   => $request->jenis_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'isi_surat'     => $isi,
            ]);

            Storage::disk('public')->put(
                'letters/' . $namaFile,
                $pdf->output()
            );

            $data['file_surat'] = 'letters/' . $namaFile;
        }

        // Jalankan pembaruan data di database
        $surat->update($data);

        // Kembali ke panel utama arsip surat admin dengan notifikasi sukses
        return redirect()->route('admin.surat.index')->with('success', 'Data surat berhasil diperbarui!');
    }

    /**
     * Menghapus data surat resmi beserta file fisik PDF-nya dari disk penyimpanan.
     */
    public function destroy($id)
    {
        // Cari data surat berdasarkan ID
        $surat = Surat::findOrFail($id);

        // Hapus file fisik PDF dari folder storage
        if ($surat->file_surat) {
            Storage::disk('public')->delete($surat->file_surat);
        }

        // Mengembalikan status permohonan layanan kembali menjadi "Diproses" (karena surat dihapus)
        if ($surat->permintaan) {
            $surat->permintaan->update(['status' => 'Diproses']);
        }

        // Hapus record dari database
        $surat->delete();

        // Kembali ke panel utama arsip surat admin dengan notifikasi sukses
        return redirect()->route('admin.surat.index')->with('success', 'Data surat berhasil dihapus!');
    }

    /**
     * Menampilkan daftar arsip surat resmi khusus untuk akun Client yang masuk saat ini.
     */
    public function clientIndex(Request $request)
    {
        $search = $request->input('search');
        $client = auth()->user()->client;

        // Validasi profil data client
        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        // Query data arsip surat yang diajukan oleh client login saat ini
        $surat = Surat::with(['permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            // Filter pencarian
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%$search%")
                        ->orWhere('jenis_surat', 'like', "%$search%")
                        ->orWhereHas('permintaan.layanan', function ($qLayanan) use ($search) {
                            $qLayanan->where('nama_layanan', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate(10);

        // Mengembalikan ke view arsip surat mandiri milik client
        return view('client.surat.index', compact('surat', 'search'));
    }

    /**
     * Menampilkan halaman pratinjau (preview) berkas surat resmi di panel admin.
     */
    public function preview($id)
    {
        // Cari data surat lengkap
        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        
        // Memetakan properti surat ke variabel preview dokumen bersama
        $documentType = 'surat';
        $title = $surat->jenis_surat;
        $number = $surat->nomor_surat;
        $date = $surat->tanggal_surat;
        $content = $surat->keterangan; // Teks isi surat HTML
        $filePath = $surat->file_surat;
        $permintaan = $surat->permintaan;
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
     * Menampilkan halaman pratinjau (preview) berkas surat resmi di panel client.
     */
    public function clientPreview($id)
    {
        $client = auth()->user()->client;
        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        // Cari data surat milik client login saat ini berdasarkan ID
        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->findOrFail($id);

        // Memetakan properti ke variabel preview dokumen bersama
        $documentType = 'surat';
        $title = $surat->jenis_surat;
        $number = $surat->nomor_surat;
        $date = $surat->tanggal_surat;
        $content = $surat->keterangan; // Teks isi surat HTML
        $filePath = $surat->file_surat;
        $permintaan = $surat->permintaan;
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
     * Menampilkan form edit arsip surat resmi (halaman alternatif).
     */
    public function edit($id)
    {
        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        $permintaan = $surat->permintaan;

        return view('admin.surat.edit', compact('surat', 'permintaan'));
    }
}
