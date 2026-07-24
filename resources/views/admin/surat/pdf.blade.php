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
    {!! $isi_surat !!}
</body>
</html>
