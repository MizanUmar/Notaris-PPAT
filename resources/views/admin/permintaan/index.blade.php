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
                            <td class="pe-4 text-end">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('admin.permintaan.show', $req->id) }}" class="btn btn-sm btn-premium-primary"><i class="fa-solid fa-gear"></i> Proses</a>
                                    
                                    <form action="{{ route('admin.permintaan.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengajuan ini beserta seluruh file dokumen pendukung?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
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
