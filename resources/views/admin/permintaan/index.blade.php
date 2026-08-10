@extends('layouts.app')

@section('title', 'Permintaan Layanan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Daftar Permintaan Layanan</h2>
            <p class="text-muted mb-0">Kelola pengajuan layanan hukum dan berkas kelengkapan client.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('admin.permintaan.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">Cari Client</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama client..." value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu" {{ $status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diproses" {{ $status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ $status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Jenis Layanan</label>
                    <select name="layanan_id" class="form-select">
                        <option value="">Semua Layanan</option>
                        @foreach($layananList as $l)
                            <option value="{{ $l->id }}" {{ $layananId == $l->id ? 'selected' : '' }}>{{ $l->nama_layanan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100 py-2"><i class="fa-solid fa-filter"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Pengajuan</th>
                        <th>Client / NIK</th>
                        <th>Layanan</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th>Lihat Dokumen</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $req)
                        <tr>
                            <td class="ps-4"><span class="fw-bold text-dark">#{{ $req->id }}</span></td>
                            <td>
                                <span class="fw-bold d-block text-capitalize">{{ $req->client->user->nama ?? '-' }}</span>
                                <small class="text-muted">NIK: {{ $req->client->nik }}</small>
                            </td>
                            <td><span class="fw-medium text-primary">{{ $req->layanan->nama_layanan }}</span></td>
                            <td><span class="small">{{ $req->tanggal_permintaan->translatedFormat('d F Y') }}</span></td>
                            <td>
                                @if($req->status === 'Menunggu')
                                    <span class="badge badge-waiting">Menunggu</span>
                                @elseif($req->status === 'Diproses')
                                    <span class="badge badge-process">Diproses</span>
                                @elseif($req->status === 'Selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($req->dokumenClient->count() > 0)
                                    @if($req->dokumenClient->count() === 1)
                                        @php $singleDoc = $req->dokumenClient->first(); @endphp
                                        <a href="{{ asset('storage/' . $singleDoc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-3 py-1 px-2 fw-semibold" title="{{ $singleDoc->nama_file }}">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    @else
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-info dropdown-toggle rounded-3 py-1 px-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-eye me-1"></i> Lihat ({{ $req->dokumenClient->count() }})
                                            </button>
                                            <ul class="dropdown-menu shadow border-0">
                                                <li class="dropdown-header small text-muted font-heading">Dokumen Upload Client:</li>
                                                @foreach($req->dokumenClient as $doc)
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
                            <td class="pe-4 text-end">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('admin.permintaan.show', $req->id) }}" class="btn btn-sm btn-premium-primary"><i class="fa-solid fa-gear"></i> Proses</a>
                                    
                                    @if(!in_array($req->status, ['Diproses', 'Selesai']))
                                        <form action="{{ route('admin.permintaan.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini beserta seluruh file dokumen pendukung?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Permintaan yang diproses / selesai tidak dapat dihapus"><i class="fa-solid fa-lock"></i> Hapus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada pengajuan permintaan layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($permintaan->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $permintaan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
