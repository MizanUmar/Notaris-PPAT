<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code Buku Tamu - Notaris & PPAT Eka Sulistya</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }

        .print-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            margin: 3rem auto;
            padding: 3rem;
            text-align: center;
            border: 2px solid #e2e8f0;
        }

        .office-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        .qr-frame {
            border: 4px solid #800020;
            border-radius: 16px;
            padding: 1.5rem;
            background-color: #ffffff;
            display: inline-block;
            margin: 2rem 0;
            box-shadow: 0 8px 16px rgba(0,0,0,0.06);
        }

        .step-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #800020;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .text-maroon {
            color: #800020 !important;
        }

        @media print {
            body {
                background-color: #ffffff;
            }
            .print-card {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 1rem;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Floating Action Button -->
        <div class="d-flex justify-content-center gap-2 mt-4 no-print">
            <button onclick="window.print()" class="btn btn-dark px-4 py-2 rounded-3 shadow"><i class="fa-solid fa-print me-2"></i> Cetak Halaman</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3"><i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Dashboard</a>
        </div>

        <div class="print-card">
            <i class="fa-solid fa-scale-balanced text-maroon fs-1 mb-3"></i>
            <h1 class="office-title text-uppercase fs-3 mb-1">Kantor Notaris & PPAT</h1>
            <h2 class="office-title text-uppercase fs-4 text-maroon mb-3">Eka Sulistya, S.H., M.Kn.</h2>
            <p class="text-muted small">Jl. Pangeran Natakusuma, Pontianak</p>
            
            <div class="border-top border-bottom my-4 py-2">
                <h5 class="fw-bold font-heading mb-0 text-dark tracking-wide">PENCATATAN KUNJUNGAN TAMU DIGITAL</h5>
            </div>

            <p class="text-secondary">Silakan pindai (scan) QR Code di bawah menggunakan HP Anda untuk mengisi Buku Tamu digital kami.</p>

            <div class="qr-frame">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $checkInUrl }}" alt="Check-in QR" style="width: 220px;">
            </div>

            <!-- Steps -->
            <div class="row g-3 text-center mt-3 justify-content-center">
                <div class="col-md-3 d-flex flex-column align-items-center">
                    <div class="step-num">1</div>
                    <span class="small fw-bold">Scan QR Code</span>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center">
                    <div class="step-num">2</div>
                    <span class="small fw-bold">Isi Form Kunjungan</span>
                </div>
                <div class="col-md-3 d-flex flex-column align-items-center">
                    <div class="step-num">3</div>
                    <span class="small fw-bold">Check-in Selesai</span>
                </div>
            </div>

            <div class="border-top mt-5 pt-3 text-center">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> Didukung oleh Sistem Manajemen Digital Kantor Notaris</small>
            </div>
        </div>
    </div>

</body>
</html>
