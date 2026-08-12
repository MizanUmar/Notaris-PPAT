@extends('layouts.app')

@section('title', 'Detail Layanan Saya')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Detail Layanan #{{ $permintaan->id }}</h2>
            <p class="text-muted mb-0">Lihat status pengerjaan, berkas terupload, dan unduh dokumen terbit.</p>
        </div>
        <a href="{{ route('client.permintaan.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if($permintaan->status === 'Menunggu' && !$permintaan->isDokumenLengkap())
        <div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4 p-3 rounded-3" role="alert">
            <div class="d-flex align-items-start gap-3">
                <div class="icon-circle bg-warning-subtle text-warning mb-0" style="width: 42px; height: 42px; border-radius: 8px; flex-shrink: 0;">
                    <i class="fa-solid fa-bell fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Notifikasi Kelengkapan Dokumen: Berkas Belum Lengkap</h6>
                    <p class="mb-0 text-muted small">
                        Pengajuan permohonan layanan Anda belum dapat diproses oleh Notaris karena berkas persyaratan belum lengkap (Terverifikasi: <strong>{{ $permintaan->jumlah_berkas_tercentang }}</strong> dari <strong>{{ $permintaan->jumlah_berkas_wajib }}</strong> dokumen). Silakan unggah seluruh dokumen persyaratan yang bertanda <span class="text-danger fw-bold">(Belum Tercentang)</span> di bawah ini agar permohonan Anda dapat segera diproses.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Status & Outputs -->
        <div class="col-lg-5">
            <div class="card card-premium p-4 mb-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Status Pengajuan</h5>

                <div class="mb-3">
                    <small class="text-muted d-block small">Jenis Layanan</small>
                    <span class="fw-bold text-primary">{{ $permintaan->layanan->nama_layanan }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block small">Tanggal Pengajuan</small>
                    <span class="fw-semibold">{{ $permintaan->tanggal_permintaan->translatedFormat('d F Y') }}</span>
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block small">Status Saat Ini</small>
                    @if($permintaan->status === 'Menunggu')
                    <span class="badge badge-waiting d-inline-block py-2 px-3 fs-6">Menunggu Kelengkapan Berkas</span>
                    @elseif($permintaan->status === 'Diproses')
                    <span class="badge badge-process d-inline-block py-2 px-3 fs-6">Sedang Diproses Notaris</span>
                    @elseif($permintaan->status === 'Selesai')
                    <span class="badge badge-success d-inline-block py-2 px-3 fs-6">Selesai / Terbit Dokumen</span>
                    @else
                    <span class="badge badge-danger d-inline-block py-2 px-3 fs-6">Ditolak / Dibatalkan</span>
                    @endif
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block small">Catatan / Keterangan Notaris</small>
                    <p class="mb-0 bg-light p-3 rounded text-muted small border-start border-3 border-dark-subtle">{{ $permintaan->keterangan ?? 'Belum ada catatan tambahan dari Notaris.' }}</p>
                </div>
            </div>

            <!-- Output Files (Deeds & Letters) -->
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2"><i class="fa-solid fa-file-shield text-success me-2"></i> Dokumen Terbit / Hasil</h5>

                <div class="card card-premium p-4">

                    <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">
                        <i class="fa-solid fa-file-shield text-success me-2"></i>
                        Dokumen Terbit / Hasil
                    </h5>

                    <div class="d-flex flex-column gap-3">

                        @php
                        $hasOutput = false;
                        @endphp

                        {{-- ================= AKTA ================= --}}
                        @if($permintaan->akta)

                        @php
                        $akt = $permintaan->akta;
                        $hasOutput = true;
                        @endphp

                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-success-subtle border-start border-success border-4">

                            <div>

                                <span class="d-block fw-bold text-success small">
                                    AKTA TERBIT
                                </span>

                                <h6 class="mb-1 fw-bold">
                                    {{ $akt->nama_akta }}
                                </h6>

                                <small class="text-muted">
                                    Nomor Akta :
                                    {{ $akt->nomor_akta }}
                                </small>

                                <br>

                                <small class="text-muted">
                                    Tanggal :
                                    {{ \Carbon\Carbon::parse($akt->tanggal_akta)->translatedFormat('d F Y') }}
                                </small>

                            </div>

                            <div class="d-flex gap-1">
                                <a href="{{ route('client.akta.preview', $akt->id) }}"
                                    class="btn btn-success fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    Lihat Akta
                                </a>
                            </div>

                        </div>

                        @endif

                        {{-- ================= SURAT ================= --}}
                        @foreach($permintaan->surat as $sur)

                        @php
                        $hasOutput = true;
                        @endphp

                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-info-subtle border-start border-info border-4">

                            <div>

                                <span class="d-block fw-bold text-info small">
                                    SURAT TERBIT
                                </span>

                                <h6 class="mb-1 fw-bold">
                                    {{ $sur->jenis_surat }}
                                </h6>

                                <small class="text-muted">
                                    Nomor Surat :
                                    {{ $sur->nomor_surat }}
                                </small>

                            </div>

                            <div class="d-flex gap-1">
                                <a href="{{ route('client.surat.preview', $sur->id) }}"
                                    class="btn btn-info text-white fw-semibold">
                                    <i class="fa-solid fa-eye me-1"></i>
                                    Lihat Surat
                                </a>
                            </div>

                        </div>

                        @endforeach

                        {{-- ================= BELUM ADA DOKUMEN ================= --}}
                        @if(!$hasOutput)

                        <div class="text-center py-4">

                            <i class="fa-solid fa-hourglass-half fs-1 text-secondary mb-3"></i>

                            <p class="text-muted mb-0">

                                Dokumen hasil akta ataupun surat belum diterbitkan oleh Notaris.

                            </p>

                        </div>

                        @endif

                    </div>

                </div>
            </div>
        </div>

        <!-- Requirements & Uploads -->
        <div class="col-lg-7">
            <!-- Checklist -->
            <div class="card card-premium mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold font-heading mb-0">
                        <i class="fa-solid fa-list-check text-primary me-2"></i>
                        Kelengkapan Berkas Persyaratan
                    </h5>
                </div>

                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        @forelse($permintaan->layanan->persyaratan as $req)

                        @php
                        $checklist = $permintaan->checklistPersyaratan
                        ->where('persyaratan_id', $req->id)
                        ->first();
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">

                            <div class="d-flex align-items-center gap-2">

                                <input
                                    type="checkbox"
                                    class="form-check-input checklist"
                                    data-permintaan="{{ $permintaan->id }}"
                                    data-persyaratan="{{ $req->id }}"
                                    {{ ($checklist && $checklist->status) ? 'checked' : '' }}>

                                <span class="{{ ($checklist && $checklist->status) ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $req->nama_dokumen }}
                                </span>

                            </div>

                            <span class="badge bg-light text-dark rounded-pill">
                                {{ $req->keterangan }}
                            </span>

                        </li>

                        @empty

                        <li class="list-group-item text-muted">
                            Tidak memerlukan berkas kelengkapan khusus.
                        </li>

                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Uploaded Files & Upload Form -->
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2"><i class="fa-solid fa-file-arrow-up text-primary me-2"></i> File Dokumen Terupload</h5>

                @if($permintaan->status === 'Menunggu')
                <!-- Upload Box -->
                <form action="{{ route('client.permintaan.upload-dokumen', $permintaan->id) }}" method="POST" enctype="multipart/form-data" class="mb-4 bg-light p-3 rounded border-dashed">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Pilih Berkas Persyaratan (Opsional)</label>
                            <select name="persyaratan_id" class="form-select form-select-sm">
                                <option value="">-- Deteksi Otomatis / Berkas Umum --</option>
                                @foreach($permintaan->layanan->persyaratan as $req)
                                    @php
                                    $chk = $permintaan->checklistPersyaratan->where('persyaratan_id', $req->id)->first();
                                    $isDone = ($chk && $chk->status);
                                    @endphp
                                    <option value="{{ $req->id }}" {{ $isDone ? 'disabled class=text-muted' : '' }}>
                                        {{ $req->nama_dokumen }} {{ $isDone ? '(Tercentang)' : '(Belum Tercentang)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Pilih File untuk Diunggah</label>
                            <input type="file" name="dokumen" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-sm btn-primary px-4"><i class="fa-solid fa-upload me-1"></i> Upload Berkas</button>
                        </div>
                    </div>
                </form>
                @endif

                <div class="row g-3">
                    @forelse($permintaan->dokumenClient as $doc)
                    <div class="col-sm-6">
                        <div class="p-3 border rounded bg-white shadow-xs d-flex align-items-start gap-2 justify-content-between">
                            <div class="overflow-hidden d-flex gap-2">
                                <i class="fa-solid fa-file-pdf text-danger fs-3 mt-1"></i>
                                <div class="overflow-hidden">
                                    <span class="d-block fw-bold small text-truncate" style="max-width: 150px;" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</span>
                                    <small class="text-muted d-block text-xs">{{ $doc->tanggal_upload->translatedFormat('d M Y') }}</small>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="small text-decoration-none"><i class="fa-solid fa-eye me-1"></i> Lihat Dokumen</a>
                                </div>
                            </div>
                            @if($permintaan->status === 'Menunggu')
                            <form action="{{ route('client.dokumen.delete', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus file ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0 mt-1"><i class="fa-solid fa-circle-xmark fs-5"></i></button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-12 py-4 text-center text-muted">
                        <i class="fa-solid fa-file-circle-xmark fs-2 mb-2 text-black-50"></i>
                        <p class="small mb-0">Belum ada file dokumen diunggah.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('.checklist').change(function () {
    const $label = $(this).next('span');
    if ($(this).is(':checked')) {
        $label.addClass('text-decoration-line-through text-muted');
    } else {
        $label.removeClass('text-decoration-line-through text-muted');
    }

    $.ajax({
        url: "{{ route('admin.checklist.update') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            permintaan_id: $(this).data('permintaan'),
            persyaratan_id: $(this).data('persyaratan'),
            status: $(this).is(':checked') ? 1 : 0
        },
        success: function () {
            console.log('Checklist berhasil disimpan');
        }
    });
});
</script>
@endsection