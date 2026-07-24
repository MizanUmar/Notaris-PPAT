<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BukuTamuController extends Controller
{
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');
        
        $tamu = BukuTamu::with('user')
            ->when($search, function($query) use ($search) {
                $query->where('nama_tamu', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhere('nomor_hp', 'like', "%{$search}%")
                      ->orWhere('keperluan', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(10);

        return view('admin.buku-tamu.index', compact('tamu', 'search'));
    }

    public function adminShowQr()
    {
        // Define public check-in route
        $checkInUrl = route('buku-tamu.checkin');
        return view('admin.buku-tamu.qr', compact('checkInUrl'));
    }

    public function adminDestroy($id)
    {
        $tamu = BukuTamu::findOrFail($id);
        $tamu->delete();

        return redirect()->route('admin.buku-tamu.index')->with('success', 'Catatan buku tamu berhasil dihapus!');
    }

    // Public / Client check-in form
    public function showCheckInForm()
    {
        $user = Auth::user();
        $client = $user ? $user->client : null;

        return view('client.buku-tamu.create', compact('user', 'client'));
    }

    public function storeCheckIn(Request $request)
    {
        $request->validate([
            'nama_tamu' => 'required|string|max:150',
            'instansi' => 'nullable|string|max:150',
            'nomor_hp' => 'required|string|max:20',
            'keperluan' => 'required|string',
        ]);

        BukuTamu::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'nama_tamu' => $request->nama_tamu,
            'instansi' => $request->instansi,
            'nomor_hp' => $request->nomor_hp,
            'keperluan' => $request->keperluan,
            'tanggal_kunjungan' => Carbon::now()->toDateString(),
        ]);

        $message = 'Terima kasih! Kunjungan Anda berhasil dicatat.';
        
        if (Auth::check()) {
            if (Auth::user()->role === 'client') {
                return redirect()->route('client.dashboard')->with('success', $message);
            }
            return redirect()->route('admin.dashboard')->with('success', $message);
        }

        return redirect()->route('landing')->with('success', $message);
    }
}
