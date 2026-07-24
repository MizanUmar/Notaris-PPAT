@extends('layouts.app')

@section('title', 'Persyaratan Berkas Layanan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Persyaratan Berkas</h2>
            <p class="text-muted mb-0">Informasi dokumen kelengkapan yang wajib disiapkan untuk setiap pengajuan layanan hukum.</p>
        </div>
        <!--<a href="{{ route('client.permintaan.create') }}" class="btn btn-premium-primary">
            <i class="fa-solid fa-file-signature me-1"></i> Mulai Pengajuan Baru
        </a>-->
    </div>

    <!-- Alert Tip -->
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert" style="background-color: #fff0f2; border-left: 4px solid #800020 !important; color: #800020;">
        <i class="fa-solid fa-circle-info fs-5 me-3"></i>
        <div>
            <strong class="d-block">Tips Persiapan Berkas:</strong>
            <span class="small opacity-90">Silakan pindai (scan) dokumen persyaratan asli Anda ke format <strong>PDF/JPG/PNG</strong> dengan ukuran maksimal <strong>5 MB</strong> per file sebelum melakukan pengajuan.</span>
        </div>
    </div>

    <!-- Accordion Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="accordion shadow-sm border-0" id="accordionRequirements">
                @forelse($layanan as $lay)
                    <div class="card card-premium border-0 mb-3 overflow-hidden">
                        <div class="card-header bg-white border-0 p-0" id="heading-{{ $lay->id }}">
                            <button class="accordion-button collapsed fw-bold font-heading py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $lay->id }}" aria-expanded="false" aria-controls="collapse-{{ $lay->id }}" style="background-color: #ffffff; color: #1e293b; font-size: 1.05rem;">
                                <i class="fa-solid fa-folder-open text-primary me-3 fs-5"></i> 
                                <div class="d-flex flex-column flex-sm-row justify-content-between w-100 align-items-start align-items-sm-center pe-3">
                                    <span>{{ $lay->nama_layanan }}</span>
                                    <span class="badge bg-light text-dark fw-normal rounded-pill mt-1 mt-sm-0 small" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1 text-primary"></i> Estimasi: {{ $lay->estimasi_waktu }}</span>
                                </div>
                            </button>
                        </div>
                        <div id="collapse-{{ $lay->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $lay->id }}" data-bs-parent="#accordionRequirements">
                            <div class="card-body bg-white pt-0 px-4 pb-4">
                                <hr class="my-3 opacity-10">
                                
                                @if($lay->deskripsi)
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-muted small text-uppercase tracking-wider mb-2">Deskripsi Layanan</h6>
                                        <p class="text-muted small mb-0">{{ $lay->deskripsi }}</p>
                                    </div>
                                @endif

                                <h6 class="fw-bold text-muted small text-uppercase tracking-wider mb-3">Daftar Dokumen yang Harus Diunggah</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border-0 mb-0">
                                        <thead>
                                            <tr class="text-muted small border-bottom">
                                                <th class="border-0 pb-2 ps-0" style="width: 40px;">No</th>
                                                <th class="border-0 pb-2" style="width: 250px;">Nama Dokumen</th>
                                                <th class="border-0 pb-2">Keterangan / Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($lay->persyaratan as $req)
                                                <tr class="border-bottom-0">
                                                    <td class="ps-0 text-muted fw-bold border-0">{{ $loop->iteration }}</td>
                                                    <td class="fw-semibold text-dark border-0">
                                                        <i class="fa-solid fa-file-circle-check text-primary me-2"></i>
                                                        {{ $req->nama_dokumen }}
                                                    </td>
                                                    <td class="text-muted small border-0">
                                                        <span class="bg-light px-3 py-1 rounded text-dark-emphasis d-inline-block">{{ $req->keterangan ?? 'Wajib berkas asli / fotokopi jelas' }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted border-0">
                                                        <i class="fa-solid fa-info-circle fs-3 mb-2 text-black-50"></i>
                                                        <p class="small mb-0">Tidak memerlukan berkas kelengkapan khusus untuk layanan ini. Hubungi Staf Notaris untuk detail lebih lanjut.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card card-premium p-5 text-center text-muted">
                        <i class="fa-solid fa-file-circle-xmark fs-1 mb-3 text-black-50"></i>
                        <h5 class="fw-bold">Belum Ada Layanan Tersedia</h5>
                        <p class="small mb-0">Saat ini data layanan dan persyaratan belum ditambahkan oleh administrator.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
