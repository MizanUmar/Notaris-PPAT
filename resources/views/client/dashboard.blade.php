@extends('layouts.app')

@section('title', 'Dashboard Client')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Halaman Dashboard Client -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Beranda Client</h2>
            <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->nama }}!</p>
        </div>
        <!-- Tombol cepat untuk pengajuan permohonan layanan hukum baru -->
        <a href="{{ route('client.permintaan.create') }}" class="btn btn-premium-primary">
            <i class="fa-solid fa-file-signature me-1"></i> Buat Permintaan Baru
        </a>
    </div>

    <!-- ========================================================
         KARTU-KARTU INDIKATOR LAYANAN SAYA
         ======================================================== -->
    <div class="row g-4 mb-4">
        <!-- Statistik: Total Seluruh Pengajuan Layanan -->
        <div class="col-md-4">
            <div class="card card-premium p-4 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block text-uppercase mb-1">Total Pengajuan</span>
                        <h3 class="fw-bold mb-0">{{ $totalLayanan }}</h3>
                    </div>
                    <div class="icon-circle bg-primary-subtle text-primary mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-file-invoice fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistik: Pengajuan yang Sedang Diproses Staf/Notaris -->
        <div class="col-md-4">
            <div class="card card-premium p-4 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block text-uppercase mb-1">Sedang Diproses</span>
                        <h3 class="fw-bold text-warning mb-0">{{ $prosesLayanan }}</h3>
                    </div>
                    <div class="icon-circle bg-warning-subtle text-warning mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-spinner fa-spin fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistik: Pengajuan yang Selesai & Dokumen Terbit -->
        <div class="col-md-4">
            <div class="card card-premium p-4 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block text-uppercase mb-1">Selesai</span>
                        <h3 class="fw-bold text-success mb-0">{{ $selesaiLayanan }}</h3>
                    </div>
                    <div class="icon-circle bg-success-subtle text-success mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-square-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ========================================================
             DAFTAR PENGAJUAN LAYANAN TERBARU (KOLOM KIRI)
             ======================================================== -->
        <div class="col-lg-8">
            <div class="card card-premium">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-history text-primary me-2"></i> Pengajuan Layanan Terbaru</h5>
                    <a href="{{ route('client.permintaan.index') }}" class="btn btn-sm btn-link text-decoration-none">Semua Riwayat</a>
                </div>
                <div class="table-responsive px-4 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>No. Order</th>
                                <th>Jenis Layanan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAktivitas as $akt)
                                <tr>
                                    <td><span class="fw-bold">#{{ $akt->id }}</span></td>
                                    <td><span class="fw-semibold text-primary">{{ $akt->layanan->nama_layanan }}</span></td>
                                    <td><span class="small">{{ $akt->tanggal_permintaan->translatedFormat('d F Y') }}</span></td>
                                    <td>
                                        <!-- Penanda Warna Status Pengajuan -->
                                        @if($akt->status === 'Menunggu')
                                            <span class="badge badge-waiting">Menunggu Berkas</span>
                                        @elseif($akt->status === 'Diproses')
                                            <span class="badge badge-process">Sedang Diproses</span>
                                        @elseif($akt->status === 'Selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('client.permintaan.show', $akt->id) }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-eye me-1"></i> Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Anda belum pernah melakukan pengajuan layanan hukum.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================
             BOX INFORMASI TINDAKAN / PENGINGAT UNGGAH PERSYARATAN (KOLOM KANAN)
             ======================================================== -->
        <div class="col-lg-4">
            <div class="card card-premium border-danger-subtle bg-danger-subtle bg-opacity-25">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="fw-bold font-heading mb-0 text-danger-emphasis"><i class="fa-solid fa-circle-exclamation text-danger me-2"></i> Perlu Tindakan</h5>
                </div>
                <div class="card-body pt-0">
                    @php $hasPending = false; @endphp
                    <div class="d-flex flex-column gap-3">
                        <!-- Menampilkan pengingat berkas syarat layanan yang belum diunggah oleh client -->
                        @foreach($pendingUploads as $pu)
                            @foreach($pu->layanan->persyaratan as $req)
                                @php
                                    // Deteksi jika dokumen syarat sudah diunggah oleh client sebelumnya
                                    $isUploaded = $pu->dokumenClient->contains(function($value) use ($req) {
                                        return Str::contains(strtolower($value->nama_file), strtolower(explode(' ', $req->nama_dokumen)[0]));
                                    });
                                @endphp
                                @if(!$isUploaded)
                                    @php $hasPending = true; @endphp
                                    <div class="p-3 bg-white border border-danger-subtle rounded-3 shadow-xs">
                                        <small class="text-danger fw-bold d-block mb-1">Pengajuan #{{ $pu->id }} ({{ $pu->layanan->nama_layanan }})</small>
                                        <p class="mb-2 small text-muted">Belum mengunggah: <strong>{{ $req->nama_dokumen }}</strong></p>
                                        <!-- Tombol pintas untuk mengunggah dokumen yang kurang -->
                                        <a href="{{ route('client.permintaan.show', $pu->id) }}" class="btn btn-xs btn-danger text-white py-1 px-2 rounded-2 small fs-xs"><i class="fa-solid fa-upload"></i> Unggah File</a>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach

                        <!-- Jika seluruh berkas persyaratan telah lengkap -->
                        @if(!$hasPending)
                            <div class="text-center py-4 text-success-emphasis">
                                <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
                                <p class="small mb-0">Semua berkas persyaratan untuk pengajuan aktif Anda telah terunggah dengan lengkap!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
