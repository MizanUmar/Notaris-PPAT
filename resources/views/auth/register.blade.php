@extends('layouts.auth')

@section('title', 'Daftar Sekarang')

@section('content')
<div class="auth-card" style="max-width: 550px; margin: 0 auto;">
    <div class="auth-brand">
        <i class="fa-solid fa-user-plus"></i>
        <h2 class="auth-title">Daftar Akun Baru</h2>
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

    <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label small fw-bold">Username</label>
                <input type="text" name="username" id="username" class="form-control form-control-premium" placeholder="Username login" value="{{ old('username') }}" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="nama" class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control form-control-premium" placeholder="Sesuai KTP" value="{{ old('nama') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nik" class="form-label small fw-bold">NIK (Nomor Induk Kependudukan)</label>
                <input type="text" name="nik" id="nik" class="form-control form-control-premium" placeholder="16 Digit NIK" value="{{ old('nik') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="no_hp" class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control form-control-premium" placeholder="Contoh: 0812345678" value="{{ old('no_hp') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label small fw-bold">Surel (Email)</label>
            <input type="email" name="email" id="email" class="form-control form-control-premium" placeholder="alamat@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label small fw-bold">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" class="form-control form-control-premium" rows="2" placeholder="Alamat tinggal sesuai KTP" required>{{ old('alamat') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label small fw-bold">Password</label>
                <input type="password" name="password" id="password" class="form-control form-control-premium" placeholder="Min. 6 Karakter" required>
            </div>

            <div class="col-md-6 mb-4">
                <label for="password_confirmation" class="form-label small fw-bold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-premium" placeholder="Ulangi password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-premium-primary mb-3">Daftar Sekarang</button>
    </form>

    <div class="auth-footer">
        Sudah memiliki akun? <a href="{{ route('login') }}">Masuk</a>
        <div class="mt-3">
            <a href="{{ route('landing') }}" class="text-muted small"><i class="fa-solid fa-arrow-left-long me-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
