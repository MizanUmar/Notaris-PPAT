@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Arsip Surat Resmi</h2>
            <p class="text-muted mb-0">Kelola dan arsipkan surat-surat keterangan pendukung layanan.</p>
        </div>
        <div class="btn-group gap-2">
            <a href="{{ route('admin.permintaan-surat') }}" class="btn btn-outline-primary fw-semibold">
                <i class="fa-solid fa-file-signature me-1"></i> Permintaan Surat
            </a>
            <button class="btn btn-premium-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa-solid fa-plus me-1"></i> Unggah Surat Baru
            </button>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('admin.surat.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari surat berdasarkan Nomor Surat, Jenis Surat, atau Nama Client..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Surat Table Card -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">No. Surat</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Client Terkait</th>
                        <th>Keterangan</th>
                        <th>File Digital</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surat as $sur)
                        <tr>
                            <td class="ps-4"><span class="fw-bold font-monospace text-dark">{{ $sur->nomor_surat }}</span></td>
                            <td><span class="fw-semibold text-primary">{{ $sur->jenis_surat }}</span></td>
                            <td><span class="small">{{ $sur->tanggal_surat->translatedFormat('d F Y') }}</span></td>
                            <td>
                                <span class="fw-medium text-capitalize d-block">{{ $sur->permintaan->client->user->nama ?? '-' }}</span>
                                <small class="text-muted">Layanan: {{ $sur->permintaan->layanan->nama_layanan ?? '-' }}</small>
                            </td>
                            <td><span class="small text-muted">{{ Str::limit($sur->keterangan, 50) ?? '-' }}</span></td>
                            <td>
                                <a href="{{ asset('storage/' . $sur->file_surat) }}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2 rounded">
                                    <i class="fa-solid fa-file-pdf me-1"></i> Unduh Surat
                                </a>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group gap-1">
                                    <button class="btn btn-sm btn-warning text-dark edit-btn" 
                                            data-id="{{ $sur->id }}"
                                            data-nomor_surat="{{ $sur->nomor_surat }}"
                                            data-jenis_surat="{{ $sur->jenis_surat }}"
                                            data-tanggal_surat="{{ $sur->tanggal_surat->toDateString() }}"
                                            data-keterangan="{{ $sur->keterangan }}">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.surat.destroy', $sur->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip surat ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">Arsip surat tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($surat->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $surat->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-envelope-open-text me-1"></i> Unggah Surat Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Link Permintaan Layanan</label>
                        <select name="permintaan_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Layanan Client</option>
                            @foreach($requests as $req)
                                <option value="{{ $req->id }}" {{ request('permintaan_id') == $req->id ? 'selected' : '' }}>
                                    #{{ $req->id }} - {{ $req->client->user->nama }} ({{ $req->layanan->nama_layanan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control" placeholder="Contoh: 120/SK/NOT/2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis / Judul Surat</label>
                        <input type="text" name="jenis_surat" class="form-control" placeholder="Contoh: Surat Kuasa, Surat Keterangan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan / Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan isi surat"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">File Surat Digital (PDF/Word/JPG max 10MB)</label>
                        <input type="file" name="file_surat" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Surat</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-file-pen me-1"></i> Edit Data Surat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="edit_nomor_surat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Jenis / Judul Surat</label>
                        <input type="text" name="jenis_surat" id="edit_jenis_surat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="edit_tanggal_surat" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Catatan / Keterangan</label>
                        <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Ganti File Surat Digital (Kosongkan jika tidak diganti)</label>
                        <input type="file" name="file_surat" class="form-control">
                    </div>
                </div>
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
<script>
    // Trigger modal tambah if permintaan_id is in query
    @if(request('permintaan_id'))
        const modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
        modalTambah.show();
    @endif

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nomor_surat = this.getAttribute('data-nomor_surat');
            const jenis_surat = this.getAttribute('data-jenis_surat');
            const tanggal_surat = this.getAttribute('data-tanggal_surat');
            const keterangan = this.getAttribute('data-keterangan');

            document.getElementById('editForm').setAttribute('action', `/admin/surat/update/${id}`);
            document.getElementById('edit_nomor_surat').value = nomor_surat;
            document.getElementById('edit_jenis_surat').value = jenis_surat;
            document.getElementById('edit_tanggal_surat').value = tanggal_surat;
            document.getElementById('edit_keterangan').value = keterangan;

            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        });
    });
</script>
@endsection
