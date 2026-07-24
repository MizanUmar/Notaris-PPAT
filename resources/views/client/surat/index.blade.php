@extends('layouts.app')

@section('title', 'Arsip Surat Saya - Notaris Eka Sulistya')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Arsip Surat Saya</h2>
            <p class="text-muted mb-0">Lihat dan unduh berkas surat keterangan / kuasa / pernyataan resmi yang telah diterbitkan oleh Notaris.</p>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('client.surat.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari surat berdasarkan Nomor Surat, Jenis Surat, atau Nama Layanan..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Surat Table Card -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Surat</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Terbit</th>
                        <th>Layanan Hukum</th>
                        <th class="pe-4 text-end">Berkas Digital</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surat as $sur)
                    <tr>
                        <td class="ps-4"><span class="fw-bold font-monospace text-dark">{{ $sur->nomor_surat }}</span></td>
                        <td><span class="fw-semibold">{{ $sur->jenis_surat }}</span></td>
                        <td><span class="small">{{ $sur->tanggal_surat->translatedFormat('d F Y') }}</span></td>
                        <td>
                            <span class="fw-medium text-capitalize d-block text-primary">{{ $sur->permintaan->layanan->nama_layanan ?? '-' }}</span>
                            <small class="text-muted">No. Pengajuan: #{{ $sur->permintaan_id }}</small>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ asset('storage/' . $sur->file_surat) }}" target="_blank" class="btn btn-sm btn-success py-1 px-3 rounded">
                                <i class="fa-solid fa-download me-1"></i> Unduh Surat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-file-excel fs-1 mb-3 text-secondary"></i>
                            <p class="mb-0">Belum ada arsip surat digital yang diterbitkan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($surat->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $surat->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
