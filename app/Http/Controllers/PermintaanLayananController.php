<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\PermintaanLayanan;
use App\Models\DokumenClient;
use App\Models\Client;
use App\Models\ChecklistPersyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $permintaan = PermintaanLayanan::with(['client.user', 'layanan', 'dokumenClient', 'akta', 'surat'])
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
        $permintaan = PermintaanLayanan::with(['layanan.persyaratan', 'checklistPersyaratan'])->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
            'keterangan' => 'nullable|string',
        ]);

        if (in_array($request->status, ['Diproses', 'Selesai'])) {
            if (!$permintaan->isDokumenLengkap()) {
                $tercentang = $permintaan->jumlah_berkas_tercentang;
                $wajib = $permintaan->jumlah_berkas_wajib;
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Gagal memproses permohonan! Berkas persyaratan belum lengkap (' . $tercentang . '/' . $wajib . ' tercentang). Harap beri tahu client atau lengkapi berkas persyaratan terlebih dahulu.']);
            }
        }

        $permintaan->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.permintaan.show', $id)->with('success', 'Status permintaan berhasil diperbarui!');
    }

    public function adminDestroy($id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        if (in_array($permintaan->status, ['Diproses', 'Selesai'])) {
            return redirect()->route('admin.permintaan.index')->withErrors(['error' => 'Permintaan layanan yang sedang diproses atau telah selesai tidak dapat dihapus.']);
        }

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
        $layanan = Layanan::orderBy('nama_layanan', 'asc')->get();
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

        $layanan = Layanan::findOrFail($request->layanan_id);
        if (!$layanan->status_aktif) {
            return redirect()->back()->withErrors(['layanan_id' => 'Layanan yang Anda pilih sedang tidak aktif / tidak tersedia saat ini.'])->withInput();
        }

        $client = Auth::user()->client;

        $permintaan = PermintaanLayanan::create([
            'client_id' => $client->id,
            'layanan_id' => $request->layanan_id,
            'tanggal_permintaan' => Carbon::now()->toDateString(),
            'status' => 'Menunggu',
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

                // Auto-check requirement item upon upload
                $this->autoChecklistDocument($permintaan, $file);
            }
        }

        // Process requirement checkboxes if checked during form submission
        if ($request->has('persyaratan_ids') && is_array($request->persyaratan_ids)) {
            foreach ($request->persyaratan_ids as $reqId) {
                ChecklistPersyaratan::updateOrCreate(
                    [
                        'permintaan_id' => $permintaan->id,
                        'persyaratan_id' => $reqId,
                    ],
                    [
                        'status' => true,
                    ]
                );
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
            'persyaratan_id' => 'nullable|exists:persyaratan_dokumen,id',
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

            // Auto-check requirement item upon upload
            $this->autoChecklistDocument($permintaan, $file, $request->persyaratan_id);

            return back()->with('success', 'Dokumen berhasil diunggah dan berkas persyaratan telah tercentang!');
        }

        return back()->withErrors(['dokumen' => 'Gagal mengunggah dokumen.']);
    }

    private function autoChecklistDocument($permintaan, $file, $persyaratanId = null)
    {
        // 1. If explicit persyaratan_id is provided
        if ($persyaratanId) {
            ChecklistPersyaratan::updateOrCreate(
                [
                    'permintaan_id' => $permintaan->id,
                    'persyaratan_id' => $persyaratanId,
                ],
                [
                    'status' => 1
                ]
            );
            return;
        }

        // 2. Match filename against requirement names
        $filename = strtolower($file->getClientOriginalName());
        $persyaratanList = $permintaan->layanan->persyaratan;

        $matched = false;
        foreach ($persyaratanList as $req) {
            $reqWords = explode(' ', strtolower($req->nama_dokumen));
            foreach ($reqWords as $word) {
                $cleanedWord = trim(preg_replace('/[^a-z0-9]/', '', $word));
                if (strlen($cleanedWord) >= 3 && Str::contains($filename, $cleanedWord)) {
                    ChecklistPersyaratan::updateOrCreate(
                        [
                            'permintaan_id' => $permintaan->id,
                            'persyaratan_id' => $req->id,
                        ],
                        [
                            'status' => 1
                        ]
                    );
                    $matched = true;
                    break 2;
                }
            }
        }

        // 3. Fallback: Check off the first unchecked requirement for this request
        if (!$matched && $persyaratanList->count() > 0) {
            $checkedIds = ChecklistPersyaratan::where('permintaan_id', $permintaan->id)
                ->where('status', 1)
                ->pluck('persyaratan_id')
                ->toArray();

            $firstUnchecked = $persyaratanList->whereNotIn('id', $checkedIds)->first();
            if ($firstUnchecked) {
                ChecklistPersyaratan::updateOrCreate(
                    [
                        'permintaan_id' => $permintaan->id,
                        'persyaratan_id' => $firstUnchecked->id,
                    ],
                    [
                        'status' => 1
                    ]
                );
            }
        }
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
            ->orderBy('nama_layanan', 'asc')
            ->get();

        return view('client.persyaratan.index', compact('layanan'));
    }
}
