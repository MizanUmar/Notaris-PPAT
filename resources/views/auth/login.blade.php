@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<div class="auth-card">
    <div class="auth-brand">
        <i class="fa-solid fa-scale-balanced"></i>
        <h2 class="auth-title">Masuk ke Sistem</h2>
        <p class="text-muted small">Notaris & PPAT Eka Sulistya, S.H., M.Kn.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 border-0 small mb-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label small fw-bold">Username</label>
            <input type="text" name="username" id="username" class="form-control form-control-premium" placeholder="Masukkan username Anda" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label small fw-bold">Password</label>
            <input type="password" name="password" id="password" class="form-control form-control-premium" placeholder="Masukkan password Anda" required>
        </div>

        <div class="mb-4">
            <label for="role" class="form-label small fw-bold">Masuk Sebagai</label>
            <select name="role" id="role" class="form-select form-control-premium" required>
                <option value="" disabled selected>Pilih hak akses</option>
                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client (Umum)</option>
                <option value="notaris" {{ old('role') === 'notaris' ? 'selected' : '' }}>Notaris</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator / Staf</option>
            </select>
        </div>

        <button type="submit" class="btn btn-premium-primary mb-3">Masuk Sekarang</button>
    </form>

    <div class="auth-footer">
        Belum memiliki akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        <div class="mt-3">
            <a href="{{ route('landing') }}" class="text-muted small"><i class="fa-solid fa-arrow-left-long me-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
