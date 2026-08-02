<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <!-- Judul Dokumen PDF -->
    <title>Akta Notaris</title>

    <style>
        /* ========================================================
           ATURAN HALAMAN & MARGIN KHUSUS AKTA NOTARIS
           Standardisasi margin akta Notaris Indonesia (kiri lebih lebar untuk ruang jilid/minuta)
           ======================================================== */
        @page {
            margin: 3.8cm 2.2cm 3.8cm 4.0cm; /* Top, Right, Bottom, Left */
        }
        
        body {
            /* Font Courier / Courier New adalah font standar baku pembuatan Akta Notaris */
            font-family: Courier, "Courier New", monospace;
            font-size: 14px;
            /* Line height direnggangkan (spasi ganda) untuk penulisan akta resmi */
            line-height: 2.8;
            color: #000;
        }

        /* Seksi Judul Akta */
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

        /* Isi Teks Akta (Rata Kanan Kiri) */
        .isi {
            text-align: justify;
            word-spacing: 2px;
        }

        /* Bagian Kaki Dokumen */
        .footer {
            margin-top: 60px;
            font-size: 13px;
            line-height: 1.6;
        }

        /* Catatan Penutup Salinan Akta */
        .footer-note {
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
        }

        /* Blok Tanda Tangan Notaris di sebelah kanan */
        .signature-block {
            float: right;
            text-align: center;
            width: 320px;
            font-weight: bold;
        }

        /* Ruang Kosong untuk Tanda Tangan */
        .signature-space {
            height: 90px;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <!-- Bagian Judul dan Nomor Akta -->
    <div class="header">
        <div class="title">{{ $nama_akta }}</div>
        <div class="number">Nomor : {{ $nomor_akta }}</div>
    </div>

    <!-- Konten Utama Teks Akta (HTML) -->
    <div class="isi">
        {!! $isi_akta !!}
    </div>

    <!-- Bagian Penutup & Kolom Tanda Tangan Notaris -->
    <div class="footer">
        <div class="footer-note">-- DIBERIKAN SEBAGAI SALINAN YANG SAMA BUNYINYA --</div>
        
        <div class="signature-block">
            <p>NOTARIS KOTA PONTIANAK</p>
            <div class="signature-space"></div>
            <!-- Nama Terang Notaris -->
            <p style="text-decoration: underline;">EKA SULISTYA, S.H., M.Kn.</p>
        </div>
        <div class="clear"></div>
    </div>

</body>

</html>