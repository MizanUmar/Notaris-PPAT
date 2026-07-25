@extends('layouts.app')

@section('title', 'Biodata Saya')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Pengaturan Biodata Saya</h2>
            <p class="text-muted mb-0">Perbarui data diri KTP dan kelola keamanan password akun Anda.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Form Editor -->
        <div class="col-lg-8">
            <div class="card card-premium p-4 mb-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Informasi Pribadi</h5>

                <form action="{{ route('client.biodata.update') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap (Sesuai KTP)</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">NIK (16 Digit)</label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik', $client->nik) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $client->no_hp) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Alamat Tinggal Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $client->alamat) }}</textarea>
                    </div>

                    <h5 class="fw-bold font-heading mb-3 border-top pt-3 border-bottom pb-2">Ubah Kata Sandi (Password)</h5>
                    <p class="text-muted small">Kosongkan kolom di bawah jika tidak ingin mengubah password akun Anda.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-premium-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <!-- Help Sidebar -->
        <div class="col-lg-4">
            <div class="card card-premium p-4 text-center">
                <i class="fa-solid fa-circle-question fs-1 text-primary mb-3"></i>
                <h5 class="fw-bold font-heading mb-2">Butuh Bantuan?</h5>
                <p class="text-muted small mb-4">Jika Anda mengalami kendala dalam memperbarui data identitas NIK atau mengalami kegagalan sistem, silakan hubungi layanan bantuan staf Notaris.</p>
                @php
                $waNumber = preg_replace('/[^0-9]/', '', $profil->no_telepon);
                if (substr($waNumber, 0, 1) === '0') {
                $waNumber = '62' . substr($waNumber, 1);
                }
                @endphp
                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-3"><i class="fa-brands fa-whatsapp me-2 fs-6"></i> Hubungi Kami</a>
            </div>
        </div>
    </div>
</div>
@endsection