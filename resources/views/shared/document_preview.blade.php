@extends('layouts.app')

@section('title', 'Preview Dokumen - ' . $title)

@section('content')
<div class="container-fluid p-0">
    <!-- Header Halaman Pratinjau Dokumen -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Pratinjau Dokumen</h2>
            <p class="text-muted mb-0">Periksa isi berkas akta/surat secara dinamis sebelum diunduh atau dicetak.</p>
        </div>
        <div>
            <!-- Tombol kembali yang dinamis menyesuaikan role user (admin/client) dan kategori dokumen -->
            <a href="{{ $isAdmin ? ($documentType === 'akta' ? route('admin.akta.index') : route('admin.surat.index')) : ($documentType === 'akta' ? route('client.akta.index') : route('client.surat.index')) }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- ========================================================
             1. DETAIL METADATA DOKUMEN (KOLOM KIRI)
             ======================================================== -->
        <div class="col-lg-4">
            <div class="card card-premium p-4 mb-4 shadow-sm border-0">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2 text-primary">
                    <i class="fa-solid fa-circle-info me-1"></i> Informasi Berkas
                </h5>
                <!-- Jenis Dokumen (Akta atau Surat) -->
                <div class="mb-3">
                    <small class="text-muted d-block small">Jenis Dokumen</small>
                    <span class="badge {{ $documentType === 'akta' ? 'bg-success' : 'bg-info text-white' }} text-capitalize fw-semibold px-2 py-1">
                        {{ $documentType }}
                    </span>
                </div>
                <!-- Judul Berkas -->
                <div class="mb-3">
                    <small class="text-muted d-block small">Judul / Nama Berkas</small>
                    <span class="fw-bold text-dark">{{ $title }}</span>
                </div>
                <!-- Nomor Surat/Akta -->
                <div class="mb-3">
                    <small class="text-muted d-block small">Nomor Resmi</small>
                    <span class="fw-semibold font-monospace text-dark">{{ $number }}</span>
                </div>
                <!-- Tanggal Terbit Dokumen -->
                <div class="mb-3">
                    <small class="text-muted d-block small">Tanggal Terbit</small>
                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                </div>
                <!-- Hubungan Layanan -->
                <div class="mb-3">
                    <small class="text-muted d-block small">Layanan Hukum</small>
                    <span class="fw-semibold text-primary">{{ $permintaan->layanan->nama_layanan ?? '-' }}</span>
                </div>
                <!-- Detail Nama Client -->
                <div class="mb-4">
                    <small class="text-muted d-block small">Pemohon (Client)</small>
                    <span class="fw-semibold text-capitalize text-dark">{{ $permintaan->client->user->nama ?? '-' }}</span>
                </div>
                
                <hr>

                <!-- Tombol Aksi Unduh PDF dan Cetak Fisik -->
                <div class="d-flex flex-column gap-2 mt-3">
                    <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="btn btn-premium-primary w-100 py-2 fw-bold shadow-xs">
                        <i class="fa-solid fa-file-pdf me-1"></i> Unduh Salinan PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-dark w-100 py-2 fw-bold">
                        <i class="fa-solid fa-print me-1"></i> Cetak / Print
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================
             2. PANEL EMBED DOKUMEN / KERTAS VIRTUAL (KOLOM KANAN)
             ======================================================== -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
                    <span class="small fw-semibold text-muted"><i class="fa-solid fa-scroll me-1"></i> Salinan Digital Resmi</span>
                    <span class="badge bg-white border text-dark font-monospace">A4 / Notary Layout</span>
                </div>
                <div class="card-body p-5 bg-dark-subtle d-flex justify-content-center" style="overflow-x: auto;">
                    
                    <!-- KONDISI A: TAMPILAN BERKAS AKTA NOTARIS -->
                    @if($documentType === 'akta')
                    <div class="notary-paper-container shadow-lg">
                        <div class="notary-paper-body">
                            <!-- Judul Tengah Akta Notaris -->
                            <div style="text-align: center; margin-bottom: 30px; font-weight: bold; font-family: 'Times New Roman', Times, serif;">
                                <div style="font-size: 16px; text-transform: uppercase;">{{ $title }}</div>
                                <div style="font-size: 14px; margin-top: 5px;">Nomor : {{ $number }}</div>
                            </div>
                            <!-- Isi Akta HTML CKEditor -->
                            {!! $content !!}
                        </div>
                    </div>
                    @else
                    <!-- KONDISI B: TAMPILAN BERKAS SURAT KELUAR RESMI -->
                    <div class="letter-paper-container shadow-lg d-flex flex-column justify-content-between">
                        <div>
                            <!-- Kop Surat Resmi Notaris Eka Sulistya -->
                            <div class="letter-header" style="text-align: center; font-family: 'Times New Roman', Times, serif; margin-bottom: 20px; line-height: 1.25;">
                                <img src="{{ asset('garuda_logo.png') }}" style="width: 70px; height: auto; display: block; margin: 0 auto 5px;" alt="Logo Garuda">
                                <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; padding: 0; letter-spacing: 0.5px; text-align: center;">NOTARIS & PPAT</div>
                                <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 2px 0 0; padding: 0; letter-spacing: 0.5px; text-align: center;">EKA SULISTYA, S.H., M.Kn.</div>
                                <div style="font-size: 8.5pt; font-weight: normal; margin: 4px 0 0; padding: 0; text-align: center;">SK KEMENKUM RI Nomor : AHU-01601.AH.02.01.TAHUN 2025 Tanggal 04 Maret 2025</div>
                                <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">Kedudukan Kota Pontianak, Wilayah Kerja Provinsi Kalimantan Barat</div>
                                <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">SK Menteri ATR/KBPN RI Nomor 717/SK-HR.03.04.PPAT/VI/2025</div>
                                <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">Kedudukan Kota Pontianak</div>
                                <hr style="border: none; border-top: 4px solid #000; opacity: 1; margin: 12px 0 0 0;">
                            </div>
                            <!-- Isi Surat HTML CKEditor -->
                            <div class="letter-body">
                                {!! $content !!}
                            </div>
                        </div>
                        <!-- Kaki Surat (Footer) -->
                        <div class="letter-footer" style="text-align: center; font-family: 'Times New Roman', Times, serif; font-size: 9pt; border-top: 1px solid #000; padding-top: 10px; margin-top: 50px; line-height: 1.4;">
                            <div>Jl. Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat 78116</div>
                            <div>e-mail : ekasulistyanotaris@gmail.com</div>
                            <div>Hp : 085931148582</div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ========================================================
       STYLING SIMULASI KERTAS AKTA NOTARIS SISI KANAN
       ======================================================== */
    .notary-paper-container {
        width: 210mm; /* Lebar Kertas A4 */
        min-height: 297mm; /* Tinggi Kertas A4 */
        background-color: #ffffff;
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
        box-sizing: border-box;
    }

    /* Efek visual garis merah ganda khas kertas minuta Notaris */
    .notary-paper-container::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 3.5cm; /* Garis batas merah kiri */
        width: 3px;
        border-left: 1px solid #d9534f;
        border-right: 1px solid #d9534f;
        z-index: 10;
    }

    /* Layout margin penulisan standar notaris */
    .notary-paper-body {
        padding: 3.8cm 2.2cm 3.8cm 4.0cm; /* Margin baku minuta */
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 2.8; /* Spasi ganda/lebar */
        color: #000000;
        text-align: justify;
        word-break: break-word;
    }

    .notary-paper-body p {
        margin: 0 0 1.5rem 0;
        text-indent: 0;
    }

    /* ========================================================
       STYLING SIMULASI KERTAS SURAT RESMI
       ======================================================== */
    .letter-paper-container {
        width: 210mm;
        min-height: 297mm;
        background-color: #ffffff;
        padding: 2.5cm 2cm 2.5cm 2.5cm;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
        box-sizing: border-box;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        color: #000000;
    }

    .letter-body {
        line-height: 1.6;
        text-align: justify;
    }

    .letter-body p {
        margin-bottom: 1rem;
    }

    .letter-body table {
        width: 100% !important;
        margin-bottom: 1.5rem;
    }

    .letter-body td {
        padding: 3px 0;
    }

    /* ========================================================
       MEDIA QUERY CETAK (PRINT STYLES)
       Menyembunyikan sidebar dan navbar saat print fisik/simpan PDF browser
       ======================================================== */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body * {
            visibility: hidden;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .notary-paper-container, .notary-paper-container *,
        .letter-paper-container, .letter-paper-container * {
            visibility: visible;
        }
        .notary-paper-container, .letter-paper-container {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        .notary-paper-body {
            padding: 3.0cm 2.0cm 3.0cm 3.5cm !important;
        }
        .letter-paper-container {
            padding: 2.0cm 2.0cm 2.5cm 2.5cm !important;
        }
    }
</style>
@endsection
