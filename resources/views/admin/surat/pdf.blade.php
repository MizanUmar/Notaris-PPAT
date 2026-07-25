<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $jenis_surat }} - {{ $nomor_surat }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
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
    </style>
</head>
<body>
    <!-- KOP SURAT / LETTERHEAD -->
    <div style="text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px; font-family: 'Times New Roman', Times, serif;">
        <h2 style="font-weight: bold; font-size: 18pt; margin: 0; color: #4d0011; letter-spacing: 1px; text-align: center;">EKA SULISTYA, S.H., M.Kn.</h2>
        <div style="font-weight: bold; font-size: 10pt; margin: 2px 0 0; letter-spacing: 0.5px; text-align: center; text-transform: uppercase;">NOTARIS & PEJABAT PEMBUAT AKTA TANAH (PPAT)</div>
        <div style="font-size: 8.5pt; color: #555; margin: 2px 0 0; text-align: center;">Jl. Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat 78116</div>
        <div style="font-size: 8.5pt; color: #555; margin: 2px 0 0; text-align: center;">Telepon: (0561) 7654321 | Email: eka.sulistya.notaris@gmail.com</div>
    </div>

    {!! $isi_surat !!}
</body>
</html>
