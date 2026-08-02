<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul halaman dinamis dari child view -->
    <title>@yield('title') - Notaris & PPAT Eka Sulistya</title>

    <!-- Google Fonts: Inter untuk tampilan tipografi modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS untuk framework layouting dan komponen responsif -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 untuk pustaka icon premium -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Stylesheet FullCalendar untuk halaman agenda penjadwalan jika ada -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">

    <style>
        /* ==========================================
           1. STYLING GLOBAL & BODY
           ========================================== */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* Latar belakang abu-abu terang */
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ==========================================
           2. SIDEBAR STYLE (Navigasi Samping)
           ========================================== */
        .sidebar {
            width: 260px;
            /* Latar belakang gradasi merah maroon gelap/mewah */
            background: linear-gradient(180deg, #4d0011 0%, #2e0108 100%);
            color: #f8fafc;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
        }

        /* Bagian Brand/Logo di atas sidebar */
        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Menu List di sidebar */
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }

        .sidebar-menu li {
            padding: 0.2rem 1rem;
        }

        /* Link Navigasi Menu */
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: #e2e8f0;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.925rem;
            transition: all 0.2s ease-in-out;
        }

        /* Hover Link Navigasi */
        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        /* Menu Navigasi Aktif (Sedang Dibuka) */
        .sidebar-menu li.active a {
            background: linear-gradient(90deg, #800020 0%, #b01c38 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(128, 0, 32, 0.3);
        }

        /* Submenu Dropdown Sidebar */
        .sidebar-submenu {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .sidebar-submenu li {
            padding: 0;
        }

        .sidebar-submenu a {
            padding: 0.65rem 1rem 0.65rem 3rem;
            font-size: 0.88rem;
            color: #cbd5e1;
        }

        .sidebar-submenu a:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-submenu li.active a {
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }

        /* Animasi rotasi panah dropdown */
        .rotate {
            transition: .3s;
        }

        a[aria-expanded="true"] .rotate {
            transform: rotate(90deg);
        }

        /* ==========================================
           3. MAIN CONTENT & NAVBAR
           ========================================== */
        /* Kontainer Utama Konten di sebelah kanan sidebar */
        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Navbar Kustom */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            margin-left: 260px;
        }

        /* ==========================================
           4. PREMIUM STYLING (Cards, Buttons & Badges)
           ========================================== */
        /* Kartu Tampilan Modern Premium */
        .card-premium {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-premium:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }

        /* Tombol Premium dengan gradasi maroon mewah */
        .btn-premium-primary {
            background: linear-gradient(135deg, #800020 0%, #5a0015 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            box-shadow: 0 4px 6px -1px rgba(128, 0, 32, 0.2);
            transition: all 0.2s ease;
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #b01c38 0%, #800020 100%);
            color: #ffffff;
            box-shadow: 0 6px 8px -1px rgba(128, 0, 32, 0.3);
        }

        /* Badge Status Berkas Layanan */
        .badge-waiting {
            background-color: #fef3c7; /* Kuning */
            color: #d97706;
        }

        .badge-process {
            background-color: #fff0f2; /* Merah muda */
            color: #800020;
        }

        .badge-success {
            background-color: #dcfce7; /* Hijau */
            color: #15803d;
        }

        .badge-danger {
            background-color: #fee2e2; /* Merah */
            color: #b91c1c;
        }

        /* ==========================================
           5. OVERRIDE WARNA BOOTSTRAP KE MAROON
           ========================================== */
        .text-primary {
            color: #800020 !important;
        }

        .bg-primary {
            background-color: #800020 !important;
        }

        .btn-primary {
            background-color: #800020 !important;
            border-color: #800020 !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #5a0015 !important;
            border-color: #5a0015 !important;
        }

        .btn-outline-primary {
            color: #800020 !important;
            border-color: #800020 !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            background-color: #800020 !important;
            border-color: #800020 !important;
            color: #ffffff !important;
        }

        .border-primary {
            border-color: #800020 !important;
        }

        .bg-primary-subtle {
            background-color: #fff0f2 !important;
            color: #800020 !important;
        }

        .sidebar-brand i.text-primary {
            color: #ffccd4 !important;
        }

        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
        }   

        /* ==========================================
           6. RESPONSIVENESS & MEDIA QUERIES
           ========================================== */
        /* Menyembunyikan sidebar di perangkat seluler / tablet */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }

            /* Memunculkan sidebar ketika kelas 'active' ditambahkan */
            .sidebar.active {
                margin-left: 0;
            }

            /* Konten utama bergeser penuh jika sidebar tersembunyi */
            .main-content,
            .navbar-custom {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- ========================================================
         BAGIAN SIDEBAR (NAVIGASI UTAMA SAMPING)
         ======================================================== -->
    <div class="sidebar" id="sidebar">
        <!-- Logo Brand & Nama Kantor -->
        <div class="sidebar-brand">
            <i class="fa-solid fa-scale-balanced text-primary fs-4"></i>
            <div>
                <span class="d-block fw-bold text-white lh-sm fs-6">EKA SULISTYA</span>
                <small class="text-white d-block" style="font-size: 0.7rem;">Notaris & PPAT</small>
            </div>
        </div>

        <!-- Daftar Menu Navigasi -->
        <ul class="sidebar-menu">
            
            <!-- MENU UNTUK ADMIN DAN NOTARIS -->
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'notaris')
            <!-- Menu Dashboard -->
            <li class="{{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Menu Pengelolaan Data Client -->
            <li class="{{ Request::routeIs('admin.clients.*') ? 'active' : '' }}">
                <a href="{{ route('admin.clients.index') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Data Client</span>
                </a>
            </li>
            <!-- Menu Permintaan Layanan Hukum -->
            <li class="{{ Request::routeIs('admin.permintaan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.permintaan.index') }}">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Permintaan Layanan</span>
                </a>
            </li>
            
            <!-- Dropdown Menu Akta -->
            <li>
                <a class="d-flex justify-content-between align-items-center
        {{ request()->routeIs('admin.permintaan-akta*') || request()->routeIs('admin.akta.*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse"
                    href="#menuAkta"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.permintaan-akta*') || request()->routeIs('admin.akta.*') ? 'true' : 'false' }}"
                    aria-controls="menuAkta">
                    <span>
                        <i class="fa-solid fa-file-signature"></i>
                        Akta
                    </span>
                    <i class="fa-solid fa-chevron-right rotate"></i>
                </a>

                <!-- Submenu Dropdown Akta -->
                <div class="collapse {{ request()->routeIs('admin.permintaan-akta*') || request()->routeIs('admin.akta.*') ? 'show' : '' }}"
                    id="menuAkta">
                    <ul class="sidebar-submenu">
                        <!-- Submenu Permintaan Akta -->
                        <li class="{{ request()->routeIs('admin.permintaan-akta*') ? 'active' : '' }}">
                            <a href="{{ route('admin.permintaan-akta') }}">
                                <i class="fa-regular fa-circle-dot me-2"></i>
                                Permintaan Akta
                            </a>
                        </li>
                        <!-- Submenu Arsip Akta Diterbitkan -->
                        <li class="{{ request()->routeIs('admin.akta.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.akta.index') }}">
                                <i class="fa-regular fa-folder-open me-2"></i>
                                Arsip Akta
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Dropdown Menu Surat -->
            <li>
                <a class="d-flex justify-content-between align-items-center
        {{ request()->routeIs('admin.permintaan-surat*') || request()->routeIs('admin.surat.*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse"
                    href="#menuSurat"
                    role="button"
                    aria-expanded="{{ request()->routeIs('admin.permintaan-surat*') || request()->routeIs('admin.surat.*') ? 'true' : 'false' }}"
                    aria-controls="menuSurat">
                    <span>
                        <i class="fa-solid fa-envelope-open-text"></i>
                        Surat
                    </span>
                    <i class="fa-solid fa-chevron-right rotate"></i>
                </a>

                <!-- Submenu Dropdown Surat -->
                <div class="collapse {{ request()->routeIs('admin.permintaan-surat*') || request()->routeIs('admin.surat.*') ? 'show' : '' }}"
                    id="menuSurat">
                    <ul class="sidebar-submenu">
                        <!-- Submenu Permintaan Pembuatan Surat -->
                        <li class="{{ request()->routeIs('admin.permintaan-surat*') ? 'active' : '' }}">
                            <a href="{{ route('admin.permintaan-surat') }}">
                                <i class="fa-regular fa-circle-dot me-2"></i>
                                Permintaan Surat
                            </a>
                        </li>
                        <!-- Submenu Arsip Surat Diterbitkan -->
                        <li class="{{ request()->routeIs('admin.surat.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.surat.index') }}">
                                <i class="fa-regular fa-folder-open me-2"></i>
                                Arsip Surat
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Menu Master Layanan & Persyaratan Berkas -->
            <li class="{{ Request::routeIs('admin.layanan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.layanan.index') }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Layanan & Berkas</span>
                </a>
            </li>
            <!-- Menu Monitoring Buku Tamu Kantor -->
            <li class="{{ Request::routeIs('admin.buku-tamu.*') ? 'active' : '' }}">
                <a href="{{ route('admin.buku-tamu.index') }}">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Buku Tamu</span>
                </a>
            </li>
            <!-- Menu Pengaturan Profil Notaris -->
            <li class="{{ Request::routeIs('admin.profil.edit') ? 'active' : '' }}">
                <a href="{{ route('admin.profil.edit') }}">
                    <i class="fa-solid fa-landmark"></i>
                    <span>Profil Kantor</span>
                </a>
            </li>

            <!-- MENU UNTUK CLIENT / PEMOHON LAYANAN -->
            @elseif(Auth::user()->role === 'client')
            <!-- Menu Dashboard Client -->
            <li class="{{ Request::routeIs('client.dashboard') ? 'active' : '' }}">
                <a href="{{ route('client.dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Menu Pengajuan Permintaan Layanan Client -->
            <li class="{{ Request::routeIs('client.permintaan.*') ? 'active' : '' }}">
                <a href="{{ route('client.permintaan.index') }}">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Layanan Saya</span>
                </a>
            </li>
            <!-- Menu Akses Arsip Akta Mandiri Client -->
            <li class="{{ Request::routeIs('client.akta.*') ? 'active' : '' }}">
                <a href="{{ route('client.akta.index') }}">
                    <i class="fa-solid fa-file-contract"></i>
                    <span>Akta Saya</span>
                </a>
            </li>
            <!-- Menu Akses Arsip Surat Mandiri Client -->
            <li class="{{ Request::routeIs('client.surat.*') ? 'active' : '' }}">
                <a href="{{ route('client.surat.index') }}">
                    <i class="fa-solid fa-envelope-open-text"></i>
                    <span>Surat Saya</span>
                </a>
            </li>
            <!-- Menu Melihat Daftar Persyaratan Berkas Dokumen -->
            <li class="{{ Request::routeIs('client.persyaratan.*') ? 'active' : '' }}">
                <a href="{{ route('client.persyaratan.index') }}">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Persyaratan Berkas</span>
                </a>
            </li>
            <!-- Menu Pendaftaran Buku Tamu Digital saat berkunjung -->
            <li class="{{ Request::routeIs('buku-tamu.checkin') ? 'active' : '' }}">
                <a href="{{ route('buku-tamu.checkin') }}">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <span>Isi Buku Tamu</span>
                </a>
            </li>
            <!-- Menu Edit Profil/Biodata Pribadi Client -->
            <li class="{{ Request::routeIs('client.biodata.edit') ? 'active' : '' }}">
                <a href="{{ route('client.biodata.edit') }}">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Biodata Saya</span>
                </a>
            </li>
            @endif

            <!-- TOMBOL KELUAR APLIKASI (LOGOUT) -->
            <li class="mt-4">
                <a href="{{ route('logout') }}" class="text-danger-emphasis">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                    <span class="text-danger">Keluar</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- ========================================================
         BAGIAN NAVBAR ATAS (HEADER PROFIL)
         ======================================================== -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid p-0">
            <!-- Tombol hamburger menu samping untuk responsif mobile -->
            <button class="btn btn-outline-secondary d-lg-none me-2" id="sidebar-toggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Profil dan Nama Pengguna yang sedang masuk -->
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="fw-bold d-block text-capitalize">{{ Auth::user()->nama }}</span>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->username }}</small>
                </div>
                <!-- Avatar inisial huruf nama depan user -->
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
            </div>
        </div>
    </nav>

    <!-- ========================================================
         AREA KONTEN UTAMA & FOOTER
         ======================================================== -->
    <div class="main-content">
        <!-- Toast / Alert Flash Message Sukses -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Toast / Alert Flash Message Error Form Validasi -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Menampilkan konten blade dinamis dari sub-halaman -->
        @yield('content')

        <!-- Bagian Footer Halaman Utama -->
        <footer class="mt-5 pt-4 border-top border-light text-center text-muted" style="font-size: 0.85rem;">
            <span>&copy; {{ date('Y') }} Notaris & PPAT Eka Sulistya. All rights reserved.</span>
        </footer>
    </div>

    <!-- ========================================================
         BAGIAN LIBRARY JS / SCRIPTS PENDUKUNG
         ======================================================== -->
    <!-- Script FullCalendar untuk antarmuka kalender -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- Bootstrap Bundle JS (Popovers, Modals, Tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logika Javascript untuk Toggle Sidebar pada Layar HP/Tablet
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    <!-- Script tambahan dari sub-view halaman -->
    @yield('scripts')
</body>

</html>