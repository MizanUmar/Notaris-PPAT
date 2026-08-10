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
                        <th>Lihat Dokumen</th>
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
                        <td>
                            @if($item->dokumenClient->count() > 0)
                                @if($item->dokumenClient->count() === 1)
                                    @php $singleDoc = $item->dokumenClient->first(); @endphp
                                    <a href="{{ asset('storage/' . $singleDoc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-3 py-1 px-2 fw-semibold" title="{{ $singleDoc->nama_file }}">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-info dropdown-toggle rounded-3 py-1 px-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat ({{ $item->dokumenClient->count() }})
                                        </button>
                                        <ul class="dropdown-menu shadow border-0">
                                            <li class="dropdown-header small text-muted font-heading">Dokumen Upload Client:</li>
                                            @foreach($item->dokumenClient as $doc)
                                                <li>
                                                    <a class="dropdown-item small d-flex align-items-center justify-content-between py-2" href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">
                                                        <span class="text-truncate" style="max-width: 170px;" title="{{ $doc->nama_file }}"><i class="fa-solid fa-file-pdf text-danger me-2"></i>{{ $doc->nama_file }}</span>
                                                        <i class="fa-solid fa-arrow-up-right-from-square ms-2 text-muted fs-xs"></i>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @else
                                <span class="text-muted small"><i class="fa-regular fa-file me-1"></i> Belum ada file</span>
                            @endif
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
