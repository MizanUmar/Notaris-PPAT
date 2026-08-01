@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
<pre style="background:#000;color:#0f0;padding:10px;">Session ID: {{ session()->getId() }} | Attempts: {{ session('login_attempts') ?? 'null' }}</pre>
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

<!-- Modal Hubungi Admin -->
<div class="modal fade" id="modalHubungiAdmin" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-dark text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i> Gagal Login Berulang Kali</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-3">Anda sudah beberapa kali gagal masuk. Jika lupa username atau password, silakan hubungi Admin/Notaris untuk bantuan.</p>
                <a href="https://wa.me/6285931148582" target="_blank" class="btn btn-success w-100 fw-bold">
                    <i class="fa-brands fa-whatsapp me-2"></i> Hubungi via WhatsApp
                </a>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Coba Lagi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(session('show_contact_popup'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalHubungiAdmin'));
        modal.show();
    });
</script>
@endif
@endsection