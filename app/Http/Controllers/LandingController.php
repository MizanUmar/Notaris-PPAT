<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\ProfilKantor;
use App\Models\InformasiLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    public function index()
    {
        $profil = ProfilKantor::first();
        if (!$profil) {
            // Seed a fallback if database not seeded
            $profil = ProfilKantor::create([
                'nama_kantor' => 'Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn.',
                'alamat' => 'Jalan Pangeran Natakusuma, Kota Pontianak',
                'no_telepon' => '081234567890',
                'email' => 'eka.sulistya@example.com',
            ]);
        }

        $layanan = Layanan::with('persyaratan')
            ->where('status_aktif', true)
            ->orderBy('nama_layanan', 'asc')
            ->get();

        $informasi = InformasiLayanan::with('layanan')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('landing', compact('profil', 'layanan', 'informasi'));
    }

    // ==========================================
    // ADMIN OFFICE PROFILE MANAGEMENT
    // ==========================================
    
    public function editProfil()
    {
        $profil = ProfilKantor::first();
        if (!$profil) {
            $profil = ProfilKantor::create([
                'nama_kantor' => 'Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn.',
                'alamat' => 'Jalan Pangeran Natakusuma, Kota Pontianak',
                'no_telepon' => '081234567890',
                'email' => 'eka.sulistya@example.com',
            ]);
        }
        return view('admin.profil.edit', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $profil = ProfilKantor::first();
        if (!$profil) {
            $profil = new ProfilKantor();
        }

        $request->validate([
            'nama_kantor' => 'required|string|max:150',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ]);

        $data = [
            'nama_kantor' => $request->nama_kantor,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'email' => $request->email,
        ];

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($profil->logo) {
                Storage::disk('public')->delete($profil->logo);
            }

            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('office', $filename, 'public');
            $data['logo'] = $path;
        }

        $profil->fill($data)->save();

        return redirect()->route('admin.profil.edit')->with('success', 'Profil kantor berhasil diperbarui!');
    }
}
