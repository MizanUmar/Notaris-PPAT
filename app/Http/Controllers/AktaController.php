<?php

namespace App\Http\Controllers;

use App\Models\Akta;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AktaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $akta = Akta::with([
            'permintaan.client.user',
            'permintaan.layanan'
        ])
            ->when($search, function ($query) use ($search) {
                $query->where('nomor_akta', 'like', "%$search%")
                    ->orWhere('nama_akta', 'like', "%$search%")
                    ->orWhereHas('permintaan.client.user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%$search%");
                    });
            })
            ->latest()
            ->paginate(10);

        $requests = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->whereIn('status', ['Diproses', 'Selesai'])
            ->latest()
            ->get();

        return view('admin.akta.index', compact(
            'akta',
            'requests',
            'search'
        ));
    }

    public function create($permintaan)
    {
        $permintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])->findOrFail($permintaan);

        $akta = null;

        return view('admin.akta.create', compact(
            'permintaan',
            'akta'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_layanan,id',
            'nomor_akta'    => 'required|max:100',
            'nama_akta'     => 'required|max:100',
            'isi_akta'      => 'required',
            'tanggal_akta'  => 'required|date',
        ]);

        $namaFile = 'akta_' . time() . '.pdf';

        $pdf = Pdf::loadView('admin.akta.pdf', [
            'nomor_akta'   => $request->nomor_akta,
            'nama_akta'    => $request->nama_akta,
            'tanggal_akta' => $request->tanggal_akta,
            'isi_akta'     => $request->isi_akta,
        ]);

        Storage::disk('public')->put(
            'deeds/' . $namaFile,
            $pdf->output()
        );

        $akta = Akta::create([
            'permintaan_id' => $request->permintaan_id,
            'nomor_akta'    => $request->nomor_akta,
            'nama_akta'     => $request->nama_akta,
            'isi_akta'      => $request->isi_akta,
            'tanggal_akta'  => $request->tanggal_akta,
            'file_akta'     => 'deeds/' . $namaFile,
        ]);

        // Automatically update the status of the request to Selesai
        if ($akta->permintaan) {
            $akta->permintaan->update(['status' => 'Selesai']);
        }

        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $akta = Akta::findOrFail($id);

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

        // If a file is uploaded manually
        if ($request->hasFile('file_akta')) {
            // Delete old file
            if ($akta->file_akta) {
                Storage::disk('public')->delete($akta->file_akta);
            }

            $file = $request->file('file_akta');
            $namaFile = 'akta_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('deeds', $namaFile, 'public');
            
            $data['file_akta'] = $path;
        } else {
            // Re-generate PDF with updated metadata (nomor, nama, tanggal) and the current/new isi_akta
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

        $akta->update($data);

        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $akta = Akta::findOrFail($id);

        if ($akta->file_akta) {
            Storage::disk('public')->delete($akta->file_akta);
        }

        // Revert the request status back to Diproses since the akta is deleted
        if ($akta->permintaan) {
            $akta->permintaan->update(['status' => 'Diproses']);
        }

        $akta->delete();

        return redirect()
            ->route('admin.akta.index')
            ->with('success', 'Akta berhasil dihapus.');
    }

    public function permintaanAkta()
    {
        $permintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->where('status', 'Diproses')
            ->whereDoesntHave('akta')
            ->latest()
            ->get();

        return view(
            'admin.akta.permintaan',
            compact('permintaan')
        );
    }

    public function clientIndex(Request $request)
    {
        $search = $request->input('search');
        $client = auth()->user()->client;

        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        $akta = Akta::with(['permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
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

        return view('client.akta.index', compact('akta', 'search'));
    }

    public function preview($id)
    {
        $akta = Akta::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        $documentType = 'akta';
        $title = $akta->nama_akta;
        $number = $akta->nomor_akta;
        $date = $akta->tanggal_akta;
        $content = $akta->isi_akta;
        $filePath = $akta->file_akta;
        $permintaan = $akta->permintaan;
        $isAdmin = true;

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

    public function clientPreview($id)
    {
        $client = auth()->user()->client;
        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        $akta = Akta::with(['permintaan.client.user', 'permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->findOrFail($id);

        $documentType = 'akta';
        $title = $akta->nama_akta;
        $number = $akta->nomor_akta;
        $date = $akta->tanggal_akta;
        $content = $akta->isi_akta;
        $filePath = $akta->file_akta;
        $permintaan = $akta->permintaan;
        $isAdmin = false;

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
}
