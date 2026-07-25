@extends('layouts.app')

@section('title', 'Detail Client')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Detail Profil Client</h2>
            <p class="text-muted mb-0">Rincian data pribadi dan riwayat aktivitas client.</p>
        </div>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Client Profile Details -->
        <div class="col-lg-4">
            <div class="card card-premium p-4 text-center mb-4">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($client->user->nama ?? 'C', 0, 1)) }}
                </div>
                <h4 class="fw-bold text-capitalize mb-1">{{ $client->user->nama ?? '-' }}</h4>
                <span class="badge bg-light text-dark mb-4">Client Terdaftar</span>
                
                <div class="text-start border-top pt-3">
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase small">NIK</small>
                        <span class="fw-semibold">{{ $client->nik }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase small">Username</small>
                        <span class="fw-semibold">{{ $client->user->username ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase small">Nomor WhatsApp</small>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_hp) }}" target="_blank" class="text-decoration-none fw-semibold">
                            <i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $client->no_hp }}
                        </a>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase small">Email</small>
                        <span class="fw-semibold">{{ $client->email }}</span>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block uppercase small">Alamat Tinggal</small>
                        <span class="fw-semibold">{{ $client->alamat }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- History & Activity -->
        <div class="col-lg-8">
            <div class="card card-premium">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Riwayat Pengajuan Layanan</h5>
                </div>
                <div class="table-responsive px-4 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Arsip Hasil</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->permintaan as $req)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-primary d-block">{{ $req->layanan->nama_layanan }}</span>
                                        <small class="text-muted">ID: #{{ $req->id }}</small>
                                    </td>
                                    <td><span class="small">{{ $req->tanggal_permintaan->translatedFormat('d M Y') }}</span></td>
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
                                        <div class="d-flex flex-column gap-1">
                                            @if($req->akta)
                                                <span class="small text-success"><i class="fa-solid fa-file-contract me-1"></i> Akta: {{ $req->akta->nomor_akta }}</span>
                                            @endif
                                            @foreach($req->surat as $sur)
                                                <span class="small text-info"><i class="fa-solid fa-envelope-open-text me-1"></i> Surat: {{ $sur->nomor_surat }}</span>
                                            @endforeach
                                            @if(!$req->akta && $req->surat->isEmpty())
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.permintaan.show', $req->id) }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-eye"></i> Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat layanan untuk client ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
