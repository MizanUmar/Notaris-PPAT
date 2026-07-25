@extends('layouts.app')

@section('title', 'Arsip Akta Saya')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Arsip Akta Saya</h2>
            <p class="text-muted mb-0">Lihat dan unduh berkas akta resmi yang telah diterbitkan oleh Notaris.</p>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('client.akta.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari akta berdasarkan Nomor Akta, Nama Akta, atau Jenis Layanan..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Akta Table Card -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Akta</th>
                        <th>Nama Akta</th>
                        <th>Tanggal Terbit</th>
                        <th>Layanan Hukum</th>
                        <th class="pe-4 text-end">Berkas Digital</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($akta as $akt)
                    <tr>
                        <td class="ps-4"><span class="fw-bold font-monospace text-dark">{{ $akt->nomor_akta }}</span></td>
                        <td><span class="fw-semibold">{{ $akt->nama_akta }}</span></td>
                        <td><span class="small">{{ $akt->tanggal_akta->translatedFormat('d F Y') }}</span></td>
                        <td>
                            <span class="fw-medium text-capitalize d-block text-primary">{{ $akt->permintaan->layanan->nama_layanan ?? '-' }}</span>
                            <small class="text-muted">No. Pengajuan: #{{ $akt->permintaan_id }}</small>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('client.akta.preview', $akt->id) }}" class="btn btn-sm btn-outline-info py-1 px-3 rounded">
                                    <i class="fa-solid fa-eye me-1"></i> Lihat Akta
                                </a>
                                <a href="{{ asset('storage/' . $akt->file_akta) }}" target="_blank" class="btn btn-sm btn-success py-1 px-3 rounded">
                                    <i class="fa-solid fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-file-excel fs-1 mb-3 text-secondary"></i>
                            <p class="mb-0">Belum ada arsip akta digital yang diterbitkan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($akta->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $akta->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
