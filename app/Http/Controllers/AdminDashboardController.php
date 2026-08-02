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

/**
 * Controller untuk mengelola Halaman Dashboard Admin / Notaris
 */
class AdminDashboardController extends Controller
{
    /**
     * Menampilkan rangkuman statistik data, daftar permintaan terbaru,
     * buku tamu terbaru, agenda kerja, dan pengingat (reminder) notaris.
     */
    public function index()
    {
        // Menghitung jumlah total akun client terdaftar
        $totalClients = Client::count();

        // Menghitung jumlah total permohonan layanan masuk
        $totalPermintaan = PermintaanLayanan::count();

        // Menghitung jumlah total akta resmi yang telah diterbitkan
        $totalAkta = Akta::count();

        // Menghitung jumlah total surat resmi yang telah diterbitkan
        $totalSurat = Surat::count();

        // Menghitung jumlah permohonan baru yang masih berstatus 'Menunggu' persetujuan
        $pendingPermintaan = PermintaanLayanan::where('status', 'Menunggu')->count();

        // Mengambil 5 permohonan layanan terbaru beserta data client dan jenis layanannya
        $recentPermintaan = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->latest()
            ->take(5)
            ->get();

        // Mengambil 5 tamu kunjungan terbaru dari log buku tamu digital
        $recentBukuTamu = BukuTamu::latest()
            ->take(5)
            ->get();

        // Mengambil 5 agenda pekerjaan yang saat ini sedang berstatus 'Diproses'
        $agenda = PermintaanLayanan::with([
            'client.user',
            'layanan'
        ])
            ->where('status', 'Diproses')
            ->orderBy('updated_at')
            ->take(5)
            ->get();

        // Mengambil seluruh pengingat agenda (reminder) khusus untuk user/notaris yang login saat ini
        $reminders = Reminder::where('user_id', Auth::id())
            ->orderBy('tanggal')
            ->get();

        // Mengirimkan seluruh variabel rangkuman ke view dashboard admin
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
