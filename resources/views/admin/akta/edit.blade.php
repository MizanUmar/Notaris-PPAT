@extends('layouts.app')

@section('title', 'Edit Akta - Notaris Eka Sulistya')

@section('content')
<div class="container-fluid py-4">

    <div class="card card-premium">

        <div class="card-header bg-white py-3">
            <h3 class="fw-bold mb-0">
                <i class="fa fa-file-signature text-primary me-2"></i>
                Edit Akta
            </h3>
        </div>

        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Nama Client</label>
                    <div class="fw-semibold text-dark">{{ $permintaan->client->user->nama ?? '-' }}</div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Layanan</label>
                    <div class="fw-semibold text-primary">{{ $permintaan->layanan->nama_layanan ?? '-' }}</div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Tanggal Permintaan</label>
                    <div class="fw-semibold text-dark">{{ $permintaan->created_at->translatedFormat('d F Y') }}</div>
                </div>
            </div>

            <hr>

            <div class="row">
                <!-- Left Column: Dynamic Parameter Fields -->
                <div class="col-lg-4 border-end" style="max-height: 800px; overflow-y: auto; padding-right: 20px;">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-warning text-dark rounded-circle px-2 py-1 me-2 fw-bold small">1</span>
                        <h5 class="fw-bold mb-0 text-primary">Parameter Akta</h5>
                    </div>
                    <p class="text-muted small">Sesuaikan parameter di bawah ini jika ingin membangun ulang draft akta. Isi akta di sebelah kanan sudah memuat versi yang tersimpan sebelumnya.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary">Pilih Jenis Draft Akta</label>
                        <select id="select-jenis-draft" class="form-select form-select-sm border-primary">
                            <option value="pt">Pendirian PT / CV (30 Halaman)</option>
                            <option value="hibah">Akta Hibah (2 Halaman)</option>
                            <option value="ajb">Akta Jual Beli (2 Halaman)</option>
                            <option value="legalisasi">Legalisasi Dokumen (1 Halaman)</option>
                            <option value="default">Default / Akta Umum (2 Halaman)</option>
                        </select>
                        <small class="text-muted d-block mt-1">Menerapkan template ini akan MENGGANTI isi akta di sebelah kanan.</small>
                    </div>

                    <div id="dynamic-fields-container">
                        <!-- Dynamic fields will be rendered here by JS -->
                    </div>

                    <button type="button" id="btnApplyTemplate" class="btn btn-warning w-100 fw-bold mb-4 shadow-sm py-2">
                        <i class="fa fa-file-invoice me-1"></i> Terapkan ke Template Akta
                    </button>
                </div>

                <!-- Right Column: Standard Form -->
                <div class="col-lg-8 ps-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-primary text-white rounded-circle px-2 py-1 me-2 fw-bold small">2</span>
                        <h5 class="fw-bold mb-0 text-primary">Informasi & Isi Akta</h5>
                    </div>

                    <form method="POST" action="{{ route('admin.akta.update', $akta->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Nomor Akta</label>
                                    <input type="text" name="nomor_akta" class="form-control @error('nomor_akta') is-invalid @enderror" value="{{ old('nomor_akta', $akta->nomor_akta) }}" required>
                                    @error('nomor_akta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Nama Akta</label>
                                    <input type="text" name="nama_akta" class="form-control @error('nama_akta') is-invalid @enderror" value="{{ old('nama_akta', $akta->nama_akta) }}" required>
                                    @error('nama_akta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Tanggal Akta</label>
                            <input type="date" name="tanggal_akta" class="form-control @error('tanggal_akta') is-invalid @enderror" value="{{ old('tanggal_akta', $akta->tanggal_akta->format('Y-m-d')) }}" required>
                            @error('tanggal_akta')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Ganti File Akta Digital (Kosongkan agar PDF dibuat ulang otomatis dari isi di bawah)</label>
                            <input type="file" name="file_akta" class="form-control">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">Isi Akta</label>
                            <textarea id="editor" name="isi_akta" class="@error('isi_akta') is-invalid @enderror">{{ old('isi_akta', $akta->isi_akta) }}</textarea>
                            @error('isi_akta')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.akta.index') }}" class="btn btn-light border fw-semibold">
                                <i class="fa fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="fa fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    const layananName = "{{ $permintaan->layanan->nama_layanan ?? '' }}";

    // --- Templates sama persis kayak di create.blade.php ---
    window.templatePT = `...`; // salin persis dari create.blade.php
    window.templateHibah = `...`;
    window.templateAJB = `...`;
    window.templateLegalisasi = `...`;
    window.templateDefault = `...`;

    // --- renderFields() sama persis kayak di create.blade.php ---
    function renderFields() {
        /* salin persis dari create.blade.php */ }

    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'insertTable', '|', 'undo', 'redo']
        })
        .then(editor => {
            window.editor = editor;

            editor.editing.view.change(writer => {
                writer.setStyle('min-height', '500px', editor.editing.view.document.getRoot());
            });

            // Render field kiri, tapi JANGAN auto-compile ke editor
            // karena editor sudah terisi isi_akta yang tersimpan
            const selectDraft = document.getElementById('select-jenis-draft');
            const lowLayanan = layananName.toLowerCase();
            if (lowLayanan.includes('pt') || lowLayanan.includes('cv')) selectDraft.value = 'pt';
            else if (lowLayanan.includes('hibah')) selectDraft.value = 'hibah';
            else if (lowLayanan.includes('jual') || lowLayanan.includes('ajb')) selectDraft.value = 'ajb';
            else if (lowLayanan.includes('legalisasi')) selectDraft.value = 'legalisasi';
            else selectDraft.value = 'pt';

            renderFields();

            // compileDeed() sama persis kayak di create.blade.php
            function compileDeed() {
                /* salin persis dari create.blade.php */ }

            selectDraft.addEventListener('change', function() {
                renderFields();
            });

            // Tombol "Terapkan ke Template" baru mengganti isi editor,
            // TIDAK otomatis jalan saat halaman dibuka
            document.getElementById('btnApplyTemplate').addEventListener('click', compileDeed);
        })
        .catch(error => console.error(error));
</script>
@endsection