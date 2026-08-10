<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil->nama_kantor }} - Pelayanan & Manajemen Data Client</title>

    <!-- Google Fonts: Inter untuk teks biasa, Outfit untuk tajuk/heading modern -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS untuk framework CSS responsif -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 untuk pustaka icon premium -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        /* ==========================================
           1. STYLING GLOBAL & FONT
           ========================================== */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* ==========================================
           2. NAVIGATION BAR STYLE (Navbar)
           ========================================== */
        .navbar-premium {
            background: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 0;
        }

        .navbar-premium .navbar-brand {
            color: #800020 !important;
            font-weight: 700;
        }

        .navbar-premium .navbar-brand i {
            color: #800020;
        }

        .navbar-premium .nav-link {
            color: #800020 !important;
            font-weight: 600;
            transition: .3s;
        }

        .navbar-premium .nav-link:hover {
            color: #a00028 !important;
        }

        .navbar-premium .nav-link.active {
            color: #800020 !important;
            border-bottom: 2px solid #800020;
        }

        /* Toggler untuk menu seluler */
        .navbar-toggler {
            border-color: #800020;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* ==========================================
           3. HERO SECTION STYLE (Tajuk Utama)
           ========================================== */
        .hero-section {
            /* Latar belakang merah maroon gradasi khas */
            background: linear-gradient(135deg, #4d0011 0%, #2e0108 100%);
            color: #ffffff;
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }

        /* Hiasan lingkaran radial di latar belakang hero */
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 10;
        }

        /* ==========================================
           4. CARD & BUTTON STYLE (Kartu & Tombol)
           ========================================== */
        /* Kartu Layanan */
        .card-service {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-service:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
            border-color: #800020;
        }

        /* Lingkaran pembungkus icon di dalam kartu */
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background-color: #fff0f2;
            color: #800020;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Spasi Padding Seksi */
        .section-padding {
            padding: 5rem 0;
        }

        /* Tombol Premium Utama (Maroon Gradasi) */
        .btn-premium {
            background: linear-gradient(135deg, #800020 0%, #5a0015 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            box-shadow: 0 10px 15px -3px rgba(128, 0, 32, 0.25);
            transition: all 0.2s ease;
        }

        .btn-premium:hover {
            background: linear-gradient(135deg, #b01c38 0%, #800020 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -3px rgba(128, 0, 32, 0.35);
        }

        /* Tombol Premium Outline (Border Putih Transparan) */
        .btn-premium-outline {
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: transparent;
            color: #ffffff;
            border-radius: 8px;
            padding: 0.8rem 1.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-premium-outline:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: #ffffff;
            color: #ffffff;
        }

        /* Tombol Maroon Solid */
        .btn-maroon {
            background: #800020;
            color: #fff !important;
            border: 2px solid #800020;
        }

        .btn-maroon:hover {
            background: #660018;
            border-color: #660018;
            color: #fff !important;
        }

        /* Tombol Outline Maroon */
        .btn-outline-maroon {
            background: #fff;
            color: #800020 !important;
            border: 2px solid #800020;
        }

        .btn-outline-maroon:hover {
            background: #800020;
            color: #fff !important;
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

        /* ==========================================
           6. RESPONSIVENESS & MEDIA QUERIES
           ========================================== */
        /* Tablet view */
        @media (max-width: 991.98px) {
            .hero-section {
                padding: 7rem 0 4rem;
                text-align: center;
            }

            .hero-section h1.display-4 {
                font-size: 2.4rem;
            }

            .hero-content .d-flex.flex-wrap.gap-3 {
                justify-content: center;
            }

            .section-padding {
                padding: 3.5rem 0;
            }

            .card-service {
                margin-bottom: 0.5rem;
            }

            .hero-qr {
                margin-top: 2rem;
            }

            .hero-qr img {
                max-width: 180px !important;
                padding: 1rem !important;
            }
        }

        /* Mobile View */
        @media (max-width: 767.98px) {
            .navbar-premium {
                padding: 0.6rem 0;
            }

            .navbar-premium .navbar-brand span {
                font-size: 1.1rem;
            }

            .navbar-nav {
                padding-top: 0.75rem;
                gap: 0.5rem !important;
            }

            .navbar-nav .btn {
                width: 100%;
                text-align: center;
            }

            .hero-section {
                padding: 6.5rem 0 3rem;
                text-align: center;
            }

            .hero-section h1.display-4 {
                font-size: 1.9rem;
                line-height: 1.3;
            }

            .hero-content .lead {
                font-size: 1rem;
            }

            .hero-content .d-flex.flex-wrap.gap-3 {
                flex-direction: column;
                justify-content: center;
            }

            .btn-premium,
            .btn-premium-outline {
                width: 100%;
                text-align: center;
                padding: 0.9rem 1.2rem;
            }

            .section-padding {
                padding: 2.5rem 0;
            }

            .icon-circle {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .card-service {
                padding: 1.25rem !important;
            }

            .accordion-button {
                font-size: 0.95rem;
                padding: 0.9rem 1rem;
            }

            .accordion-body {
                padding: 1rem !important;
            }

            #lokasi .btn-success {
                width: 100%;
                text-align: center;
            }

            .ratio.ratio-16x9 {
                margin-top: 1.5rem;
            }
        }

        @media (max-width: 399.98px) {
            .hero-section h1.display-4 {
                font-size: 1.6rem;
            }

            .badge.bg-primary.px-3.py-2.rounded-pill {
                font-size: 0.7rem;
            }
        }

        /* ==========================================
           7. FOOTER STYLE (Bawah Halaman)
           ========================================== */
        .footer {
            background-color: #2e0108;
            color: #94a3b8;
            padding: 1.5rem 0 2.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body>

    <!-- ========================================================
         BAGIAN NAVBAR ATAS (HEADER NAVIGATION)
         ======================================================== -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-premium fixed-top">
        <div class="container">
            <!-- Brand Logo dan Nama Notaris -->
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold font-heading fs-4" href="#">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>EKA SULISTYA</span>
            </a>
            
            <!-- Tombol Hamburger Responsif Layar Kecil -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- List Menu Navigasi -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#persyaratan">Persyaratan Berkas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#lokasi">Kontak & Lokasi</a>
                    </li>
                    
                    <!-- Kondisional Autentikasi Pengguna -->
                    @auth
                    <!-- Jika User Sudah Login, Tampilkan Link ke Dashboard Sesuai Role -->
                    <li class="nav-item">
                        @if(Auth::user()->role === 'client')
                        <a class="btn btn-primary btn-sm px-3 rounded-pill" href="{{ route('client.dashboard') }}">Dashboard Saya</a>
                        @else
                        <a class="btn btn-primary btn-sm px-3 rounded-pill" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        @endif
                    </li>
                    @else
                    <!-- Jika User Belum Login, Tampilkan Tombol Masuk dan Daftar -->
                    <li class="nav-item">
                        <a class="btn btn-outline-maroon btn-sm px-3 rounded-pill" href="{{ route('login') }}">
                            Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-maroon btn-sm px-3 rounded-pill" href="{{ route('register') }}">
                            Daftar
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- ========================================================
         HERO SECTION (BANNER UTAMA DENGAN BARCODE KUNJUNGAN)
         ======================================================== -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Teks Hero Penjelasan Singkat Portal Layanan -->
                <div class="col-lg-7 hero-content">
                    @if(session('success'))
                    <div class="alert alert-success border-0 shadow-lg alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 text-uppercase tracking-wider">Notaris & PPAT Eka Sulistya</span>
                    <h1 class="display-4 fw-extrabold text-white font-heading mb-1 lh-sm">
                        Sistem Informasi Management Operasional Dan Layanan Kantor
                    </h1>
                    <h1 class="display-4 fw-extrabold text-white font-heading mb-4 lh-sm">
                        Notaris & PPAT
                    </h1>
                    <p class="lead text-white-50 mb-5">
                        Selamat datang di portal pelayanan digital Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn. Ajukan permohonan layanan, pantau progres berkas, dan akses dokumen hukum Anda secara praktis dan aman.
                    </p>
                    <!-- Tombol Ajukan Layanan / Akses Dashboard -->
                    <div class="d-flex flex-wrap gap-3">
                        @auth
                        @if(Auth::user()->role === 'client')
                        <a href="{{ route('client.dashboard') }}" class="btn btn-premium btn-lg">
                            <i class="fa-solid fa-chart-pie me-2"></i> Dashboard Saya
                        </a>
                        @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-premium btn-lg">
                            <i class="fa-solid fa-chart-pie me-2"></i> Dashboard Admin
                        </a>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-premium btn-lg">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Ajukan Layanan
                        </a>
                        @endauth
                    </div>
                </div>
                
                <!-- QR Code Buku Tamu untuk Memudahkan Pengisian Presensi Kunjungan Fisik -->
                <div class="col-lg-5 text-center hero-qr">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ route('buku-tamu.checkin') }}" alt="QR Code Guestbook" class="img-thumbnail p-4 rounded-4 shadow-lg mb-3" style="max-width: 280px; border: 1px solid rgba(255,255,255,0.1);">
                    <p class="text-white-50 small"><i class="fa-solid fa-qrcode me-2"></i> Pindai QR Code untuk mengisi Buku Tamu Kunjungan</p>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================================
         DAFTAR LAYANAN UTAMA (SERVICES CARDS)
         ======================================================== -->
    <section class="section-padding" id="layanan">
        <div class="container">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="text-primary fw-bold text-uppercase">Layanan Utama</span>
                <h2 class="font-heading fw-bold mt-2">Daftar Layanan Notaris & PPAT</h2>
                <p class="text-muted">Kami melayani pengurusan akta hukum autentik serta urusan legalitas dokumen dengan proses yang terstruktur.</p>
            </div>

            <!-- Menampilkan list layanan yang disediakan kantor secara dinamis -->
            <div class="row g-4">
                @foreach($layanan as $lay)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-service p-4 d-flex flex-column h-100 {{ !$lay->status_aktif ? 'opacity-75 bg-light-subtle' : '' }}">
                        <!-- Icon & Status Badge Layanan -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-circle mb-0">
                                @if($loop->iteration == 1)
                                <i class="fa-solid fa-house-chimney"></i>
                                @elseif($loop->iteration == 2)
                                <i class="fa-solid fa-building"></i>
                                @elseif($loop->iteration == 3)
                                <i class="fa-solid fa-gift"></i>
                                @else
                                <i class="fa-solid fa-stamp"></i>
                                @endif
                            </div>

                            @if($lay->status_aktif)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small" style="font-size: 0.75rem;">
                                    🟢 Layanan tersedia
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small" style="font-size: 0.75rem;">
                                    🔴 Layanan sedang tidak tersedia
                                </span>
                            @endif
                        </div>

                        <h5 class="fw-bold font-heading mb-2">{{ $lay->nama_layanan }}</h5>
                        <p class="text-muted small mb-4 flex-grow-1">{{ Str::limit($lay->deskripsi, 120) }}</p>

                        <div class="mt-auto border-top pt-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i> {{ $lay->estimasi_waktu }}</span>
                            </div>

                            @if($lay->status_aktif)
                                @auth
                                    <a href="{{ route('client.permintaan.create') }}" class="btn btn-sm btn-primary w-100 rounded-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary w-100 rounded-3 py-2 fw-semibold">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Sekarang
                                    </a>
                                @endauth
                            @else
                                <button class="btn btn-sm btn-secondary w-100 rounded-3 py-2 fw-semibold" disabled title="Layanan sedang tidak aktif">
                                    <i class="fa-solid fa-ban me-1"></i> Layanan Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================================
         PERSYARATAN BERKAS (ACCORDION REQUIREMENTS)
         ======================================================== -->
    <section class="section-padding bg-light" id="persyaratan">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-primary fw-bold text-uppercase">Persyaratan Berkas</span>
                <h2 class="font-heading fw-bold mt-2">Dokumen yang Perlu Dipersiapkan</h2>
                <p class="text-muted">Siapkan dokumen-dokumen berikut sebelum melakukan pengajuan secara online maupun datang ke kantor.</p>
            </div>

            <!-- Tampilan list akordeon untuk masing-masing berkas persyaratan tiap layanan -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion shadow-sm border-0" id="accordionRequirements">
                        @foreach($layanan as $lay)
                        <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-sm">
                            <h2 class="accordion-header" id="heading-{{ $lay->id }}">
                                <button class="accordion-button collapsed fw-bold font-heading d-flex justify-content-between align-items-center w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $lay->id }}">
                                    <div>
                                        <i class="fa-solid fa-folder-open text-primary me-2"></i> {{ $lay->nama_layanan }}
                                    </div>
                                    <div class="me-3">
                                        @if($lay->status_aktif)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small fw-normal"><i class="fa-solid fa-circle text-success me-1" style="font-size: 6px;"></i> Tersedia</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small fw-normal"><i class="fa-solid fa-circle text-danger me-1" style="font-size: 6px;"></i> Tidak Tersedia</span>
                                        @endif
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse-{{ $lay->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionRequirements">
                                <div class="accordion-body bg-white p-4">
                                    <ul class="list-group list-group-flush">
                                        @forelse($lay->persyaratan as $req)
                                        <li class="list-group-item d-flex flex-wrap gap-2 justify-content-between align-items-center py-3 border-0 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-regular fa-circle-check text-success"></i>
                                                <span>{{ $req->nama_dokumen }}</span>
                                            </div>
                                            <span class="badge bg-light text-dark rounded-pill">{{ $req->keterangan }}</span>
                                        </li>
                                        @empty
                                        <li class="list-group-item text-muted">Hubungi notaris untuk detail persyaratan.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================
         KONTAK & DETAIL LOKASI GOOGLE MAPS KANTOR
         ======================================================== -->
    <section class="section-padding bg-white" id="lokasi">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Data Detail Kontak (Alamat, Jam Kerja, Telepon, Email) -->
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase">Kontak & Lokasi</span>
                    <h2 class="font-heading fw-bold mt-2 mb-4">Kunjungi Kantor Kami</h2>
                    <p class="text-muted mb-4">
                        Kantor kami berlokasi strategis di Kota Pontianak. Silakan berkunjung pada jam kerja operasional atau hubungi kontak kami jika memerlukan informasi lebih lanjut.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <!-- Alamat Kantor -->
                        <div class="d-flex gap-3">
                            <div class="icon-circle bg-light text-primary mb-0 mt-1" style="width:45px;height:45px;flex-shrink:0;">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Alamat</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $profil->alamat }}
                                </p>
                            </div>
                        </div>

                        <!-- Jam Operasional Kerja -->
                        <div class="d-flex gap-3">
                            <div class="icon-circle bg-light text-primary mb-0 mt-1" style="width:45px;height:45px;flex-shrink:0;">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Jam Operasional</h6>
                                <p class="text-muted mb-0 small">Senin - Jumat : 08.00 - 17.00 WIB</p>
                                <p class="text-muted mb-0 small">Sabtu : 08.00 - 12.00 WIB</p>
                                <p class="text-muted mb-0 small">Minggu & Hari Libur Nasional : Tutup</p>
                            </div>
                        </div>

                        <!-- Nomor Kontak Kantor -->
                        <div class="d-flex gap-3">
                            <div class="icon-circle bg-light text-primary mb-0 mt-1" style="width:45px;height:45px;flex-shrink:0;">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Telepon / WhatsApp</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $profil->no_telepon }}
                                </p>
                            </div>
                        </div>

                        <!-- Surel Email Kantor -->
                        <div class="d-flex gap-3">
                            <div class="icon-circle bg-light text-primary mb-0 mt-1" style="width:45px;height:45px;flex-shrink:0;">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Surel Resmi</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $profil->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol WhatsApp API Direct Chat -->
                    @php
                    $waNumber = preg_replace('/[^0-9]/', '', $profil->no_telepon);
                    if (substr($waNumber, 0, 1) === '0') {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    @endphp
                    <a href="https://wa.me/{{ $waNumber }}" class="btn btn-success rounded-3 px-4 py-2" target="_blank">
                        <i class="fa-brands fa-whatsapp me-2 fs-5"></i>
                        Konsultasi via WhatsApp
                    </a>
                </div>

                <!-- Google Maps Frame -->
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm" style="border:1px solid #e2e8f0;">
                        <iframe
                            @if($profil->latitude && $profil->longitude)
                            src="https://www.google.com/maps?q={{ $profil->latitude }},{{ $profil->longitude }}&output=embed"
                            @else
                            src="https://www.google.com/maps?q={{ urlencode($profil->nama_kantor . ', ' . $profil->alamat) }}&output=embed"
                            @endif
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================
         BAGIAN FOOTER HALAMAN LANDING PAGE
         ======================================================== -->
    <footer class="footer">
        <div class="text-center small text-white-50 mt-3">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $profil->nama_kantor }}. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS (Dropdown, Collapse, Modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>