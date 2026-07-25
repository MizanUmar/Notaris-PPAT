<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])
            ->when($search, function($query) use ($search) {
                $query->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('jenis_surat', 'like', "%{$search}%")
                      ->orWhereHas('permintaan.client.user', function($q) use ($search) {
                          $q->where('nama', 'like', "%{$search}%");
                      });
            })
            ->orderBy('tanggal_surat', 'desc')
            ->paginate(10);

        // Get requests that can be linked to letters
        $requests = PermintaanLayanan::with(['client.user', 'layanan'])
            ->whereIn('status', ['Diproses', 'Selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.surat.index', compact('surat', 'requests', 'search'));
    }

    public function permintaanSurat()
    {
        $permintaan = PermintaanLayanan::with(['client.user', 'layanan'])
            ->where('status', 'Diproses')
            ->whereDoesntHave('surat')
            ->latest()
            ->get();

        return view('admin.surat.permintaan', compact('permintaan'));
    }

    public function create($permintaan_id)
    {
        $permintaan = PermintaanLayanan::with(['client.user', 'layanan'])
            ->findOrFail($permintaan_id);

        return view('admin.surat.create', compact('permintaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_layanan,id',
            'nomor_surat' => 'required|string|max:100',
            'jenis_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'isi_surat' => 'required|string',
        ]);

        $namaFile = 'surat_' . time() . '.pdf';

        $pdf = Pdf::loadView('admin.surat.pdf', [
            'nomor_surat'   => $request->nomor_surat,
            'jenis_surat'   => $request->jenis_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'isi_surat'     => $request->isi_surat,
        ]);

        Storage::disk('public')->put(
            'letters/' . $namaFile,
            $pdf->output()
        );

        $surat = Surat::create([
            'permintaan_id' => $request->permintaan_id,
            'nomor_surat' => $request->nomor_surat,
            'jenis_surat' => $request->jenis_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'file_surat' => 'letters/' . $namaFile,
            'keterangan' => $request->isi_surat,
        ]);

        // Automatically update the status of the request to Selesai
        if ($surat->permintaan) {
            $surat->permintaan->update(['status' => 'Selesai']);
        }

        return redirect()->route('admin.surat.index')->with('success', 'Surat resmi berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

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

        if ($request->hasFile('file_surat')) {
            // Delete old file
            if ($surat->file_surat) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('letters', $filename, 'public');
            
            $data['file_surat'] = $path;
        } else {
            // Re-generate PDF with updated metadata and current text content
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

        $surat->update($data);

        return redirect()->route('admin.surat.index')->with('success', 'Data surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $surat = Surat::findOrFail($id);
        
        if ($surat->file_surat) {
            Storage::disk('public')->delete($surat->file_surat);
        }

        // Revert the request status back to Diproses since the letter is deleted
        if ($surat->permintaan) {
            $surat->permintaan->update(['status' => 'Diproses']);
        }

        $surat->delete();

        return redirect()->route('admin.surat.index')->with('success', 'Data surat berhasil dihapus!');
    }

    public function clientIndex(Request $request)
    {
        $search = $request->input('search');
        $client = auth()->user()->client;

        if (!$client) {
            return redirect()->route('client.dashboard')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        $surat = Surat::with(['permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
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

        return view('client.surat.index', compact('surat', 'search'));
    }

    public function preview($id)
    {
        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])->findOrFail($id);
        $documentType = 'surat';
        $title = $surat->jenis_surat;
        $number = $surat->nomor_surat;
        $date = $surat->tanggal_surat;
        $content = $surat->keterangan; // HTML content
        $filePath = $surat->file_surat;
        $permintaan = $surat->permintaan;
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

        $surat = Surat::with(['permintaan.client.user', 'permintaan.layanan'])
            ->whereHas('permintaan', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->findOrFail($id);

        $documentType = 'surat';
        $title = $surat->jenis_surat;
        $number = $surat->nomor_surat;
        $date = $surat->tanggal_surat;
        $content = $surat->keterangan; // HTML content
        $filePath = $surat->file_surat;
        $permintaan = $surat->permintaan;
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
