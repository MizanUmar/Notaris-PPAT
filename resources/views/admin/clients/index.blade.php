@extends('layouts.app')

@section('title', 'Manajemen Client')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Manajemen Client</h2>
            <p class="text-muted mb-0">Kelola data login dan biodata lengkap client.</p>
        </div>
        <button class="btn btn-premium-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus me-1"></i> Tambah Client Baru
        </button>
    </div>

    <!-- Search Card -->
    <div class="card card-premium mb-4">
        <div class="card-body">
            <form action="{{ route('admin.clients.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari client berdasarkan Nama, NIK, Email, atau Alamat..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Client Table Card -->
    <div class="card card-premium">
        <div class="table-responsive p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Nama Lengkap</th>
                        <th>NIK</th>
                        <th>No. Telepon / WA</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold d-block text-capitalize">{{ $client->user->nama ?? '-' }}</span>
                                <small class="text-muted">Username: {{ $client->user->username ?? '-' }}</small>
                            </td>
                            <td><span class="font-monospace small">{{ $client->nik }}</span></td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->no_hp) }}" target="_blank" class="text-decoration-none">
                                    <i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $client->no_hp }}
                                </a>
                            </td>
                            <td><span class="small">{{ $client->email }}</span></td>
                            <td><span class="small text-truncate d-inline-block" style="max-width: 200px;" title="{{ $client->alamat }}">{{ $client->alamat }}</span></td>
                            <td class="pe-4 text-end">
                                <div class="btn-group gap-1">
                                    <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-sm btn-info text-white"><i class="fa-solid fa-eye"></i> Detail</a>
                                    
                                    <button class="btn btn-sm btn-warning text-dark edit-btn" 
                                            data-id="{{ $client->id }}"
                                            data-username="{{ $client->user->username ?? '' }}"
                                            data-nama="{{ $client->user->nama ?? '' }}"
                                            data-nik="{{ $client->nik }}"
                                            data-no_hp="{{ $client->no_hp }}"
                                            data-email="{{ $client->email }}"
                                            data-alamat="{{ $client->alamat }}">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data client ini? Semua data permintaan layanan dan dokumen terkait juga akan terhapus.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Data client tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($clients->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.clients.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-user-plus me-1"></i> Tambah Client Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Username Login</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Sesuai KTP" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">NIK (16 Digit)</label>
                            <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 0812345678" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="alamat@email.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Password Akun</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Alamat Rumah</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap client" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" id="editForm">
            @csrf
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold font-heading"><i class="fa-solid fa-user-pen me-1"></i> Edit Data Client</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Username Login</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">NIK (16 Digit)</label>
                            <input type="text" name="nik" id="edit_nik" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" id="edit_no_hp" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Password Akun (Kosongkan jika tidak diganti)</label>
                            <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Alamat Rumah</label>
                        <textarea name="alamat" id="edit_alamat" class="form-control" rows="3" required></textarea>
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
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const nama = this.getAttribute('data-nama');
            const nik = this.getAttribute('data-nik');
            const no_hp = this.getAttribute('data-no_hp');
            const email = this.getAttribute('data-email');
            const alamat = this.getAttribute('data-alamat');

            document.getElementById('editForm').setAttribute('action', `/admin/clients/update/${id}`);
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_nik').value = nik;
            document.getElementById('edit_no_hp').value = no_hp;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_alamat').value = alamat;

            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        });
    });
</script>
@endsection
