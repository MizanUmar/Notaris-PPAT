@extends('layouts.app')

@section('title', 'Layanan & Berkas Persyaratan')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Layanan & Persyaratan Dokumen</h2>
            <p class="text-muted mb-0">Kelola jenis layanan hukum, berkas persyaratan wajib, dan pengumuman layanan.</p>
        </div>
        <button class="btn btn-premium-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
            <i class="fa-solid fa-plus me-1"></i> Tambah Layanan Baru
        </button>
    </div>

    <!-- Services Grid/List -->
    <div class="row g-4">
        @forelse($layanan as $lay)
            <div class="col-12">
                <div class="card card-premium overflow-hidden">
                    <div class="card-header bg-dark text-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-0">
                        <div>
                            <span class="badge bg-primary text-white me-2">Estimasi: {{ $lay->estimasi_waktu }}</span>
                            @if($lay->status_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif
                            <h4 class="fw-bold font-heading mb-0 mt-2 text-white">{{ $lay->nama_layanan }}</h4>
                        </div>
                        <div class="btn-group gap-1">
                            <button class="btn btn-sm btn-light edit-layanan-btn"
                                    data-id="{{ $lay->id }}"
                                    data-nama_layanan="{{ $lay->nama_layanan }}"
                                    data-deskripsi="{{ $lay->deskripsi }}"
                                    data-estimasi_waktu="{{ $lay->estimasi_waktu }}"
                                    data-status_aktif="{{ $lay->status_aktif ? 1 : 0 }}">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Layanan
                            </button>
                            <form action="{{ route('admin.layanan.destroy', $lay->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini beserta seluruh berkas persyaratan dan info terkait?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 bg-white">
                        <p class="text-muted small border-bottom pb-3 mb-4">{{ $lay->deskripsi }}</p>
                        
                        <div class="row g-4">
                            <!-- Requirements (Persyaratan) -->
                            <div class="col-md-6 border-end">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold font-heading mb-0 text-dark"><i class="fa-solid fa-folder-open text-primary me-2"></i> Berkas Persyaratan Wajib</h6>
                                    <button class="btn btn-sm btn-outline-primary tambah-persyaratan-btn" data-layanan-id="{{ $lay->id }}">
                                        <i class="fa-solid fa-plus"></i> Tambah Berkas
                                    </button>
                                </div>
                                <ul class="list-group list-group-flush small">
                                    @forelse($lay->persyaratan as $req)
                                        <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-2">
                                            <div>
                                                <i class="fa-regular fa-circle-check text-success me-2"></i>
                                                <span class="fw-semibold">{{ $req->nama_dokumen }}</span>
                                                <span class="text-muted text-xs ms-1">({{ $req->keterangan ?? 'Fotokopi' }})</span>
                                            </div>
                                            <div class="btn-group gap-1">
                                                <button class="btn btn-sm btn-link text-warning p-0 edit-req-btn"
                                                        data-id="{{ $req->id }}"
                                                        data-nama_dokumen="{{ $req->nama_dokumen }}"
                                                        data-keterangan="{{ $req->keterangan }}">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form action="{{ route('admin.layanan.persyaratan.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted border-0 px-0">Belum ada berkas persyaratan.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <!-- News / Announcements (Informasi Layanan) -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold font-heading mb-0 text-dark"><i class="fa-solid fa-circle-info text-primary me-2"></i> Info / Pengumuman Layanan</h6>
                                    <button class="btn btn-sm btn-outline-primary tambah-info-btn" data-layanan-id="{{ $lay->id }}">
                                        <i class="fa-solid fa-plus"></i> Tambah Info
                                    </button>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @forelse($lay->informasi as $info)
                                        <div class="p-3 bg-light rounded-3 position-relative shadow-xs border-start border-3 border-info">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="fw-bold small text-info text-truncate d-inline-block" style="max-width: 70%;">{{ $info->judul }}</span>
                                                <small class="text-muted text-xs">{{ $info->tanggal->format('d M Y') }}</small>
                                            </div>
                                            <p class="text-muted mb-0 small" style="font-size: 0.825rem;">{{ $info->isi_informasi }}</p>
                                            <div class="position-absolute bottom-0 end-0 p-2">
                                                <button class="btn btn-sm btn-link text-warning p-0 edit-info-btn"
                                                        data-id="{{ $info->id }}"
                                                        data-judul="{{ $info->judul }}"
                                                        data-isi_informasi="{{ $info->isi_informasi }}"
                                                        data-tanggal="{{ $info->tanggal->toDateString() }}">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                                <form action="{{ route('admin.layanan.informasi.destroy', $info->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 ms-1"><i class="fa-solid fa-trash text-xs"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted small py-2 mb-0">Belum ada info khusus untuk layanan ini.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <i class="fa-solid fa-briefcase fs-1 mb-3"></i>
                <p>Belum ada jenis layanan terdaftar.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Layanan -->
<div class="modal fade" id="modalTambahLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.layanan.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-folder-plus me-1"></i> Tambah Layanan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Akta Jual Beli (AJB)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Layanan</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat layanan" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Estimasi Waktu Pengerjaan</label>
                        <input type="text" name="estimasi_waktu" class="form-control" placeholder="Contoh: 3 Hari Kerja" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Status Aktif</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Layanan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Layanan -->
<div class="modal fade" id="modalEditLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="editLayananForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-folder-open me-1"></i> Edit Layanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Layanan</label>
                        <input type="text" name="nama_layanan" id="edit_nama_layanan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Layanan</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Estimasi Waktu Pengerjaan</label>
                        <input type="text" name="estimasi_waktu" id="edit_estimasi_waktu" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Status Aktif</label>
                        <select name="status_aktif" id="edit_status_aktif" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
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

<!-- Modal Tambah Persyaratan -->
<div class="modal fade" id="modalTambahReq" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="addReqForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-plus me-1"></i> Tambah Berkas Persyaratan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Berkas / Dokumen</label>
                        <input type="text" name="nama_dokumen" class="form-control" placeholder="Contoh: KTP Penjual & Pembeli" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Scan Asli / Fotokopi 3 Lembar">
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Berkas</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Persyaratan -->
<div class="modal fade" id="modalEditReq" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="editReqForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-pen me-1"></i> Edit Berkas Persyaratan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Berkas / Dokumen</label>
                        <input type="text" name="nama_dokumen" id="edit_nama_dokumen" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Keterangan Tambahan</label>
                        <input type="text" name="keterangan" id="edit_req_keterangan" class="form-control">
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

<!-- Modal Tambah Info -->
<div class="modal fade" id="modalTambahInfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="addInfoForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-plus me-1"></i> Tambah Informasi Layanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Informasi</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Kebijakan Pajak AJB Terbaru 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Publikasi</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Isi Informasi / Pengumuman</label>
                        <textarea name="isi_informasi" class="form-control" rows="4" placeholder="Detail informasi..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Info</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Info -->
<div class="modal fade" id="modalEditInfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="editInfoForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-pen me-1"></i> Edit Informasi Layanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Informasi</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Publikasi</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Isi Informasi</label>
                        <textarea name="isi_informasi" id="edit_isi_informasi" class="form-control" rows="4" required></textarea>
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
    // Layanan Edit Populating
    document.querySelectorAll('.edit-layanan-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama_layanan');
            const deskripsi = this.getAttribute('data-deskripsi');
            const estimasi = this.getAttribute('data-estimasi_waktu');
            const status = this.getAttribute('data-status_aktif');

            document.getElementById('editLayananForm').setAttribute('action', `/admin/layanan/update/${id}`);
            document.getElementById('edit_nama_layanan').value = nama;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_estimasi_waktu').value = estimasi;
            document.getElementById('edit_status_aktif').value = status;

            new bootstrap.Modal(document.getElementById('modalEditLayanan')).show();
        });
    });

    // Add Requirement Dynamic Route Set
    document.querySelectorAll('.tambah-persyaratan-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-layanan-id');
            document.getElementById('addReqForm').setAttribute('action', `/admin/layanan/${id}/persyaratan/store`);
            new bootstrap.Modal(document.getElementById('modalTambahReq')).show();
        });
    });

    // Edit Requirement Populating
    document.querySelectorAll('.edit-req-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama_dokumen');
            const keterangan = this.getAttribute('data-keterangan');

            document.getElementById('editReqForm').setAttribute('action', `/admin/layanan/persyaratan/update/${id}`);
            document.getElementById('edit_nama_dokumen').value = nama;
            document.getElementById('edit_req_keterangan').value = keterangan;

            new bootstrap.Modal(document.getElementById('modalEditReq')).show();
        });
    });

    // Add Info Dynamic Route Set
    document.querySelectorAll('.tambah-info-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-layanan-id');
            document.getElementById('addInfoForm').setAttribute('action', `/admin/layanan/${id}/informasi/store`);
            new bootstrap.Modal(document.getElementById('modalTambahInfo')).show();
        });
    });

    // Edit Info Populating
    document.querySelectorAll('.edit-info-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const judul = this.getAttribute('data-judul');
            const isi = this.getAttribute('data-isi_informasi');
            const tanggal = this.getAttribute('data-tanggal');

            document.getElementById('editInfoForm').setAttribute('action', `/admin/layanan/informasi/update/${id}`);
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_isi_informasi').value = isi;
            document.getElementById('edit_tanggal').value = tanggal;

            new bootstrap.Modal(document.getElementById('modalEditInfo')).show();
        });
    });
</script>
@endsection
