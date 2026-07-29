@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Beranda Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang kembali, {{ Auth::user()->nama }}!</p>
        </div>
        <div class="text-muted small">
            <i class="fa-regular fa-calendar me-1"></i> Hari ini: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-premium p-4 h-100 stat-accent accent-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold h-100 d-block text-uppercase mb-1">Total Client</span>
                        <h3 class="fw-bold mb-0">{{ $totalClients }}</h3>
                    </div>
                    <div class="icon-circle bg-primary-subtle text-primary mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-premium p-4 h-100 stat-accent accent-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold h-100 d-block text-uppercase mb-1">Menunggu Proses</span>
                        <h3 class="fw-bold text-warning mb-0">{{ $pendingPermintaan }}</h3>
                    </div>
                    <div class="icon-circle bg-warning-subtle text-warning mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-premium p-4 h-100 stat-accent accent-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold h-100 d-block text-uppercase mb-1">Total Permintaan</span>
                        <h3 class="fw-bold text-info mb-0">{{ $totalPermintaan }}</h3>
                    </div>
                    <div class="icon-circle bg-info-subtle text-info mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-file-invoice fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-premium p-4 h-100 stat-accent accent-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold h-100 d-block text-uppercase mb-1">Akta Diarsipkan</span>
                        <h3 class="fw-bold text-success mb-0">{{ $totalAkta }}</h3>
                    </div>
                    <div class="icon-circle bg-success-subtle text-success mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-file-shield fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-premium p-4 h-100 stat-accent accent-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold h-100 d-block text-uppercase mb-1">Surat Diarsipkan</span>
                        <h3 class="fw-bold text-info mb-0">{{ $totalSurat }}</h3>
                    </div>
                    <div class="icon-circle bg-info-subtle text-info mb-0" style="width: 50px; height: 50px; border-radius: 10px;">
                        <i class="fa-solid fa-file-lines fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Requests -->
        <div class="col-lg-8">
            <div class="card card-premium mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-bell-concierge text-primary me-2"></i> Permintaan Layanan Terbaru</h5>
                    <a href="{{ route('admin.permintaan.index') }}" class="btn btn-sm btn-link text-decoration-none">Semua Permintaan</a>
                </div>
                <div class="table-responsive px-4 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Client</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPermintaan as $req)
                            <tr>
                                <td>
                                    <span class="fw-semibold d-block text-capitalize">{{ $req->client->user->nama }}</span>
                                    <small class="text-muted">{{ $req->client->nik }}</small>
                                </td>
                                <td>
                                    <span class="fw-medium text-primary">{{ $req->layanan->nama_layanan }}</span>
                                </td>
                                <td>
                                    <span class="small">{{ $req->tanggal_permintaan->translatedFormat('d M Y') }}</span>
                                </td>
                                <td>
                                    @if($req->status === 'Menunggu')
                                    <span class="badge badge-waiting">Menunggu</span>
                                    @elseif($req->status === 'Diproses')
                                    <span class="badge badge-process">Diproses</span>
                                    @elseif($req->status === 'Selesai')
                                    <span class="badge badge-success">Selesai</span>
                                    @else
                                    <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.permintaan.show', $req->id) }}" class="btn btn-sm btn-light border" title="Kelola"><i class="fa-solid fa-gear"></i> Kelola</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada permintaan layanan terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Guestbook -->
            <div class="card card-premium">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-address-book text-primary me-2"></i> Kunjungan Buku Tamu Terbaru</h5>
                    <a href="{{ route('admin.buku-tamu.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                </div>
                <div class="table-responsive px-4 pb-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Nama Tamu</th>
                                <th>Instansi</th>
                                <th>Keperluan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBukuTamu as $tamu)
                            <tr>
                                <td>
                                    <span class="fw-semibold d-block text-capitalize">{{ $tamu->nama_tamu }}</span>
                                    <small class="text-muted">{{ $tamu->nomor_hp }}</small>
                                </td>
                                <td><span class="small">{{ $tamu->instansi ?? '-' }}</span></td>
                                <td><span class="small text-truncate d-inline-block" style="max-width: 250px;">{{ $tamu->keperluan }}</span></td>
                                <td><span class="small">{{ $tamu->tanggal_kunjungan->translatedFormat('d M Y') }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada kunjungan tamu hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Agenda & Calendar (Right sidebar layout) -->
        <div class="col-lg-4">
            <!-- Calendar -->
            <div class="card card-premium mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold font-heading mb-0">
                        <i class="fa-solid fa-calendar-days text-primary me-2"></i>
                        Kalender & Pengingat
                    </h5>

                    <button
                        class="btn btn-primary btn-sm rounded-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalReminder">

                        <i class="fa-solid fa-plus me-1"></i>
                        Tambah
                    </button>
                </div>

                <div class="card-body">

                    <div id="calendar"></div>

                </div>
            </div>

            <!-- Agenda Card -->
            <div class="card card-premium">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold font-heading mb-0"><i class="fa-solid fa-calendar-check text-primary me-2"></i> Agenda Mendatang</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-3">
                        @forelse($agenda as $ag)
                        <div class="d-flex gap-3 p-3 rounded-3 bg-light border-start border-primary border-3">
                            <div class="text-center bg-white p-2 rounded shadow-sm" style="min-width: 55px; height: fit-content;">
                                <span class="d-block fw-bold fs-5 text-primary lh-sm">{{ $ag->updated_at->format('d') }}</span>
                                <span class="text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase;">{{ $ag->updated_at->translatedFormat('M') }}</span>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-truncate mb-1">{{ $ag->layanan->nama_layanan }}</h6>
                                <span class="d-block small text-muted text-capitalize mb-1"><i class="fa-regular fa-user me-1"></i> {{ $ag->client->user->nama }}</span>
                                <span class="badge badge-process text-xs py-1">Sedang Diproses</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-clipboard-check fs-2 text-black-50 mb-2"></i>
                            <p class="small mb-0">Tidak ada agenda pengerjaan aktif saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #calendar {

        min-height: 420px;

    }

    .fc .fc-toolbar-title {

        font-size: 18px;

        font-weight: 600;

    }

    .fc-daygrid-day {

        cursor: pointer;

    }

    .fc-event {

        border-radius: 8px;

        padding: 2px;

        font-size: 12px;

    }

    .stat-accent {
        border: 1px solid #e2e8f0 !important;
        border-left-width: 4px !important;
    }

    .stat-accent.accent-primary {
        border-left-color: #800020 !important;
    }

    .stat-accent.accent-warning {
        border-left-color: #f59e0b !important;
    }

    .stat-accent.accent-info {
        border-left-color: #06b6d4 !important;
    }

    .stat-accent.accent-success {
        border-left-color: #16a34a !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {

            initialView: 'dayGridMonth',

            locale: 'id',

            selectable: true,

            height: 550,

            events: "{{ route('reminder.events') }}",

            dateClick: function(info) {

                fetch('/admin/reminder/by-date/' + info.dateStr)
                    .then(response => response.json())
                    .then(data => {

                        const form = document.getElementById('reminderForm');

                        form.reset();

                        if (data) {

                            // MODE EDIT
                            document.getElementById('modalTitle').innerHTML = "Edit Reminder";

                            document.getElementById('judul').value = data.judul;

                            document.getElementById('tanggal').value = data.tanggal;

                            document.getElementById('catatan').value = data.catatan ?? "";

                            form.action = "/admin/reminder/" + data.id;

                            if (document.getElementById('_method')) {
                                document.getElementById('_method').value = "PUT";
                            }

                            document.getElementById('btnSave').innerHTML = "Update";

                            document.getElementById('btnDelete').classList.remove('d-none');

                            document.getElementById('btnDelete').dataset.id = data.id;

                        } else {

                            // MODE TAMBAH
                            document.getElementById('modalTitle').innerHTML = "Tambah Reminder";

                            document.getElementById('tanggal').value = info.dateStr;

                            form.action = "/admin/reminder";

                            if (document.getElementById('_method')) {
                                document.getElementById('_method').value = "POST";
                            }

                            document.getElementById('btnSave').innerHTML = "Simpan";

                            document.getElementById('btnDelete').classList.add('d-none');

                        }

                        new bootstrap.Modal(document.getElementById('modalReminder')).show();

                    });

            }

        });

        calendar.render();

    });

    document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            // ...konfigurasi kalender kamu tetap sama...
        });

        calendar.render();

        // ⬇️ pindahkan ke sini, di dalam DOMContentLoaded
        document.getElementById('btnDelete').addEventListener('click', function() {
            if (confirm('Hapus reminder ini?')) {
                let form = document.getElementById('reminderForm');
                form.action = '/admin/reminder/' + this.dataset.id;
                document.getElementById('_method').value = 'DELETE';
                form.submit();
            }
        });

    });
</script>

<div class="modal fade" id="modalReminder">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="reminderForm" action="{{ route('reminder.store') }}" method="POST">

                @csrf

                <input type="hidden" name="_method" id="_method" value="POST">

                <input type="hidden" id="reminderId">

                <div class="modal-header">

                    <h5 id="modalTitle">Tambah Reminder</h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Judul</label>

                        <input
                            type="text"
                            class="form-control"
                            id="judul"
                            name="judul"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Tanggal</label>

                        <input
                            type="date"
                            class="form-control"
                            name="tanggal"
                            id="tanggal"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>Catatan</label>

                        <textarea
                            class="form-control"
                            name="catatan"
                            id="catatan"
                            rows="4"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        type="button">

                        Batal

                    </button>

                    <button
                        class="btn btn-primary"
                        id="btnSave">

                        Simpan

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger d-none"
                        id="btnDelete">

                        Hapus

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection