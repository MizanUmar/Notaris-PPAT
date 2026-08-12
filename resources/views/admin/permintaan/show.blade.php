@extends('layouts.app')

@section('title', 'Proses Permintaan Layanan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Proses Permintaan Layanan #{{ $permintaan->id }}</h2>
            <p class="text-muted mb-0">Kelola berkas persyaratan, kelayakan, status pengerjaan, dan arsip dokumen.</p>
        </div>
        <a href="{{ route('admin.permintaan.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Details & Status Updator -->
        <div class="col-lg-5">
            <div class="card card-premium p-4 mb-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Informasi Pengajuan</h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block small">Nama Client</small>
                    <a href="{{ route('admin.clients.show', $permintaan->client->id) }}" class="fw-bold text-decoration-none text-capitalize">
                        {{ $permintaan->client->user->nama }}
                    </a>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block small">Layanan yang Diajukan</small>
                    <span class="fw-bold text-primary">{{ $permintaan->layanan->nama_layanan }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block small">Tanggal Masuk</small>
                    <span class="fw-semibold">{{ $permintaan->tanggal_permintaan->translatedFormat('d F Y') }}</span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block small">Status Saat Ini</small>
                    @if($permintaan->status === 'Menunggu')
                        <span class="badge badge-waiting">Menunggu Berkas / Verifikasi</span>
                    @elseif($permintaan->status === 'Diproses')
                        <span class="badge badge-process">Sedang Diproses</span>
                    @elseif($permintaan->status === 'Selesai')
                        <span class="badge badge-success">Selesai / Terbit Dokumen</span>
                    @else
                        <span class="badge badge-danger">Ditolak / Dibatalkan</span>
                    @endif
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block small">Catatan Tambahan</small>
                    <p class="mb-0 bg-light p-3 rounded text-muted small border-start border-3 border-dark-subtle">{{ $permintaan->keterangan ?? 'Tidak ada catatan tambahan.' }}</p>
                </div>

                <!-- Update Status Form -->
                <form action="{{ route('admin.permintaan.update-status', $permintaan->id) }}" method="POST" class="border-top pt-3">
                    @csrf
                    <h6 class="fw-bold font-heading mb-3"><i class="fa-solid fa-pen-fancy me-1 text-primary"></i> Perbarui Status Layanan</h6>
                    
                    @if(!$permintaan->isDokumenLengkap())
                        <div class="alert alert-warning border-warning shadow-xs mb-3 p-3 rounded-3" role="alert">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa-solid fa-triangle-exclamation text-warning fs-5 mt-1"></i>
                                <div>
                                    <strong class="d-block text-dark small">Berkas Persyaratan Belum Lengkap!</strong>
                                    <span class="small text-muted">Tercentang: <strong>{{ $permintaan->jumlah_berkas_tercentang }}</strong> dari <strong>{{ $permintaan->jumlah_berkas_wajib }}</strong> berkas wajib. Admin tidak dapat memproses layanan ini sebelum seluruh berkas persyaratan tercentang.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Status Baru</label>
                        <select name="status" class="form-select" required>
                            <option value="Menunggu" {{ $permintaan->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diproses" {{ $permintaan->status === 'Diproses' ? 'selected' : '' }} {{ !$permintaan->isDokumenLengkap() && $permintaan->status !== 'Diproses' ? 'disabled class=text-muted' : '' }}>
                                Diproses {{ !$permintaan->isDokumenLengkap() && $permintaan->status !== 'Diproses' ? '(Terkunci - Berkas Belum Lengkap)' : '' }}
                            </option>
                            <option value="Selesai" {{ $permintaan->status === 'Selesai' ? 'selected' : '' }} {{ !$permintaan->isDokumenLengkap() && $permintaan->status !== 'Selesai' ? 'disabled class=text-muted' : '' }}>
                                Selesai {{ !$permintaan->isDokumenLengkap() && $permintaan->status !== 'Selesai' ? '(Terkunci - Berkas Belum Lengkap)' : '' }}
                            </option>
                            <option value="Ditolak" {{ $permintaan->status === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan Perkembangan / Alasan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Berkas belum lengkap, silakan lengkapi fotokopi KTP dan Sertifikat...">{{ $permintaan->keterangan }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-premium-primary w-100">Perbarui Status</button>
                </form>
            </div>

            <!-- Documents Output Section (Deeds & Letters archives linked) -->
            <div class="card card-premium p-4">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2">Arsip Dokumen Terbit</h5>
                <div class="d-flex flex-column gap-2 mb-3">
                    @if($permintaan->akta)
                        @php $akt = $permintaan->akta; @endphp
                        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-success-subtle border-start border-success border-3">
                            <span class="small fw-semibold text-success"><i class="fa-solid fa-file-contract me-1"></i> Akta: {{ $akt->nomor_akta }}</span>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.akta.preview', $akt->id) }}" class="btn btn-xs btn-outline-success py-0 px-2" title="Lihat"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ asset('storage/' . $akt->file_akta) }}" download class="btn btn-xs btn-success py-0 px-2 text-white" title="Unduh Langsung"><i class="fa-solid fa-download"></i></a>
                            </div>
                        </div>
                    @else
                        <span class="text-muted small">Belum ada akta terbit untuk permintaan ini.</span>
                    @endif

                    @forelse($permintaan->surat as $sur)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-info-subtle border-start border-info border-3">
                            <span class="small fw-semibold text-info"><i class="fa-solid fa-envelope-open-text me-1"></i> Surat: {{ $sur->nomor_surat }}</span>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.surat.preview', $sur->id) }}" class="btn btn-xs btn-outline-info py-0 px-2" title="Lihat"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ asset('storage/' . $sur->file_surat) }}" download class="btn btn-xs btn-info py-0 px-2 text-white" title="Unduh Langsung"><i class="fa-solid fa-download"></i></a>
                            </div>
                        </div>
                    @empty
                        <span class="text-muted small">Belum ada surat terbit untuk permintaan ini.</span>
                    @endforelse
                </div>

                @if($permintaan->status === 'Diproses' || $permintaan->status === 'Selesai')
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('admin.akta.index') }}?permintaan_id={{ $permintaan->id }}" class="btn btn-sm btn-success w-100 rounded-3"><i class="fa-solid fa-plus me-1"></i> Terbitkan Akta</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.surat.index') }}?permintaan_id={{ $permintaan->id }}" class="btn btn-sm btn-info text-white w-100 rounded-3"><i class="fa-solid fa-plus me-1"></i> Terbitkan Surat</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Checklists & Uploaded Files -->
        <div class="col-lg-7">
            <!-- Checklist of Requirements -->
            <div class="card card-premium mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i> Checklist Berkas Persyaratan</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        @forelse($permintaan->layanan->persyaratan as $req)
                            @php
                                // Check if user has uploaded a file with a similar name
                                $isUploaded = $permintaan->dokumenClient->contains(function($value) use ($req) {
                                    return Str::contains(strtolower($value->nama_file), strtolower(explode(' ', $req->nama_dokumen)[0]));
                                });
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                    $checked = $permintaan->checklistPersyaratan
                                        ->where('persyaratan_id', $req->id)
                                        ->first();
                                    @endphp

                                    <input
                                        type="checkbox"
                                        class="form-check-input checklist"
                                        data-permintaan="{{ $permintaan->id }}"
                                        data-persyaratan="{{ $req->id }}"
                                        {{ optional($checked)->status ? 'checked' : '' }}>
                                    <span class="{{ $isUploaded ? 'text-decoration-line-through text-muted' : '' }}">{{ $req->nama_dokumen }}</span>
                                </div>
                                <span class="badge bg-light text-dark rounded-pill">{{ $req->keterangan }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Layanan ini tidak memerlukan berkas khusus.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Uploaded Files -->
            <div class="card card-premium">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-file-pdf text-primary me-2"></i> File Dokumen yang Diunggah</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        @forelse($permintaan->dokumenClient as $doc)
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-start gap-2 shadow-sm position-relative">
                                    <i class="fa-solid fa-file-lines text-primary fs-3 mt-1"></i>
                                    <div class="overflow-hidden">
                                        <span class="d-block fw-bold small text-truncate" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</span>
                                        <small class="text-muted d-block text-xs">Diunggah: {{ $doc->tanggal_upload->translatedFormat('d M, H:i') }}</small>
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-link text-decoration-none p-0 mt-1 small"><i class="fa-solid fa-eye me-1"></i> Lihat Dokumen</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-4 text-center text-muted">
                                <i class="fa-solid fa-file-circle-xmark fs-2 mb-2 text-black-50"></i>
                                <p class="small mb-0">Client belum mengunggah dokumen digital.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$('.checklist').change(function () {

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
