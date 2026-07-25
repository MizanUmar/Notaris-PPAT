@extends('layouts.app')

@section('title', 'Preview Dokumen - ' . $title)

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold font-heading mb-1">Pratinjau Dokumen</h2>
            <p class="text-muted mb-0">Periksa isi berkas akta/surat secara dinamis sebelum diunduh atau dicetak.</p>
        </div>
        <div>
            <a href="{{ $isAdmin ? ($documentType === 'akta' ? route('admin.akta.index') : route('admin.surat.index')) : ($documentType === 'akta' ? route('client.akta.index') : route('client.surat.index')) }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Metadata -->
        <div class="col-lg-4">
            <div class="card card-premium p-4 mb-4 shadow-sm border-0">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2 text-primary">
                    <i class="fa-solid fa-circle-info me-1"></i> Informasi Berkas
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block small">Jenis Dokumen</small>
                    <span class="badge {{ $documentType === 'akta' ? 'bg-success' : 'bg-info text-white' }} text-capitalize fw-semibold px-2 py-1">
                        {{ $documentType }}
                    </span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block small">Judul / Nama Berkas</small>
                    <span class="fw-bold text-dark">{{ $title }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block small">Nomor Resmi</small>
                    <span class="fw-semibold font-monospace text-dark">{{ $number }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block small">Tanggal Terbit</small>
                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block small">Layanan Hukum</small>
                    <span class="fw-semibold text-primary">{{ $permintaan->layanan->nama_layanan ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block small">Pemohon (Client)</small>
                    <span class="fw-semibold text-capitalize text-dark">{{ $permintaan->client->user->nama ?? '-' }}</span>
                </div>
                
                <hr>

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

        <!-- Main Document Sheet -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
                    <span class="small fw-semibold text-muted"><i class="fa-solid fa-scroll me-1"></i> Salinan Digital Resmi</span>
                    <span class="badge bg-white border text-dark font-monospace">A4 / Notary Layout</span>
                </div>
                <div class="card-body p-5 bg-dark-subtle d-flex justify-content-center" style="overflow-x: auto;">
                    
                    @if($documentType === 'akta')
                    <!-- NOTARY PAPER LAYOUT -->
                    <div class="notary-paper-container shadow-lg">
                        <div class="notary-paper-body">
                            <div style="text-align: center; margin-bottom: 30px; font-weight: bold; font-family: 'Times New Roman', Times, serif;">
                                <div style="font-size: 16px; text-transform: uppercase;">{{ $title }}</div>
                                <div style="font-size: 14px; margin-top: 5px;">Nomor : {{ $number }}</div>
                            </div>
                            {!! $content !!}
                        </div>
                    </div>
                    @else
                    <!-- CORPORATE LETTER LAYOUT -->
                    <div class="letter-paper-container shadow-lg">
                        <div class="letter-header">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <div class="text-center">
                                    <h3 class="kop-title">EKA SULISTYA, S.H., M.Kn.</h3>
                                    <div class="kop-subtitle">NOTARIS & PEJABAT PEMBUAT AKTA TANAH (PPAT)</div>
                                    <div class="kop-address">Jl. Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat 78116</div>
                                    <div class="kop-phone">Telepon: (0561) 7654321 | Email: eka.sulistya.notaris@gmail.com</div>
                                </div>
                            </div>
                            <hr class="kop-divider">
                        </div>
                        <div class="letter-body">
                            {!! $content !!}
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for Notary Paper */
    .notary-paper-container {
        width: 210mm; /* A4 width */
        min-height: 297mm; /* A4 height */
        background-color: #ffffff;
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
        box-sizing: border-box;
    }

    /* Absolute lines to simulate the double vertical red lines on Notary Paper */
    .notary-paper-container::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 3.5cm; /* left boundary red line */
        width: 3px;
        border-left: 1px solid #d9534f;
        border-right: 1px solid #d9534f;
        z-index: 10;
    }

    .notary-paper-body {
        padding: 3.8cm 2.2cm 3.8cm 4.0cm; /* Standard Notary Margins */
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 2.8; /* Professional double line spacing */
        color: #000000;
        text-align: justify;
        word-break: break-word;
    }

    .notary-paper-body p {
        margin: 0 0 1.5rem 0;
        text-indent: 0;
    }

    /* Styling for Corporate Letter */
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

    .kop-title {
        font-family: 'Times New Roman', Times, serif;
        font-weight: bold;
        font-size: 18pt;
        margin: 0;
        color: #4d0011;
        letter-spacing: 1px;
    }

    .kop-subtitle {
        font-family: 'Times New Roman', Times, serif;
        font-weight: bold;
        font-size: 10pt;
        margin: 2px 0 0;
        letter-spacing: 0.5px;
    }

    .kop-address, .kop-phone {
        font-family: 'Times New Roman', Times, serif;
        font-size: 8.5pt;
        color: #555;
        margin: 2px 0 0;
    }

    .kop-divider {
        border: none;
        border-top: 3px double #000;
        opacity: 1;
        margin-top: 15px;
        margin-bottom: 25px;
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

    /* Print Styles to allow distraction-free physical printing */
    @media print {
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
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            border: none;
            margin: 0;
            padding: 0;
        }
    }
</style>
@endsection
