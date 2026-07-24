@extends('layouts.app')

@section('title', 'Layanan Saya')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Riwayat Pengajuan Layanan</h2>
            <p class="text-muted mb-0">Monitor status pengajuan akta dan surat pendukung Anda di sini.</p>
        </div>
        <a href="{{ route('client.permintaan.create') }}" class="btn btn-premium-primary">
            <i class="fa-solid fa-file-signature me-1"></i> Buat Permintaan Baru
        </a>
    </div>

    <!-- Requests List Table -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Pengajuan</th>
                        <th>Jenis Layanan Hukum</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Dokumen Terbit</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $req)
                    <tr>
                        <td class="ps-4"><span class="fw-bold">#{{ $req->id }}</span></td>
                        <td><span class="fw-bold text-primary">{{ $req->layanan->nama_layanan }}</span></td>
                        <td><span class="small">{{ $req->tanggal_permintaan->translatedFormat('d F Y') }}</span></td>
                        <td>
                            @if($req->status === 'Menunggu')
                            <span class="badge badge-waiting">Menunggu Berkas</span>
                            @elseif($req->status === 'Diproses')
                            <span class="badge badge-process">Sedang Diproses</span>
                            @elseif($req->status === 'Selesai')
                            <span class="badge badge-success">Selesai</span>
                            @else
                            <span class="badge badge-danger">Ditolak / Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">

                                @if($req->akta)

                                <a href="{{ asset('storage/' . $req->akta->file_akta) }}"
                                    target="_blank"
                                    class="small text-success text-decoration-none">

                                    <i class="fa-solid fa-file-contract me-1"></i>
                                    Unduh Akta

                                </a>

                                @elseif($req->surat->count())

                                @foreach($req->surat as $sur)

                                <a href="{{ asset('storage/' . $sur->file_surat) }}"
                                    target="_blank"
                                    class="small text-info text-decoration-none">

                                    <i class="fa-solid fa-envelope-open-text me-1"></i>
                                    Unduh Surat

                                </a>

                                @endforeach

                                @else

                                <span class="text-muted small">
                                    Belum terbit
                                </span>

                                @endif

                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('client.permintaan.show', $req->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Cek Berkas / Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Anda belum memiliki pengajuan layanan. Silakan klik tombol "Buat Permintaan Baru" untuk memulai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection