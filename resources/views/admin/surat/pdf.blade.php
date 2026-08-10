<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $jenis_surat }} - {{ $nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 2.8cm 2.0cm 3.2cm 2.5cm; /* Top, Right, Bottom, Left */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000000;
        }
        
        /* Kop Surat Header */
        .kop-surat {
            text-align: center;
            border-bottom: 4px double #000000;
            padding-bottom: 8px;
            margin-bottom: 25px;
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.25;
        }
        .kop-logo {
            width: 65px;
            height: auto;
            display: block;
            margin: 0 auto 6px;
        }
        .kop-title-1 {
            font-weight: bold;
            font-size: 13pt;
            margin: 0;
            padding: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-title-2 {
            font-weight: bold;
            font-size: 15pt;
            margin: 2px 0 0;
            padding: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-sub {
            font-size: 8.5pt;
            font-weight: normal;
            margin: 2px 0 0;
            padding: 0;
            text-align: center;
        }
        
        /* Surat Body Styling */
        .letter-body {
            text-align: justify;
            text-justify: inter-word;
        }
        .letter-body p {
            margin-top: 0;
            margin-bottom: 0.8rem;
            line-height: 1.6;
        }
        .letter-body h3, .letter-body h4 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .letter-body table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 12px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
        }
        .letter-body td {
            padding: 3px 0;
            vertical-align: top;
            border: none !important;
        }
        .letter-body ol, .letter-body ul {
            margin-top: 0;
            margin-bottom: 12px;
            padding-left: 24px;
        }
        .letter-body li {
            margin-bottom: 6px;
            text-align: justify;
        }
        
        /* Signature & Avoid Page Break */
        .signature-table, .signature-container {
            page-break-inside: avoid !important;
            margin-top: 35px;
            width: 100%;
        }

        /* Fixed bottom footer for DomPDF */
        .footer {
            position: fixed;
            bottom: -2.6cm;
            left: 0;
            right: 0;
            height: 2.0cm;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 8.5pt;
            line-height: 1.4;
            border-top: 1px solid #000000;
            padding-top: 8px;
            color: #333333;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT / LETTERHEAD -->
    <div class="kop-surat">
        @if(file_exists(public_path('garuda_logo.png')))
            <img src="{{ public_path('garuda_logo.png') }}" class="kop-logo" alt="Logo Garuda">
        @endif
        <div class="kop-title-1">NOTARIS & PPAT</div>
        <div class="kop-title-2">EKA SULISTYA, S.H., M.Kn.</div>
        <div class="kop-sub">SK KEMENKUM RI Nomor : AHU-01601.AH.02.01.TAHUN 2025 Tanggal 04 Maret 2025</div>
        <div class="kop-sub">Kedudukan Kota Pontianak, Wilayah Kerja Provinsi Kalimantan Barat</div>
        <div class="kop-sub">SK Menteri ATR/KBPN RI Nomor 717/SK-HR.03.04.PPAT/VI/2025</div>
        <div class="kop-sub">Kedudukan Kota Pontianak</div>
    </div>

    <!-- CONTENT -->
    <div class="letter-body">
        {!! $isi_surat !!}
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div>Jl. Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat 78116</div>
        <div>e-mail : ekasulistyanotaris@gmail.com</div>
        <div>Hp : 085931148582</div>
    </div>
</body>
</html>
