@extends('layouts.app')

@section('title', 'Arsip Akta')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Bagian Halaman Arsip Akta -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Arsip Akta Digital</h2>
            <p class="text-muted mb-0">Kelola dan telusuri arsip akta resmi yang diterbitkan.</p>
        </div>
    </div>

    <!-- ========================================================
         KARTU PENCARIAN & FILTER DATA
         ======================================================== -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <!-- Form Pencarian Data Akta -->
            <form action="{{ route('admin.akta.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <!-- Icon Pencarian -->
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <!-- Input Keyword Pencarian -->
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari akta berdasarkan Nomor Akta, Nama Akta, atau Nama Client..." value="{{ $search }}">
                    </div>
                </div>
                <!-- Tombol Submit Cari -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================
         TABEL DATA ARSIP AKTA
         ======================================================== -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Akta</th>
                        <th>Nama Akta</th>
                        <th>Tanggal Terbit</th>
                        <th>Pemohon (Client)</th>
                        <th>Berkas Digital</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Perulangan Data Akta -->
                    @forelse($akta as $akt)
                    <tr>
                        <!-- Nomor Akta -->
                        <td class="ps-4"><span class="fw-bold font-monospace text-dark">{{ $akt->nomor_akta }}</span></td>
                        <!-- Nama Akta -->
                        <td><span class="fw-semibold">{{ $akt->nama_akta }}</span></td>
                        <!-- Tanggal Terbit Akta (Format Indonesia) -->
                        <td><span class="small">{{ $akt->tanggal_akta->translatedFormat('d F Y') }}</span></td>
                        <!-- Nama Client Pemohon -->
                        <td>
                            <span class="fw-medium text-capitalize d-block">{{ $akt->permintaan->client->user->nama ?? '-' }}</span>
                            <small class="text-muted">Layanan: {{ $akt->permintaan->layanan->nama_layanan ?? '-' }}</small>
                        </td>
                        <!-- Tombol Berkas Digital (Lihat & Unduh PDF) -->
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Tombol Preview Berkas -->
                                <a href="{{ route('admin.akta.preview', $akt->id) }}" class="btn btn-sm btn-outline-info py-1 px-2 rounded">
                                    <i class="fa-solid fa-eye me-1"></i> Lihat
                                </a>
                                <!-- Tombol Unduh Berkas -->
                                <a href="{{ asset('storage/' . $akt->file_akta) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 rounded">
                                    <i class="fa-solid fa-file-pdf me-1"></i> Unduh
                                </a>
                            </div>
                        </td>
                        <!-- Tombol Aksi Pengelolaan (Edit & Hapus) -->
                        <td class="pe-4 text-end">
                            <div class="btn-group gap-1">
                                <!-- Tombol Triger Edit Data Akta (Memunculkan Modal Edit via JS) -->
                                <button type="button" class="btn btn-sm btn-warning text-dark edit-btn"
                                    data-id="{{ $akt->id }}"
                                    data-nomor_akta="{{ $akt->nomor_akta }}"
                                    data-nama_akta="{{ $akt->nama_akta }}"
                                    data-tanggal_akta="{{ $akt->tanggal_akta->format('Y-m-d') }}"
                                    data-isi_akta="{{ $akt->isi_akta }}">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>

                                <!-- Form Hapus Data Akta -->
                                <form action="{{ route('admin.akta.destroy', $akt->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip akta ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <!-- Tampilan Jika Data Akta Kosong -->
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Arsip akta tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bagian Pagination Tabel -->
        @if($akta->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $akta->links() }}
        </div>
        @endif
    </div>
</div>


<!-- ========================================================
     MODAL POPUP EDIT DATA AKTA
     ======================================================== -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="" method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <!-- Header Modal -->
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-file-pen me-1"></i> Edit Data Akta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Body Modal Form -->
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Kolom Nomor Akta -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor Akta</label>
                            <input type="text" name="nomor_akta" id="edit_nomor_akta" class="form-control" required>
                        </div>
                        <!-- Kolom Nama Akta -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nama Akta</label>
                            <input type="text" name="nama_akta" id="edit_nama_akta" class="form-control" required>
                        </div>
                    </div>
                    <!-- Kolom Tanggal Pembuatan -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Pembuatan</label>
                        <input type="date" name="tanggal_akta" id="edit_tanggal_akta" class="form-control" required>
                    </div>

                    <!-- Kolom Editor Isi Akta (Rich Text Editor CKEditor) -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Isi Akta</label>
                        <textarea id="edit_editor" name="isi_akta"></textarea>
                    </div>

                    <!-- Upload File Akta Manual -->
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Ganti File Akta Digital (Kosongkan jika tidak diganti — biarkan kosong agar PDF otomatis dibuat ulang dari Isi Akta di atas)</label>
                        <input type="file" name="file_akta" class="form-control">
                    </div>
                </div>
                <!-- Footer Modal -->
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Memuat Pustaka CKEditor 5 dari CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let editEditorInstance = null;

    // Fungsi Inisialisasi Rich Text Editor
    function initEditEditor(content) {
        // Jika instance editor lama masih aktif, hancurkan terlebih dahulu
        if (editEditorInstance) {
            editEditorInstance.destroy().then(() => {
                editEditorInstance = null;
                createEditEditor(content);
            });
        } else {
            createEditEditor(content);
        }
    }

    // Fungsi Membuat Instance CKEditor Baru
    function createEditEditor(content) {
        ClassicEditor
            .create(document.querySelector('#edit_editor'), {
                toolbar: [
                    'heading', '|', 'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .then(editor => {
                editEditorInstance = editor;
                editor.setData(content || '');
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => console.error(error));
    }

    // Logika Pengisian Form Modal saat Tombol Edit Diklik
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nomor_akta = this.getAttribute('data-nomor_akta');
            const nama_akta = this.getAttribute('data-nama_akta');
            const tanggal_akta = this.getAttribute('data-tanggal_akta');
            const isi_akta = this.getAttribute('data-isi_akta');

            // Set action URL form update secara dinamis
            document.getElementById('editForm').setAttribute('action', `/admin/akta/update/${id}`);
            // Isi nilai input form
            document.getElementById('edit_nomor_akta').value = nomor_akta;
            document.getElementById('edit_nama_akta').value = nama_akta;
            document.getElementById('edit_tanggal_akta').value = tanggal_akta;

            // Inisialisasi CKEditor dengan isi akta
            initEditEditor(isi_akta);

            // Munculkan Modal Popup Edit
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        });
    });

    // Sinkronkan isi CKEditor ke textarea sebelum form disubmit
    document.getElementById('editForm').addEventListener('submit', function() {
        if (editEditorInstance) {
            document.querySelector('#edit_editor').value = editEditorInstance.getData();
        }
    });
</script>
@endsection