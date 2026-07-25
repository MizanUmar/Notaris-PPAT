<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Akta</title>

    <style>
        @page {
            margin: 3.8cm 2.2cm 3.8cm 4.0cm;
        }
        body {
            font-family: Courier, "Courier New", monospace;
            font-size: 14px;
            line-height: 2.8;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
        }

        .title {
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .number {
            font-size: 14px;
        }

        .isi {
            text-align: justify;
            word-spacing: 2px;
        }

        .footer {
            margin-top: 60px;
            font-size: 13px;
            line-height: 1.6;
        }

        .footer-note {
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
        }

        .signature-block {
            float: right;
            text-align: center;
            width: 320px;
            font-weight: bold;
        }

        .signature-space {
            height: 90px;
        }

        .clear {
            clear: both;
        }
    </style>

</head>

<body>

    <div class="isi">
        {!! $isi_akta !!}
    </div>

    <div class="footer">
        <div class="footer-note">-- DIBERIKAN SEBAGAI SALINAN YANG SAMA BUNYINYA --</div>
        
        <div class="signature-block">
            <p>NOTARIS KOTA PONTIANAK</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline;">EKA SULISTYA, S.H., M.Kn.</p>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>