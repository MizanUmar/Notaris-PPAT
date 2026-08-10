<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Akta Notaris - {{ $nomor_akta }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 3.5cm 2.2cm 3.5cm 3.8cm; /* Standard Indonesian Notary Minuta Margins */
        }
        body {
            font-family: Courier, "Courier New", monospace;
            font-size: 11pt;
            line-height: 2.2;
            color: #000000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            line-height: 1.4;
        }
        .title {
            font-size: 13pt;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .number {
            font-size: 11pt;
        }
        .isi {
            text-align: justify;
            text-justify: inter-word;
            word-break: break-word;
        }
        .isi p {
            margin-top: 0;
            margin-bottom: 1rem;
            text-indent: 0;
        }
        .footer {
            margin-top: 50px;
            font-size: 10pt;
            line-height: 1.5;
            page-break-inside: avoid;
        }
        .footer-note {
            text-align: center;
            margin-bottom: 35px;
            font-weight: bold;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signature-table td {
            border: none !important;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $nama_akta }}</div>
        <div class="number">Nomor : {{ $nomor_akta }}</div>
    </div>

    <div class="isi">
        {!! $isi_akta !!}
    </div>

    <div class="footer">
        <div class="footer-note">-- DIBERIKAN SEBAGAI SALINAN YANG SAMA BUNYINYA --</div>
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center;">
                    <p style="margin-bottom: 5px; font-weight: bold;">NOTARIS KOTA PONTIANAK</p>
                    <div style="height: 80px;"></div>
                    <p style="font-weight: bold; text-decoration: underline; margin: 0;">EKA SULISTYA, S.H., M.Kn.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>