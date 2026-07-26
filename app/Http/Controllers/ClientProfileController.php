<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ProfilKantor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $client = $user->client;
        $profil = ProfilKantor::first();
        return view('client.biodata.edit', compact('user', 'client', 'profil'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $client = $user->client;

        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:100',
            'nik' => 'required|string|max:20|unique:client,nik,' . $client->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password; // automatically hashed by casts
            }


            $client->update([
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('client.biodata.edit')->with('success', 'Biodata berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui biodata.'])->withInput();
        }
    }
}
