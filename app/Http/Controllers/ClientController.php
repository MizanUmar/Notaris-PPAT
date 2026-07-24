<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $clients = Client::with('user')
            ->when($search, function($query) use ($search) {
                $query->where('nik', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                      });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.clients.index', compact('clients', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:100',
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:6',
            'nik' => 'required|string|unique:client,nik|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => $request->password, // automatically hashed by casts
                'role' => 'client',
                'email' => $request->email,
            ]);

            Client::create([
                'user_id' => $user->id,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('admin.clients.index')->with('success', 'Data client berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data client.'])->withInput();
        }
    }

    public function show($id)
    {
        $client = Client::with(['user', 'permintaan.layanan', 'permintaan.akta', 'permintaan.surat'])->findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $user = $client->user;

        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:100',
            'password' => 'nullable|string|min:6',
            'nik' => 'required|string|max:20|unique:client,nik,' . $client->id,
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'username' => $request->username,
                'nama' => $request->nama,
                'email' => $request->email,
            ];
            if ($request->password) {
                $userData['password'] = $request->password; // hashed by casts
            }
            $user->update($userData);

            $client->update([
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('admin.clients.index')->with('success', 'Data client berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data client.'])->withInput();
        }
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $user = $client->user;
        
        // cascade on database level will delete client, but to be safe and clear we do it
        DB::beginTransaction();
        try {
            if ($user) {
                $user->delete();
            } else {
                $client->delete();
            }
            DB::commit();
            return redirect()->route('admin.clients.index')->with('success', 'Data client berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus client.']);
        }
    }
}
