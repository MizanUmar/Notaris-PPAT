<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil->nama_kantor }} - Pelayanan & Manajemen Data Client</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        h1,
        h2,
        h3,
        h4,
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .navbar-premium {
            background-color: rgb(234, 0, 0);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 0;
        }

        .hero-section {
            background: linear-gradient(135deg, #4d0011 0%, #2e0108 100%);
            color: #ffffff;
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }

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

        .info-strip {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        }

        .section-padding {
            padding: 5rem 0;
        }

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

        .footer {
            background-color: #2e0108;
            color: #94a3b8;
            padding: 1rem 0 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Custom Bootstrap Overrides for Maroon Theme */
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

        /* =========================
   Navbar
========================= */

        .navbar-premium {
            background: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
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

        /* Tombol Maroon */

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

        /* Tombol Outline */

        .btn-outline-maroon {
            background: #fff;
            color: #800020 !important;
            border: 2px solid #800020;
        }

        .btn-outline-maroon:hover {
            background: #800020;
            color: #fff !important;
        }

        /* Mobile */

        .navbar-toggler {
            border-color: #800020;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .card-service {

            background: #fff;

            border-radius: 18px;

            padding: 35px 25px;

            transition: .35s;

            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);

            text-align: center;

            height: 100%;

        }

        .card-service:hover {

            transform: translateY(-10px);

            box-shadow: 0 18px 40px rgba(0, 0, 0, .12);

        }

        .card-service .icon-circle {

            width: 75px;

            height: 75px;

            margin: auto;

            border-radius: 50%;

            background: #800020;

            color: #fff;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

        }

        #persyaratan .card {

            transition: .3s;

        }

        #persyaratan .card:hover {

            transform: translateY(-8px);

        }

        .footer-premium {

            background: #800020;

            color: #fff;

            padding: 70px 0 30px;

        }

        .footer-premium a {

            color: #fff;

            text-decoration: none;

        }

        .footer-premium a:hover {

            color: #ffd54f;

        }

        .footer-premium hr {

            border-color: rgba(255, 255, 255, .2);

        }

        /* ===========================
   Mobile
=========================== */

        @media(max-width:992px) {

            .hero-section {

                padding-top: 120px;

                text-align: center;

            }

            .hero-section img {

                max-width: 230px;

            }

            .display-4 {

                font-size: 2.3rem;

            }

            .navbar-collapse {

                background: #fff;

                padding: 20px;

                border-radius: 15px;

                box-shadow: 0 15px 30px rgba(0, 0, 0, .08);

                margin-top: 15px;

            }

            .navbar-nav .btn {

                width: 100%;

                margin-top: 10px;

            }

        }

        @media(max-width:768px) {

            .section-padding {

                padding: 70px 0;

            }

            .display-4 {

                font-size: 2rem;

            }

            h2 {

                font-size: 1.8rem;

            }

        }

        @media(max-width:576px) {

            .display-4 {

                font-size: 1.7rem;

            }

            h2 {

                font-size: 1.5rem;

            }

            .btn {

                width: 100%;

            }

            .navbar-brand {

                font-size: 1.1rem;

            }

            .icon-circle {

                width: 55px;

                height: 55px;

            }

        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-premium fixed-top">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold font-heading" href="#">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>{{ $profil->nama_kantor }}</span>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-lg-center text-center">

                    <li class="nav-item">
                        <a class="nav-link" href="#layanan">Layanan</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#persyaratan">
                            Persyaratan Berkas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#lokasi">
                            Kontak & Lokasi
                        </a>
                    </li>

                    @auth

                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3">

                        @if(Auth::user()->role=='client')

                        <a href="{{ route('client.dashboard') }}"
                            class="btn btn-maroon rounded-pill px-4">

                            Dashboard Saya

                        </a>

                        @else

                        <a href="{{ route('admin.dashboard') }}"
                            class="btn btn-maroon rounded-pill px-4">

                            Dashboard Admin

                        </a>

                        @endif

                    </li>

                    @else

                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3">

                        <a href="{{ route('login') }}"
                            class="btn btn-outline-maroon rounded-pill px-4">

                            Masuk

                        </a>

                    </li>

                    <li class="nav-item mt-2 mt-lg-0 ms-lg-2">

                        <a href="{{ route('register') }}"
                            class="btn btn-maroon rounded-pill px-4">

                            Daftar

                        </a>

                    </li>

                    @endauth

                </ul>

            </div>

        </div>

    </nav>

    <!-- Hero Section -->
    <header class="hero-section">

        <div class="container">

            <div class="row align-items-center gy-5">

                <div class="col-12 col-lg-7 hero-content text-center text-lg-start">

                    @if(session('success'))

                    <div class="alert alert-success alert-dismissible fade show">

                        {!! session('success') !!}

                        <button class="btn-close"
                            data-bs-dismiss="alert"></button>

                    </div>

                    @endif

                    <span class="badge bg-primary rounded-pill px-4 py-2 mb-3">

                        Notaris & PPAT Eka Sulistya

                    </span>

                    <h1 class="display-4 fw-bold text-white mb-3">

                        Sistem Informasi Management Operasional dan Layanan Kantor

                    </h1>

                    <h2 class="text-white fw-bold mb-4">

                        Notaris & PPAT

                    </h2>

                    <p class="lead text-white-50 mb-5">

                        Selamat datang di portal pelayanan digital
                        Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn.

                        Ajukan permohonan layanan,
                        pantau progres,
                        dan akses dokumen hukum secara online.

                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">

                        @auth

                        @if(Auth::user()->role=='client')

                        <a href="{{ route('client.dashboard') }}"
                            class="btn btn-premium">

                            Dashboard Saya

                        </a>

                        @else

                        <a href="{{ route('admin.dashboard') }}"
                            class="btn btn-premium">

                            Dashboard Admin

                        </a>

                        @endif

                        @else

                        <a href="{{ route('login') }}"
                            class="btn btn-premium">

                            Ajukan Layanan

                        </a>

                        @endauth

                    </div>

                </div>

                <div class="col-12 col-lg-5 text-center">

                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ route('buku-tamu.checkin') }}"
                        class="img-fluid bg-white p-4 rounded-4 shadow-lg"
                        style="max-width:280px;">

                    <p class="text-white-50 mt-3">

                        Scan QR Code
                        untuk Buku Tamu

                    </p>

                </div>

            </div>

        </div>

    </header>

    <!-- Layanan Section -->
    <section class="section-padding bg-light" id="layanan">
        <div class="container">

            <div class="text-center mb-5">
                <span class="text-primary fw-bold text-uppercase">
                    Layanan Kami
                </span>

                <h2 class="font-heading fw-bold mt-2">
                    Layanan Notaris & PPAT
                </h2>

                <p class="text-muted mx-auto" style="max-width:700px;">
                    Kami menyediakan berbagai layanan hukum secara profesional,
                    cepat, aman, dan terpercaya untuk kebutuhan masyarakat.
                </p>
            </div>

            <div class="row g-4">

                @foreach($layanan as $item)

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card-service h-100">

                        <div class="icon-circle">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>

                        <h5 class="mt-4 fw-bold">
                            {{ $item->nama }}
                        </h5>

                        <p class="text-muted mt-3">
                            {{ Str::limit($item->deskripsi, 120) }}
                        </p>

                    </div>
                </div>

                @endforeach

            </div>

        </div>
    </section>

    <!-- Persyaratan Section -->
    <section class="section-padding bg-white" id="persyaratan">

        <div class="container">

            <div class="text-center mb-5">

                <span class="text-primary fw-bold text-uppercase">

                    Persyaratan

                </span>

                <h2 class="font-heading fw-bold mt-2">

                    Persyaratan Berkas

                </h2>

                <p class="text-muted">

                    Berikut persyaratan dokumen yang perlu disiapkan sebelum
                    mengajukan layanan.

                </p>

            </div>

            <div class="row g-4">

                @foreach($layanan as $item)

                <div class="col-12 col-md-6">

                    <div class="card h-100 border-0 shadow-sm rounded-4">

                        <div class="card-body p-4">

                            <h5 class="fw-bold text-primary">

                                {{ $item->nama }}

                            </h5>

                            <hr>

                            <p class="text-muted">

                                {{ $layanan->persyaratan }}

                            </p>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </section>

    <!-- Kontak & Lokasi -->
    <section class="section-padding bg-white" id="lokasi">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-12 col-lg-6">

                    <span class="text-primary fw-bold text-uppercase">

                        Kontak Kami

                    </span>

                    <h2 class="font-heading fw-bold mt-2 mb-4">

                        Hubungi Kami

                    </h2>

                    <p class="text-muted mb-4">

                        Kami siap membantu kebutuhan layanan notaris dan PPAT.
                        Silakan hubungi kami melalui informasi berikut.

                    </p>

                    <div class="d-flex mb-4">

                        <div class="icon-circle me-3">

                            <i class="fa-solid fa-location-dot"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold">

                                Alamat

                            </h6>

                            <p class="text-muted mb-0">

                                {{ $profil->alamat }}

                            </p>

                        </div>

                    </div>

                    <div class="d-flex mb-4">

                        <div class="icon-circle me-3">

                            <i class="fa-solid fa-phone"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold">

                                Telepon

                            </h6>

                            <p class="text-muted mb-0">

                                {{ $profil->no_telepon }}

                            </p>

                        </div>

                    </div>

                    <div class="d-flex mb-4">

                        <div class="icon-circle me-3">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div>

                            <h6 class="fw-bold">

                                Email

                            </h6>

                            <p class="text-muted mb-0">

                                {{ $profil->email }}

                            </p>

                        </div>

                    </div>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$profil->no_telepon) }}"
                        target="_blank"
                        class="btn btn-maroon rounded-pill px-4 py-2">

                        <i class="fa-brands fa-whatsapp me-2"></i>

                        Hubungi via WhatsApp

                    </a>

                </div>

                <div class="col-12 col-lg-6">

                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow">

                        <iframe
                            src="{{ $profil->maps }}"
                            style="border:0"
                            allowfullscreen
                            loading="lazy">
                        </iframe>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer class="footer-premium">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-6">

                    <h4 class="fw-bold">

                        {{ $profil->nama_kantor }}

                    </h4>

                    <p class="mt-3">

                        Sistem Informasi Management Operasional
                        dan Layanan Kantor Notaris & PPAT.

                    </p>

                </div>

                <div class="col-lg-3">

                    <h5>

                        Menu

                    </h5>

                    <ul class="list-unstyled">

                        <li><a href="#layanan">Layanan</a></li>

                        <li><a href="#persyaratan">Persyaratan</a></li>

                        <li><a href="#lokasi">Kontak</a></li>

                    </ul>

                </div>

                <div class="col-lg-3">

                    <h5>

                        Kontak

                    </h5>

                    <p>

                        {{ $profil->no_telepon }}

                    </p>

                    <p>

                        {{ $profil->email }}

                    </p>

                </div>

            </div>

            <hr class="my-4">

            <div class="text-center">

                © {{ date('Y') }}

                {{ $profil->nama_kantor }}

                | All Rights Reserved.

            </div>

        </div>

    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>