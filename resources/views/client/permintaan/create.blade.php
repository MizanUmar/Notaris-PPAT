@extends('layouts.app')

@section('title', 'Buat Permintaan Layanan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Ajukan Layanan Hukum Baru</h2>
            <p class="text-muted mb-0">Isi form pengajuan dan unggah dokumen pendukung awal.</p>
        </div>
        <a href="{{ route('client.permintaan.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Batal / Kembali
        </a>
    </div>

    <form action="{{ route('client.permintaan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Form Area (Left Side) -->
            <div class="col-lg-7">
                <div class="card card-premium p-4">
                    <div class="mb-3">
                        <label for="layanan_id" class="form-label small fw-bold">Pilih Jenis Layanan Hukum</label>
                        <select name="layanan_id" id="layanan_id" class="form-select form-control-premium" required>
                            <option value="" disabled selected>Pilih Layanan Hukum...</option>
                            <optgroup label="Layanan Akta">
                                @foreach($layanan->where('kategori', 'akta') as $lay)
                                <option value="{{ $lay->id }}" {{ !$lay->status_aktif ? 'disabled class=text-muted' : '' }}>
                                    {{ $lay->nama_layanan }} {{ !$lay->status_aktif ? '(🔴 Sedang Tidak Tersedia)' : '' }}
                                </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Layanan Surat">
                                @foreach($layanan->where('kategori', 'surat') as $lay)
                                <option value="{{ $lay->id }}" {{ !$lay->status_aktif ? 'disabled class=text-muted' : '' }}>
                                    {{ $lay->nama_layanan }} {{ !$lay->status_aktif ? '(🔴 Sedang Tidak Tersedia)' : '' }}
                                </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label small fw-bold">Catatan Tambahan / Deskripsi Permohonan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control form-control-premium" rows="4" placeholder="Jelaskan kebutuhan pengurusan Anda, nama objek tanah/bangunan, atau detail pendukung lainnya..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Unggah Berkas Pendukung Awal (Bisa pilih beberapa file sekaligus - PDF/JPG max 5MB/file)</label>
                        <input type="file" name="dokumen[]" id="dokumen_files" class="form-control" multiple>
                        <small class="text-muted d-block mt-1">Anda dapat memilih beberapa file sekaligus dan mengunggah berkas susulan nanti di halaman detail pengajuan.</small>
                    </div>

                    <button type="submit" class="btn btn-premium-primary px-4"><i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan</button>
                </div>
            </div>

            <!-- Requirements Helper Area (Right Side with Checkboxes) -->
            <div class="col-lg-5">
                <div class="card card-premium p-4" id="requirementCard" style="display: none;">
                    <h5 class="fw-bold font-heading mb-2 text-dark border-bottom pb-2">
                        <i class="fa-solid fa-folder-open text-primary me-2"></i> Berkas yang Perlu Disiapkan
                    </h5>
                    <p class="text-muted small mb-3">Harap persiapkan dan centang dokumen berikut untuk mempercepat proses pembuatan akta:</p>
                    <div class="list-group list-group-flush" id="requirementList">
                        <!-- Loaded dynamically via JS with checkboxes -->
                    </div>
                </div>

                <div class="card card-premium p-4 text-center text-muted" id="requirementPlaceholder">
                    <i class="fa-solid fa-folder-open fs-1 text-black-50 mb-3"></i>
                    <p class="mb-0 small">Pilih jenis layanan hukum di samping untuk menampilkan daftar berkas persyaratan wajib.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Prepare requirements data mapping from PHP to JS
    const servicesData = {
        @foreach($layanan as $lay)
        "{{ $lay->id }}": [
            @foreach($lay->persyaratan as $req) {
                id: "{{ $req->id }}",
                nama: "{!! addslashes($req->nama_dokumen) !!}",
                ket: "{!! addslashes($req->keterangan ?? 'Fotokopi / Scan Asli') !!}"
            },
            @endforeach
        ],
        @endforeach
    };

    document.getElementById('layanan_id').addEventListener('change', function() {
        const selectedId = this.value;
        const requirements = servicesData[selectedId] || [];
        const reqList = document.getElementById('requirementList');
        const reqCard = document.getElementById('requirementCard');
        const reqPlaceholder = document.getElementById('requirementPlaceholder');

        reqList.innerHTML = ''; // clear

        if (requirements.length > 0) {
            requirements.forEach((req, idx) => {
                const div = document.createElement('div');
                div.className = 'list-group-item d-flex justify-content-between align-items-center px-2 py-2 border-0 border-bottom bg-white my-1 rounded shadow-xs';
                div.innerHTML = `
                    <div class="form-check mb-0 d-flex align-items-center gap-2">
                        <input type="checkbox" name="persyaratan_ids[]" value="${req.id}" id="req_chk_${req.id}" class="form-check-input me-2 text-primary" checked style="cursor: pointer; width: 1.15em; height: 1.15em;">
                        <label for="req_chk_${req.id}" class="form-check-label fw-semibold text-dark mb-0 small" style="cursor: pointer;">
                            ${req.nama}
                        </label>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill small">${req.ket}</span>
                `;
                reqList.appendChild(div);
            });
            reqCard.style.display = 'block';
            reqPlaceholder.style.display = 'none';
        } else {
            reqCard.style.display = 'none';
            reqPlaceholder.style.display = 'block';
        }
    });
</script>
@endsection