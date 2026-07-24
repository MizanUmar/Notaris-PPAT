@extends('layouts.app')

@section('title', 'Permintaan Surat - Notaris Eka Sulistya')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i>Permintaan Surat Resmi</h3>
            <p class="text-muted mb-0">
                Daftar permintaan layanan client yang siap diproses pembuatan surat keterangan / kuasa / pernyataan.
            </p>
        </div>
    </div>

    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Client</th>
                        <th>Layanan</th>
                        <th>Tanggal Permintaan</th>
                        <th>Status</th>
                        <th class="pe-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $item)
                    <tr>
                        <td class="ps-4">{{ $loop->iteration }}</td>
                        <td>
                            <span class="fw-semibold text-dark text-capitalize">{{ $item->client->user->nama }}</span>
                            <small class="text-muted d-block">{{ $item->client->user->email }}</small>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $item->layanan->nama_layanan }}</span></td>
                        <td><span class="small">{{ $item->created_at->translatedFormat('d F Y') }}</span></td>
                        <td>
                            <span class="badge badge-process">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="pe-4 text-center">
                            <a href="{{ route('admin.surat.create', $item->id) }}" class="btn btn-primary btn-sm px-3 fw-semibold">
                                <i class="fa-solid fa-file-signature me-1"></i> Buat Surat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-folder-open fs-3 d-block mb-2"></i>
                            Belum ada permintaan surat resmi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
