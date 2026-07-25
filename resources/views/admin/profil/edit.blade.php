@extends('layouts.app')

@section('title', 'Profil Kantor')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Pengaturan Profil Kantor</h2>
            <p class="text-muted mb-0">Kelola identitas resmi kantor, kontak, alamat, dan titik lokasi pada peta.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Lokasi Peta -->
        <div class="col-lg-4">
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 text-start border-bottom pb-2">Titik Lokasi Peta</h5>

                @if($profil->latitude && $profil->longitude)
                <div class="ratio ratio-1x1 rounded border mb-3">
                    <iframe
                        src="https://www.google.com/maps?q={{ $profil->latitude }},{{ $profil->longitude }}&output=embed"
                        style="border:0;"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                @else
                <div class="bg-light rounded p-4 border d-flex flex-column align-items-center justify-content-center text-muted mb-3" style="min-height: 180px;">
                    <i class="fa-solid fa-map-location-dot fs-1 text-black-50 mb-2"></i>
                    <span class="small fw-semibold">Titik Lokasi Belum Diatur</span>
                </div>
                @endif

                <p class="text-muted small mb-0">
                    Peta ini akan ditampilkan pada bagian "Kontak & Lokasi" di landing page publik.
                    Cara mengambil koordinat: buka Google Maps &rarr; klik-kanan (atau tekan lama di HP) tepat pada lokasi kantor &rarr; pilih angka koordinat yang muncul di atas (contoh: <code>-0.040409, 109.314467</code>) &rarr; salin ke kolom Latitude & Longitude di form sebelah kanan.
                </p>
            </div>
        </div>

        <!-- Form Editor -->
        <div class="col-lg-8">
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Identitas & Kontak Resmi</h5>

                <form action="{{ route('admin.profil.update') }}" method="POST">
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

                    <hr class="my-4">

                    <h6 class="fw-bold font-heading mb-3">Titik Lokasi pada Peta</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Latitude</label>
                            <input type="text" name="latitude" class="form-control" placeholder="Contoh: -0.040409" value="{{ old('latitude', $profil->latitude) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Longitude</label>
                            <input type="text" name="longitude" class="form-control" placeholder="Contoh: 109.314467" value="{{ old('longitude', $profil->longitude) }}">
                        </div>
                    </div>
                    <p class="text-muted small mb-4">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Kosongkan jika belum tahu koordinatnya — peta di landing page akan otomatis memakai kolom Alamat Lengkap di atas sebagai fallback.
                    </p>

                    <button type="submit" class="btn btn-premium-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection