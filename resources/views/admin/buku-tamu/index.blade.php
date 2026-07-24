@extends('layouts.app')

@section('title', 'Buku Tamu Kunjungan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Buku Tamu Kunjungan</h2>
            <p class="text-muted mb-0">Monitor rekapitulasi data kunjungan client di Kantor Notaris.</p>
        </div>
        <a href="{{ route('admin.buku-tamu.qr') }}" class="btn btn-premium-primary">
            <i class="fa-solid fa-qrcode me-1"></i> Tampilkan QR Code Buku Tamu
        </a>
    </div>

    <!-- Search Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('admin.buku-tamu.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari berdasarkan Nama Tamu, Instansi/Pekerjaan, Keperluan..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Buku Tamu Table Card -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Tanggal Kunjungan</th>
                        <th>Nama Tamu</th>
                        <th>Instansi / Pekerjaan</th>
                        <th>No. Telepon / WA</th>
                        <th>Keperluan Kunjungan</th>
                        <th>Tipe Pengguna</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tamu as $t)
                        <tr>
                            <td class="ps-4"><span class="fw-semibold">{{ $t->tanggal_kunjungan->translatedFormat('d F Y') }}</span></td>
                            <td><span class="fw-bold text-capitalize">{{ $t->nama_tamu }}</span></td>
                            <td><span class="small">{{ $t->instansi ?? '-' }}</span></td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $t->nomor_hp) }}" target="_blank" class="text-decoration-none">
                                    <i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $t->nomor_hp }}
                                </a>
                            </td>
                            <td><span class="small text-muted" title="{{ $t->keperluan }}">{{ Str::limit($t->keperluan, 80) }}</span></td>
                            <td>
                                @if($t->user_id)
                                    <span class="badge bg-primary-subtle text-primary">Client Terdaftar</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Pengunjung Umum</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.buku-tamu.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan kunjungan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Belum ada kunjungan tamu terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($tamu->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $tamu->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
