<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\PermintaanLayanan;
use App\Models\DokumenClient;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PermintaanLayananController extends Controller
{
    // ==========================================
    // ADMIN ACTIONS
    // ==========================================

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $layananId = $request->input('layanan_id');

        $layananList = Layanan::orderBy('nama_layanan', 'asc')->get();

        $permintaan = PermintaanLayanan::with(['client.user', 'layanan'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('client.user', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($layananId, function ($query) use ($layananId) {
                $query->where('layanan_id', $layananId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.permintaan.index', compact('permintaan', 'layananList', 'search', 'status', 'layananId'));
    }

    public function adminShow($id)
    {
        $permintaan = PermintaanLayanan::with([
            'client.user',
            'layanan.persyaratan',
            'dokumenClient',
            'akta',
            'surat',
            'checklistPersyaratan'
        ])->findOrFail($id);

        return view('admin.permintaan.show', compact('permintaan'));
    }

    public function adminUpdateStatus(Request $request, $id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
            'keterangan' => 'nullable|string',
        ]);

        $permintaan->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.permintaan.show', $id)->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function adminDestroy($id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        // Delete all associated files
        foreach ($permintaan->dokumenClient as $doc) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }

        $permintaan->delete();

        return redirect()->route('admin.permintaan.index')->with('success', 'Permintaan layanan berhasil dihapus!');
    }

    // ==========================================
    // CLIENT ACTIONS
    // ==========================================

    public function clientDashboard()
    {
        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            return redirect()->route('login')->withErrors(['error' => 'Profil client tidak ditemukan.']);
        }

        $totalLayanan = PermintaanLayanan::where('client_id', $client->id)->count();
        $prosesLayanan = PermintaanLayanan::where('client_id', $client->id)->whereIn('status', ['Menunggu', 'Diproses'])->count();
        $selesaiLayanan = PermintaanLayanan::where('client_id', $client->id)->where('status', 'Selesai')->count();

        $recentAktivitas = PermintaanLayanan::with('layanan')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Tasks / pending documents that need upload
        // We will show a simple reminder of documents they need to prepare
        $pendingUploads = PermintaanLayanan::with(['layanan.persyaratan', 'dokumenClient'])
            ->where('client_id', $client->id)
            ->where('status', 'Menunggu')
            ->get();

        return view('client.dashboard', compact('client', 'totalLayanan', 'prosesLayanan', 'selesaiLayanan', 'recentAktivitas', 'pendingUploads'));
    }

    public function clientIndex()
    {
        $client = Auth::user()->client;

        $permintaan = PermintaanLayanan::with([
            'layanan',
            'akta',
            'surat'
        ])
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        return view('client.permintaan.index', compact('permintaan'));
    }

    public function clientCreate()
    {
        $layanan = Layanan::where('status_aktif', true)->orderBy('nama_layanan', 'asc')->get();
        return view('client.permintaan.create', compact('layanan'));
    }

    public function clientStore(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'keterangan' => 'nullable|string',
            'dokumen' => 'nullable|array',
            'dokumen.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // max 5MB per file
        ]);

        $client = Auth::user()->client;

        $permintaan = PermintaanLayanan::create([
            'client_id' => $client->id,
            'layanan_id' => $request->layanan_id,
            'tanggal_permintaan' => Carbon::now()->toDateString(),
            'status' => 'Diproses',
            'keterangan' => $request->keterangan,
        ]);

        // Upload documents if any
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('client_documents', $filename, 'public');

                DokumenClient::create([
                    'permintaan_id' => $permintaan->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'tanggal_upload' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('client.permintaan.index')->with('success', 'Permintaan layanan berhasil diajukan! Silakan lengkapi/pantau status dokumen Anda.');
    }

    public function clientShow($id)
    {
        $client = Auth::user()->client;

        $permintaan = PermintaanLayanan::with([
            'layanan.persyaratan',
            'dokumenClient',
            'akta',
            'surat',
            'checklistPersyaratan'
        ])
            ->where('client_id', $client->id)
            ->findOrFail($id);

        return view('client.permintaan.show', compact('permintaan'));
    }

    public function clientUploadDokumen(Request $request, $id)
    {
        $client = Auth::user()->client;
        $permintaan = PermintaanLayanan::where('client_id', $client->id)->findOrFail($id);

        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('client_documents', $filename, 'public');

            DokumenClient::create([
                'permintaan_id' => $permintaan->id,
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $path,
                'tanggal_upload' => Carbon::now(),
            ]);

            return back()->with('success', 'Dokumen berhasil diunggah!');
        }

        return back()->withErrors(['dokumen' => 'Gagal mengunggah dokumen.']);
    }

    public function clientDeleteDokumen($id)
    {
        $doc = DokumenClient::findOrFail($id);
        // Ensure request belongs to current client
        $permintaan = $doc->permintaan;
        if ($permintaan->client_id !== Auth::user()->client->id) {
            abort(403, 'Akses ditolak.');
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function clientPersyaratan()
    {
        $layanan = Layanan::with('persyaratan')
            ->where('status_aktif', true)
            ->orderBy('nama_layanan', 'asc')
            ->get();

        return view('client.persyaratan.index', compact('layanan'));
    }
}
