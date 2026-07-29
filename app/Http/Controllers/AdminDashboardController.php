<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\PermintaanLayanan;
use App\Models\Akta;
use App\Models\Surat;
use App\Models\BukuTamu;
use Illuminate\Http\Request;


class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::count();

        $totalPermintaan = PermintaanLayanan::count();

        $totalAkta = Akta::count();

        $totalSurat = Surat::count();

        $pendingPermintaan = PermintaanLayanan::where('status', 'Menunggu')->count();

        $recentPermintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->latest()
            ->take(5)
            ->get();

        $recentBukuTamu = BukuTamu::latest()
            ->take(5)
            ->get();

        $agenda = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->where('status', 'Diproses')
            ->orderBy('updated_at')
            ->take(5)
            ->get();

        $reminders = Reminder::where('user_id', Auth::id())
            ->orderBy('tanggal')
            ->get();

        return view('admin.dashboard', compact(
            'totalClients',
            'totalPermintaan',
            'totalAkta',
            'totalSurat',
            'pendingPermintaan',
            'recentPermintaan',
            'recentBukuTamu',
            'agenda',
            'reminders'
        ));
    }
}
