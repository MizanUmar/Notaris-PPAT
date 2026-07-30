<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string|in:admin,notaris,client',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            // Validate role matches user selection
            if ($user->role !== $credentials['role']) {
                Auth::logout();
                $this->incrementFailedAttempt($request);
                return back()->withErrors(['username' => 'Role tidak sesuai dengan akun Anda.'])->withInput();
            }

            // Login berhasil -> reset counter percobaan gagal
            $request->session()->forget('login_attempts');

            $request->session()->regenerate();
            return $this->redirectUser($user);
        }

        $this->incrementFailedAttempt($request);

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput();
    }

    private function incrementFailedAttempt(Request $request)
    {
        $attempts = $request->session()->get('login_attempts', 0) + 1;
        $request->session()->put('login_attempts', $attempts);

        if ($attempts >= 3) {
            $request->session()->flash('show_contact_popup', true);
        }
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username|max:100',
            'nama' => 'required|string|max:150',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'required|string|unique:client,nik|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => $request->password,
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

            Auth::login($user);
            return redirect()->route('client.dashboard')->with('success', 'Pendaftaran berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal melakukan pendaftaran. Silakan coba lagi.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectUser($user)
    {
        if ($user->role === 'admin' || $user->role === 'notaris') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('client.dashboard');
    }
}
