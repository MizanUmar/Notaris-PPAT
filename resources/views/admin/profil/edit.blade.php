@extends('layouts.app')

@section('title', 'Profil Kantor')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Pengaturan Profil Kantor</h2>
            <p class="text-muted mb-0">Kelola identitas resmi kantor, logo, kontak, dan alamat pelayanan publik.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Logo and details summary -->
        <div class="col-lg-4">
            <div class="card card-premium p-4 text-center">
                <h5 class="fw-bold font-heading mb-3 text-start border-bottom pb-2">Logo Instansi</h5>
                
                @if($profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo Kantor" class="img-fluid rounded mb-3 border shadow-sm p-2" style="max-height: 180px; object-fit: contain;">
                @else
                    <div class="bg-light rounded p-4 border d-flex flex-column align-items-center justify-content-center text-muted mb-3" style="min-height: 180px;">
                        <i class="fa-solid fa-scale-balanced fs-1 text-black-50 mb-2"></i>
                        <span class="small fw-semibold">Belum Ada Logo Khusus</span>
                    </div>
                @endif
                <p class="text-muted small">Logo ini akan ditampilkan pada halaman publik landing page serta surat kelengkapan.</p>
            </div>
        </div>

        <!-- Form Editor -->
        <div class="col-lg-8">
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Identitas & Kontak Resmi</h5>

                <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Kantor Resmi</label>
                        <input type="text" name="nama_kantor" class="form-control" value="{{ old('nama_kantor', $profil->nama_kantor) }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor HP / Telepon Kantor</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $profil->no_telepon) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Surel (Email) Kantor</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $profil->email) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $profil->alamat) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Perbarui Logo Baru (JPG/PNG max 2MB)</label>
                        <input type="file" name="logo" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-premium-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
