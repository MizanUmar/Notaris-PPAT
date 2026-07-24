<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Kunjungan - Notaris & PPAT Eka Sulistya</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #4d0011 0%, #2e0108 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .guest-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            max-width: 550px;
            width: 100%;
            padding: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .office-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #0f172a;
        }

        .form-control-premium {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-premium:focus {
            border-color: #800020;
            box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.1);
        }

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

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #b01c38 0%, #800020 100%);
            box-shadow: 0 6px 8px -1px rgba(128, 0, 32, 0.3);
            color: #ffffff;
        }

        .text-primary {
            color: #800020 !important;
        }
    </style>
</head>
<body>

    <div class="guest-card">
        <div class="text-center mb-4">
            <i class="fa-solid fa-scale-balanced text-primary fs-1 mb-2"></i>
            <h3 class="office-title text-uppercase mb-1">Buku Tamu Digital</h3>
            <p class="text-muted small">Notaris & PPAT Eka Sulistya, S.H., M.Kn.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 border-0 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('buku-tamu.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap Tamu</label>
                <input type="text" name="nama_tamu" class="form-control form-control-premium" placeholder="Masukkan nama Anda" value="{{ old('nama_tamu', $user->nama ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Instansi / Pekerjaan</label>
                <input type="text" name="instansi" class="form-control form-control-premium" placeholder="Contoh: Swasta, PT Maju Bersama, Urusan Pribadi" value="{{ old('instansi') }}">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Nomor HP / WhatsApp</label>
                <input type="text" name="nomor_hp" class="form-control form-control-premium" placeholder="Masukkan nomor telepon aktif" value="{{ old('nomor_hp', $client->no_hp ?? '') }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Keperluan Kunjungan</label>
                <textarea name="keperluan" class="form-control form-control-premium" rows="3" placeholder="Contoh: Legalisasi dokumen, konsultasi AJB, serah terima berkas..." required>{{ old('keperluan') }}</textarea>
            </div>

            <button type="submit" class="btn btn-premium-primary mb-3"><i class="fa-solid fa-circle-check me-1"></i> Catat Kehadiran Saya</button>
        </form>

        <div class="text-center mt-3">
            @auth
                @if(Auth::user()->role === 'client')
                    <a href="{{ route('client.dashboard') }}" class="text-muted small"><i class="fa-solid fa-arrow-left-long me-1"></i> Kembali ke Dashboard</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="text-muted small"><i class="fa-solid fa-arrow-left-long me-1"></i> Kembali ke Dashboard</a>
                @endif
            @else
                <a href="{{ route('landing') }}" class="text-muted small"><i class="fa-solid fa-arrow-left-long me-1"></i> Kembali ke Beranda</a>
            @endauth
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
