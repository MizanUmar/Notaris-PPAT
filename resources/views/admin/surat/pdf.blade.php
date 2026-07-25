<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $jenis_surat }} - {{ $nomor_surat }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm 3.5cm 2.5cm; /* Bottom margin leaves room for the fixed footer */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        h3, h4 {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        p {
            margin-top: 0;
            margin-bottom: 12px;
            text-align: justify;
            text-indent: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
            border: none !important;
        }
        ol, ul {
            margin-top: 0;
            margin-bottom: 12px;
            padding-left: 25px;
        }
        li {
            margin-bottom: 6px;
            text-align: justify;
        }
        /* Fixed bottom footer style for DomPDF */
        .footer {
            position: fixed;
            bottom: -2.8cm;
            left: 0;
            right: 0;
            height: 2.2cm;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            line-height: 1.4;
            border-top: 1px solid #000;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT / LETTERHEAD -->
    <div style="text-align: center; border-bottom: 4px solid #000; padding-bottom: 5px; margin-bottom: 25px; font-family: 'Times New Roman', Times, serif; line-height: 1.25;">
        <img src="{{ public_path('garuda_logo.png') }}" style="width: 65px; height: auto; display: block; margin: 0 auto 5px;" alt="Logo Garuda">
        <h2 style="font-weight: bold; font-size: 14pt; margin: 0; padding: 0; letter-spacing: 0.5px; text-align: center; text-transform: uppercase;">NOTARIS & PPAT</h2>
        <h2 style="font-weight: bold; font-size: 16pt; margin: 2px 0 0; padding: 0; letter-spacing: 0.5px; text-align: center; text-transform: uppercase;">EKA SULISTYA, S.H., M.Kn.</h2>
        <div style="font-size: 8.5pt; font-weight: normal; margin: 4px 0 0; padding: 0; text-align: center;">SK KEMENKUM RI Nomor : AHU-01601.AH.02.01.TAHUN 2025 Tanggal 04 Maret 2025</div>
        <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">Kedudukan Kota Pontianak, Wilayah Kerja Provinsi Kalimantan Barat</div>
        <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">SK Menteri ATR/KBPN RI Nomor 717/SK-HR.03.04.PPAT/VI/2025</div>
        <div style="font-size: 8.5pt; font-weight: normal; margin: 2px 0 0; padding: 0; text-align: center;">Kedudukan Kota Pontianak</div>
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
