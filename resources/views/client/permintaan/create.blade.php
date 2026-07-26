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

    <div class="row g-4">
        <!-- Form Area -->
        <div class="col-lg-7">
            <div class="card card-premium p-4">
                <form action="{{ route('client.permintaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="layanan_id" class="form-label small fw-bold">Pilih Jenis Layanan Hukum</label>
                        <select name="layanan_id" id="layanan_id" class="form-select form-control-premium" required>
                            <option value="" disabled selected>Pilih Layanan Hukum...</option>
                            <optgroup label="Layanan Akta">
                                @foreach($layanan->where('kategori', 'akta') as $lay)
                                <option value="{{ $lay->id }}">{{ $lay->nama_layanan }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Layanan Surat">
                                @foreach($layanan->where('kategori', 'surat') as $lay)
                                <option value="{{ $lay->id }}">{{ $lay->nama_layanan }}</option>
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
                        <input type="file" name="dokumen[]" class="form-control" multiple>
                        <small class="text-muted d-block mt-1">Anda juga dapat mengunggah berkas susulan nanti di halaman detail pengajuan.</small>
                    </div>

                    <button type="submit" class="btn btn-premium-primary px-4"><i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan</button>
                </form>
            </div>
        </div>

        <!-- Requirements Helper Area -->
        <div class="col-lg-5">
            <div class="card card-premium p-4" id="requirementCard" style="display: none;">
                <h5 class="fw-bold font-heading mb-3 text-dark border-bottom pb-2"><i class="fa-solid fa-folder-open text-primary me-2"></i> Berkas yang Perlu Disiapkan</h5>
                <p class="text-muted small">Harap persiapkan dan unggah dokumen berikut untuk mempercepat proses pembuatan akta:</p>
                <ul class="list-group list-group-flush small" id="requirementList">
                    <!-- Loaded dynamically via JS -->
                </ul>
            </div>

            <div class="card card-premium p-4 text-center text-muted" id="requirementPlaceholder">
                <i class="fa-solid fa-folder-open fs-1 text-black-50 mb-3"></i>
                <p class="mb-0 small">Pilih jenis layanan hukum di samping untuk menampilkan daftar berkas persyaratan wajib.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Prepare requirements data mapping from PHP to JS
    const servicesData = {
        @foreach($layanan as $lay)
        "{{ $lay->id }}": [
            @foreach($lay - > persyaratan as $req) {
                nama: "{{ $req->nama_dokumen }}",
                ket: "{{ $req->keterangan ?? 'Fotokopi / Scan Asli' }}"
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
            requirements.forEach(req => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 border-bottom';
                li.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-square-check text-primary"></i>
                        <span>${req.nama}</span>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill">${req.ket}</span>
                `;
                reqList.appendChild(li);
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