<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul dinamis halaman autentikasi (Login/Register) -->
    <title>@yield('title') - Notaris & PPAT Eka Sulistya</title>

    <!-- Google Fonts: Inter untuk font yang bersih dan profesional -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS untuk layouting dan form responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 untuk pustaka icon pendukung form -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        /* ==========================================
           1. STYLING GLOBAL & BACKGROUND GRADIENT
           ========================================== */
        body {
            font-family: 'Inter', sans-serif;
            /* Latar belakang gradasi merah maroon gelap/premium khas Notaris */
            background: linear-gradient(135deg, #4d0011 0%, #2e0108 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* ==========================================
           2. AUTH CARD & CONTAINER STYLE
           ========================================== */
        /* Wadah penyeimbang lebar form di tengah layar */
        .auth-container {
            width: 100%;
            max-width: 480px;
        }

        /* Kartu putih wadah form login/registrasi */
        .auth-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
        }

        /* Logo Brand di atas form auth */
        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-brand i {
            font-size: 2.5rem;
            color: #800020; /* Merah maroon */
            margin-bottom: 0.5rem;
        }

        .auth-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.5rem;
        }

        /* ==========================================
           3. PREMIUM FORM INPUTS & BUTTONS
           ========================================== */
        /* Input form bergaya premium dengan transisi fokus */
        .form-control-premium {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        /* Efek fokus pada kolom input form */
        .form-control-premium:focus {
            border-color: #800020;
            box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.1);
        }

        /* Tombol Utama Premium dengan warna gradasi merah maroon */
        .btn-premium-primary {
            background: linear-gradient(135deg, #800020 0%, #5a0015 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(128, 0, 32, 0.2);
            transition: all 0.2s ease;
            width: 100%;
        }

        /* Efek hover tombol utama */
        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #b01c38 0%, #800020 100%);
            box-shadow: 0 6px 8px -1px rgba(128, 0, 32, 0.3);
            color: #ffffff;
        }

        /* ==========================================
           4. AUTH FOOTER STYLE (Link Bawah Card)
           ========================================== */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .auth-footer a {
            color: #800020;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- Wadah penyeimbang letak form auth di tengah -->
    <div class="auth-container">
        <!-- Tempat menyisipkan konten form auth dari child view -->
        @yield('content')
    </div>

    <!-- Bootstrap Bundle JS (Popovers, Tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script tambahan dari sub-view halaman -->
    @yield('scripts')
</body>

</html>