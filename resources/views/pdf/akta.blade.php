<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>
        body {

            font-family: DejaVu Sans;

            font-size: 14px;

            line-height: 1.8;

        }

        h2 {

            text-align: center;

            margin-bottom: 40px;

        }

        table {

            width: 100%;

            margin-bottom: 30px;

        }

        td {

            padding: 4px;

            vertical-align: top;

        }

        .isi {

            margin-top: 30px;

            text-align: justify;

        }

        .footer {

            margin-top: 80px;

            text-align: right;

        }
    </style>

</head>

<body>

    <h2>{{ $akta->nama_akta }}</h2>

    <table>

        <tr>

            <td width="180">Nomor Akta</td>

            <td>: {{ $akta->nomor_akta }}</td>

        </tr>

        <tr>

            <td>Tanggal</td>

            <td>: {{ \Carbon\Carbon::parse($akta->tanggal_akta)->translatedFormat('d F Y') }}</td>

        </tr>

        <tr>

            <td>Nama Client</td>

            <td>: {{ $akta->permintaan->client->user->nama }}</td>

        </tr>

        <tr>

            <td>Layanan</td>

            <td>: {{ $akta->permintaan->layanan->nama_layanan }}</td>

        </tr>

    </table>

    <div class="isi">

        {!! $akta->isi_akta !!}

    </div>

    <div class="footer">

        <p>

            Pontianak,

            {{ \Carbon\Carbon::parse($akta->tanggal_akta)->translatedFormat('d F Y') }}

        </p>

        <br><br><br>

        <b>Eka Sulistya, S.H.</b>

    </div>

</body>

</html>