@extends('layouts.app')

@section('title', 'Edit Surat - Notaris Eka Sulistya')

@section('content')
<div class="container-fluid py-4">

    <div class="card card-premium">

        <div class="card-header bg-white py-3">
            <h3 class="fw-bold mb-0">
                <i class="fa-solid fa-file-signature text-primary me-2"></i>
                Edit Surat Resmi
            </h3>
        </div>

        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Nama Client</label>
                    <div class="fw-semibold text-dark">{{ $permintaan->client->user->nama ?? '-' }}</div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Layanan</label>
                    <div class="fw-semibold text-primary">{{ $permintaan->layanan->nama_layanan ?? '-' }}</div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Tanggal Permintaan</label>
                    <div class="fw-semibold text-dark">{{ $permintaan->created_at->translatedFormat('d F Y') }}</div>
                </div>
            </div>

            <hr>

            <div class="row">
                <!-- Left Column: Dynamic Parameter Fields -->
                <div class="col-lg-4 border-end" style="max-height: 800px; overflow-y: auto; padding-right: 20px;">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-warning text-dark rounded-circle px-2 py-1 me-2 fw-bold small">1</span>
                        <h5 class="fw-bold mb-0 text-primary">Parameter Surat</h5>
                    </div>
                    <p class="text-muted small">Sesuaikan parameter di bawah ini jika ingin menyusun ulang draft surat. <strong>Isi surat di sebelah kanan sudah memuat versi yang tersimpan sebelumnya</strong>, jadi tombol ini hanya perlu ditekan bila Anda ingin mengganti seluruh isi.</p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary">Pilih Jenis Draft Surat</label>
                        <select id="select-jenis-draft" class="form-select form-select-sm border-primary">
                            <option value="kuasa">Surat Kuasa</option>
                            <option value="fisik">Surat Pernyataan Penguasaan Fisik Tanah Dan Tidak Sengketa</option>
                            <option value="batas">Surat Pernyataan Pemasangan Tanda-Tanda Batas</option>
                            <option value="default">Default / Surat Keterangan Umum</option>
                        </select>
                        <small class="text-muted d-block mt-1">Menerapkan template ini akan MENGGANTI isi surat di sebelah kanan.</small>
                    </div>

                    <div id="dynamic-fields-container">
                        <!-- Dynamic fields rendered by JS -->
                    </div>

                    <button type="button" id="btnApplyTemplate" class="btn btn-warning w-100 fw-bold mb-4 shadow-sm py-2">
                        <i class="fa fa-file-invoice me-1"></i> Terapkan ke Template Surat
                    </button>
                </div>

                <!-- Right Column: Standard Form -->
                <div class="col-lg-8 ps-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-primary text-white rounded-circle px-2 py-1 me-2 fw-bold small">2</span>
                        <h5 class="fw-bold mb-0 text-primary">Informasi & Isi Surat</h5>
                    </div>

                    <form method="POST" action="{{ route('admin.surat.update', $surat->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required>
                                    @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Jenis / Judul Surat</label>
                                    <input type="text" name="jenis_surat" class="form-control @error('jenis_surat') is-invalid @enderror" value="{{ old('jenis_surat', $surat->jenis_surat) }}" required>
                                    @error('jenis_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" value="{{ old('tanggal_surat', $surat->tanggal_surat->format('Y-m-d')) }}" required>
                            @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Ganti File Surat Digital (Kosongkan agar PDF dibuat ulang otomatis dari Isi Surat di bawah)</label>
                            <input type="file" name="file_surat" class="form-control @error('file_surat') is-invalid @enderror">
                            @error('file_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">Isi Surat</label>
                            <textarea id="editor" name="isi_surat" class="@error('isi_surat') is-invalid @enderror">{{ old('isi_surat', $surat->keterangan) }}</textarea>
                            @error('isi_surat')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.surat.index') }}" class="btn btn-light border fw-semibold">
                                <i class="fa fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="fa fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    const layananName = "{{ $permintaan->layanan->nama_layanan ?? '' }}";

    // 1. SURAT KUASA Template
    window.templateKuasa = `<div style="text-align: center; margin-bottom: 20px;">
  <p style="margin: 0; padding: 0; font-size: 14pt; font-family: 'Times New Roman', Times, serif;"><strong><u>SURAT KUASA</u></strong></p>
  <p style="margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif;">Nomor : [NOMOR_SURAT]</p>
</div>
<br>
<p>Yang bertanda tangan di bawah ini :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 25%; border: none; padding: 2px 0;">Nama</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;"><strong>[NAMA_PEMBERI]</strong></td></tr>
  <tr><td style="border: none; padding: 2px 0;">Pekerjaan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[PEKERJAAN_PEMBERI]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Alamat</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[ALAMAT_PEMBERI]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">NIK</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[NIK_PEMBERI]</td></tr>
</table>
<p>Selaku Pemberi Kuasa untuk selanjutnya disebut : ------------------------------------------------------------- <strong>PIHAK PERTAMA</strong> -------------------------------------------------------------</p>
<p>Dengan ini memberi kuasa kepada :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 25%; border: none; padding: 2px 0;">Nama</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;"><strong>EKA SULISTYA, S.H., M.Kn.</strong></td></tr>
  <tr><td style="border: none; padding: 2px 0;">Pekerjaan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">Notaris & PPAT</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Alamat Kantor</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">Jl. Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat 78116.</td></tr>
</table>
<p>Selaku Penerima Kuasa untuk selanjutnya disebut : ------------------------------------------------------------- <strong>PIHAK KEDUA</strong> -------------------------------------------------------------</p>
<p style="text-align: center; font-weight: bold; margin: 15px 0;">------------------------------------------------------------- KHUSUS -------------------------------------------------------------</p>
<p>Untuk dan atas nama Pihak Pertama untuk menghadap di Kantor Pertanahan Kota Pontianak untuk mengurus Alih Media dan melakukan segala sesuatu berkaitan dengan pengurusan tersebut serta menerima sertifikatnya dari Kantor Pertanahan atas sebidang tanah yang terletak di :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 30%; border: none; padding: 2px 0;">Kelurahan / Desa</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[DESA_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Kecamatan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[KECAMATAN_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Kota</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[KOTA_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Provinsi</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[PROVINSI_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Status Hak</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[STATUS_HAK]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Nomor Sertifikat</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[NOMOR_SERTIFIKAT]</td></tr>
</table>
<br>
<p>Demikianlah Surat Kuasa ini dibuat dengan sebenar-benarnya dan dapat dipergunakan sebagaimana mestinya.</p>
<br>
<table style="width: 100%; border: none; margin-top: 40px;">
  <tr>
    <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
      Pihak Pertama<br><br><br><br><br><strong>( [NAMA_PEMBERI] )</strong>
    </td>
    <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
      Pontianak, [TANGGAL_SURAT_LENGKAP]<br>Pihak Kedua<br><br><br><br><br><strong>( EKA SULISTYA, S.H., M.Kn. )</strong>
    </td>
  </tr>
</table>`;

    // 2. SURAT PERNYATAAN PENGUASAAN FISIK Template
    window.templateFisik = `<div style="text-align: center; margin-bottom: 20px;">
  <p style="margin: 0; padding: 0; font-size: 14pt; font-family: 'Times New Roman', Times, serif;"><strong><u>SURAT PERNYATAAN</u></strong></p>
  <p style="margin: 0; padding: 0; font-size: 12pt; font-family: 'Times New Roman', Times, serif;"><strong><u>PENGUASAAN FISIK TANAH DAN TIDAK SENGKETA</u></strong></p>
</div>
<br>
<p>Yang bertanda tangan dibawah ini :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 30%; border: none; padding: 2px 0;">Nama</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;"><strong>[NAMA_PERNYATA]</strong></td></tr>
  <tr><td style="border: none; padding: 2px 0;">Tempat, tanggal lahir</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[TTL_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Pekerjaan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[PEKERJAAN_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">No. KTP</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[NIK_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Alamat</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[ALAMAT_PERNYATA]</td></tr>
</table>
<br>
<p>Dengan disaksikan oleh :</p>
<p><strong>1) Nama : [NAMA_SAKSI_1]</strong></p>
<table style="width: 100%; border: none; margin-left: 20px;">
  <tr><td style="width: 25%; border: none; padding: 1px 0;">Tempat, tanggal lahir</td><td style="width: 2%; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[TTL_SAKSI_1]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">Pekerjaan</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[PEKERJAAN_SAKSI_1]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">No. KTP</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[NIK_SAKSI_1]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">Alamat</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[ALAMAT_SAKSI_1]</td></tr>
</table>
<p><strong>2) Nama : [NAMA_SAKSI_2]</strong></p>
<table style="width: 100%; border: none; margin-left: 20px;">
  <tr><td style="width: 25%; border: none; padding: 1px 0;">Tempat, tanggal lahir</td><td style="width: 2%; border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[TTL_SAKSI_2]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">Pekerjaan</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[PEKERJAAN_SAKSI_2]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">No. KTP</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[NIK_SAKSI_2]</td></tr>
  <tr><td style="border: none; padding: 1px 0;">Alamat</td><td style="border: none; padding: 1px 0;">:</td><td style="border: none; padding: 1px 0;">[ALAMAT_SAKSI_2]</td></tr>
</table>
<br>
<p>Pada hari ini [HARI_TANGGAL_PERNYATAAN] dengan ini menyatakan :</p>
<ol>
  <li>Bahwa <strong>[NAMA_PERNYATA]</strong> adalah benar-benar sebagai pemilik sebidang Tanah seluas [LUAS_TANAH] yang terletak di [LOKASI_TANAH], yang sudah bersertifikat Hak Milik No. [NOMOR_SERTIFIKAT] tanggal [TANGGAL_SERTIFIKAT], yang tercatat atas nama <strong>[NAMA_PERNYATA]</strong>, dan merupakan produk Kantor Pertanahan [KOTA_KANTOR];</li>
  <li>Bahwa sebagai pemilik tanah tersebut diatas, <strong>[NAMA_PERNYATA]</strong> benar-benar menguasai tanah secara fisik sejak tahun [TAHUN_MULAI] sampai sekarang dan saya pergunakan untuk [PERUNTUKAN_TANAH];</li>
  <li>Bahwa tanah saya tersebut tidak dalam keadaan Sengketa, Konflik, Perkara, Sita Jaminan atau Pemblokiran dari pihak lain.</li>
</ol>
<p>Demikian Surat Pernyataan ini saya buat dengan sebenarnya dan saya bertanggung jawab sepenuhnya atas Pernyataan ini. Apabila diperlukan saya sanggup mengangkat sumpah menurut Agama/Kepercayaan saya. Apabila di kemudian hari terjadi perkara perdata maupun pidana atas tanah ini, maka perkara tersebut merupakan tanggung jawab pribadi saya dengan tidak melibatkan Pejabat/Kantor Pertanahan [KOTA_KANTOR].</p>
<br>
<table style="width: 100%; border: none; margin-top: 40px;">
  <tr>
    <td style="width: 50%; text-align: left; border: none; padding-left: 20px; vertical-align: top;">
      Saksi-saksi :<br><br>
      1. <strong>[NAMA_SAKSI_1]</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (........................)<br><br>
      2. <strong>[NAMA_SAKSI_2]</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (........................)
    </td>
    <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
      Yang membuat pernyataan<br><br><br><br><br>
      <strong>( [NAMA_PERNYATA] )</strong>
    </td>
  </tr>
</table>`;

    // 3. SURAT PERNYATAAN PEMASANGAN BATAS Template
    window.templateBatas = `<div style="text-align: center; margin-bottom: 20px;">
  <p style="margin: 0; padding: 0; font-size: 14pt; font-family: 'Times New Roman', Times, serif;"><strong><u>SURAT PERNYATAAN</u></strong></p>
  <p style="margin: 0; padding: 0; font-size: 12pt; font-family: 'Times New Roman', Times, serif;"><strong><u>PEMASANGAN TANDA-TANDA BATAS</u></strong></p>
</div>
<br>
<p>Yang bertanda tangan dibawah ini :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 25%; border: none; padding: 2px 0;">Nama</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;"><strong>[NAMA_PERNYATA]</strong></td></tr>
  <tr><td style="border: none; padding: 2px 0;">Tempat, tanggal lahir</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[TTL_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Pekerjaan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[PEKERJAAN_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">No. KTP</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[NIK_PERNYATA]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Alamat</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[ALAMAT_PERNYATA]</td></tr>
</table>
<br>
<p>Selaku kuasa pemohon Pengukuran dan pemilik tanah yang terletak di :</p>
<table style="width: 100%; border: none; margin-left: 20px;">
  <tr><td style="width: 20%; border: none; padding: 2px 0;">Jalan</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[JALAN_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Desa / Kelurahan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[DESA_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Kecamatan</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[KECAMATAN_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Kota / Kabupaten</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[KOTA_TANAH]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Luas</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[LUAS_TANAH]</td></tr>
</table>
<br>
<p>Dengan ini menyatakan bahwa :</p>
<ol>
  <li>Bidang-bidang tanah tersebut telah dipasang tanda-tanda batasnya sebanyak 4 buah dan telah memperoleh persetujuan dari pemilik tanah yang berbatasan.</li>
  <li>Tanda-tanda batas tersebut terbuat dari [BAHAN_BATAS].*</li>
  <li>Bidang tanah tersebut sampai saat ini belum dijadikan jaminan hutang atau diperjualbelikan juga tidak dalam keadaan sengketa dan bukan tanah milik orang lain.</li>
</ol>
<br>
<table style="width: 100%; border: none; margin-top: 40px;">
  <tr>
    <td style="width: 55%; border: none;"></td>
    <td style="width: 45%; text-align: center; border: none; vertical-align: top;">
      Pontianak, [TANGGAL_SURAT_LENGKAP]<br>Yang membuat pernyataan<br><br><br><br><br>
      <strong>( [NAMA_PERNYATA] )</strong>
    </td>
  </tr>
</table>`;

    // 4. DEFAULT Template
    window.templateDefault = `<div style="text-align: center; margin-bottom: 20px;">
  <p style="margin: 0; padding: 0; font-size: 14pt; font-family: 'Times New Roman', Times, serif;"><strong><u>SURAT KETERANGAN</u></strong></p>
  <p style="margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif;">Nomor : [NOMOR_SURAT]</p>
</div>
<br>
<p>Yang bertanda tangan di bawah ini Notaris & PPAT Eka Sulistya, S.H., M.Kn. menerangkan bahwa :</p>
<table style="width: 100%; border: none;">
  <tr><td style="width: 25%; border: none; padding: 2px 0;">Nama</td><td style="width: 2%; border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;"><strong>[NAMA_PIHAK]</strong></td></tr>
  <tr><td style="border: none; padding: 2px 0;">NIK</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[NIK_PIHAK]</td></tr>
  <tr><td style="border: none; padding: 2px 0;">Alamat</td><td style="border: none; padding: 2px 0;">:</td><td style="border: none; padding: 2px 0;">[ALAMAT_PIHAK]</td></tr>
</table>
<br>
<p>Adalah benar-benar telah mendaftarkan permohonan berkas hukum pada kantor kami dengan keterangan sebagai berikut :</p>
<p style="margin-left: 20px; font-style: italic;">[KETERANGAN_SURAT]</p>
<br>
<p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat digunakan secara sah.</p>
<br>
<table style="width: 100%; border: none; margin-top: 40px;">
  <tr>
    <td style="width: 55%; border: none;"></td>
    <td style="width: 45%; text-align: center; border: none; vertical-align: top;">
      Pontianak, [TANGGAL_SURAT_LENGKAP]<br>Notaris / PPAT<br><br><br><br><br>
      <strong>EKA SULISTYA, S.H., M.Kn.</strong>
    </td>
  </tr>
</table>`;

    // Function to render fields dynamically based on selected draft
    function renderFields() {
        const container = document.getElementById('dynamic-fields-container');
        const selectedDraft = document.getElementById('select-jenis-draft').value;

        let html = '';

        if (selectedDraft === 'kuasa') {
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Surat</label>
                    <input type="text" id="param_nomor_surat" class="form-control form-control-sm" value="01/SKR/NOT/ES/VIII/2025">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Terbit</label>
                    <input type="text" id="param_tanggal_lengkap" class="form-control form-control-sm" value="04 Agustus 2025">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pemberi Kuasa (Pihak 1)</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Pemberi Kuasa</label>
                    <input type="text" id="param_nama_pemberi" class="form-control form-control-sm" value="SUWARTONO">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Pekerjaan</label>
                    <input type="text" id="param_pekerjaan_pemberi" class="form-control form-control-sm" value="Pensiunan">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pemberi" class="form-control form-control-sm" rows="2">Jl. P.Natakusuma, Kota Pontianak</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pemberi" class="form-control form-control-sm" value="6171052303550001">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Objek Tanah</h6>
                <div class="mb-2">
                    <label class="small text-muted">Desa / Kelurahan</label>
                    <input type="text" id="param_desa_tanah" class="form-control form-control-sm" value="Paal Lima">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Kecamatan</label>
                    <input type="text" id="param_kecamatan_tanah" class="form-control form-control-sm" value="Pontianak Barat">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Kota</label>
                    <input type="text" id="param_kota_tanah" class="form-control form-control-sm" value="Pontianak">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Provinsi</label>
                    <input type="text" id="param_provinsi_tanah" class="form-control form-control-sm" value="Kalimantan Barat">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Status Hak</label>
                    <input type="text" id="param_status_hak" class="form-control form-control-sm" value="Milik">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Nomor Sertifikat</label>
                    <input type="text" id="param_nomor_sertifikat" class="form-control form-control-sm" value="1999/Paal Lima">
                </div>
            `;
        } else if (selectedDraft === 'fisik') {
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari, Tanggal Pernyataan</label>
                    <input type="text" id="param_hari_tanggal_pernyataan" class="form-control form-control-sm" value="Kamis tanggal 02 Oktober 2025">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tanggal TTD (Lengkap)</label>
                    <input type="text" id="param_tanggal_lengkap" class="form-control form-control-sm" value="02 Oktober 2025">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pembuat Pernyataan</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pernyata" class="form-control form-control-sm" value="SUWARTONO">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Tempat, Tanggal Lahir</label>
                    <input type="text" id="param_ttl_pernyata" class="form-control form-control-sm" value="Pontianak, 23-03-1955">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Pekerjaan</label>
                    <input type="text" id="param_pekerjaan_pernyata" class="form-control form-control-sm" value="Pensiunan">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">NIK / KTP</label>
                    <input type="text" id="param_nik_pernyata" class="form-control form-control-sm" value="6171052303550001">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pernyata" class="form-control form-control-sm" rows="2">Jl. P.Natakusuma Gg. Rencana No. 8, Sungai Bangkong, Pontianak Kota</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi 1</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Saksi 1</label>
                    <input type="text" id="param_nama_saksi_1" class="form-control form-control-sm" value="RAHMAD SETIAWAN">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL Saksi 1</label>
                    <input type="text" id="param_ttl_saksi_1" class="form-control form-control-sm" value="Pontianak, 27-09-2001">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Pekerjaan</label>
                    <input type="text" id="param_pekerjaan_saksi_1" class="form-control form-control-sm" value="Buruh">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">NIK Saksi 1</label>
                    <input type="text" id="param_nik_saksi_1" class="form-control form-control-sm" value="6171052709010002">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Alamat Saksi 1</label>
                    <textarea id="param_alamat_saksi_1" class="form-control form-control-sm" rows="2">Jl. P.Natakusuma Gg. Rukun No. 31, Sungai Bangkong, Pontianak Kota</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi 2</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Saksi 2</label>
                    <input type="text" id="param_nama_saksi_2" class="form-control form-control-sm" value="DWI RISKI MONICA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL Saksi 2</label>
                    <input type="text" id="param_ttl_saksi_2" class="form-control form-control-sm" value="Pontianak, 19-07-2000">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Pekerjaan</label>
                    <input type="text" id="param_pekerjaan_saksi_2" class="form-control form-control-sm" value="Mengurus Rumah Tangga">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">NIK Saksi 2</label>
                    <input type="text" id="param_nik_saksi_2" class="form-control form-control-sm" value="6171045907000002">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Alamat Saksi 2</label>
                    <textarea id="param_alamat_saksi_2" class="form-control form-control-sm" rows="2">Jl. P.Natakusuma Gg. Rukun No. 31, Sungai Bangkong, Pontianak Kota</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Objek Tanah</h6>
                <div class="mb-2">
                    <label class="small text-muted">Luas Tanah</label>
                    <input type="text" id="param_luas_tanah" class="form-control form-control-sm" value="300m2">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Lokasi Objek</label>
                    <textarea id="param_lokasi_tanah" class="form-control form-control-sm" rows="2">Jalan Nipah Kuning Dalam, Kelurahan Sungai Beliung, Kecamatan Pontianak Barat, Kota Pontianak</textarea>
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Nomor Sertifikat</label>
                    <input type="text" id="param_nomor_sertifikat" class="form-control form-control-sm" value="11397/2021">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Tanggal Sertifikat</label>
                    <input type="text" id="param_tanggal_sertifikat" class="form-control form-control-sm" value="01-08-2021">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Kantor Pertanahan</label>
                    <input type="text" id="param_kota_kantor" class="form-control form-control-sm" value="Kota Pontianak">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Tahun Mulai Penguasaan</label>
                    <input type="text" id="param_tahun_mulai" class="form-control form-control-sm" value="1982">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Peruntukan Tanah</label>
                    <input type="text" id="param_peruntukan_tanah" class="form-control form-control-sm" value="pertanian">
                </div>
            `;
        } else if (selectedDraft === 'batas') {
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tanggal TTD (Lengkap)</label>
                    <input type="text" id="param_tanggal_lengkap" class="form-control form-control-sm" value="02 Oktober 2025">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pembuat Pernyataan</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pernyata" class="form-control form-control-sm" value="EKA SULISTYA, S.H., M.Kn.">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Tempat, Tanggal Lahir</label>
                    <input type="text" id="param_ttl_pernyata" class="form-control form-control-sm" value="Pontianak, 23-03-1955">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Pekerjaan</label>
                    <input type="text" id="param_pekerjaan_pernyata" class="form-control form-control-sm" value="PPAT/Notaris">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">NIK / KTP</label>
                    <input type="text" id="param_nik_pernyata" class="form-control form-control-sm" value="3578034411850005">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pernyata" class="form-control form-control-sm" rows="2">Jl. P.Natakusuma, Sungai Bangkong, Pontianak Kota</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Objek Tanah</h6>
                <div class="mb-2">
                    <label class="small text-muted">Jalan</label>
                    <input type="text" id="param_jalan_tanah" class="form-control form-control-sm" value="Nipah Kuning Dalam">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Desa / Kelurahan</label>
                    <input type="text" id="param_desa_tanah" class="form-control form-control-sm" value="Sungai Beliung">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Kecamatan</label>
                    <input type="text" id="param_kecamatan_tanah" class="form-control form-control-sm" value="Pontianak Barat">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Kota / Kabupaten</label>
                    <input type="text" id="param_kota_tanah" class="form-control form-control-sm" value="Pontianak">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Luas Tanah</label>
                    <input type="text" id="param_luas_tanah" class="form-control form-control-sm" value="300m2">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Bahan Batas</label>
                    <input type="text" id="param_bahan_batas" class="form-control form-control-sm" value="Kayu Belian/Beton/Paralon/Besi/Kayu">
                </div>
            `;
        } else {
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Surat</label>
                    <input type="text" id="param_nomor_surat" class="form-control form-control-sm" value="01/SKR/NOT/ES/VIII/2025">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tanggal TTD (Lengkap)</label>
                    <input type="text" id="param_tanggal_lengkap" class="form-control form-control-sm" value="04 Agustus 2025">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Identitas Pihak</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak" class="form-control form-control-sm" value="3217091002920015">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Keterangan Tambahan</label>
                    <textarea id="param_keterangan_surat" class="form-control form-control-sm" rows="3">Telah memenuhi semua prasyarat berkas dan dokumen pendukung hukum.</textarea>
                </div>
            `;
        }

        container.innerHTML = html;
    }

    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [
                'heading',
                '|',
                'bold',
                'italic',
                'underline',
                '|',
                'bulletedList',
                'numberedList',
                '|',
                'insertTable',
                '|',
                'undo',
                'redo'
            ]
        })
        .then(editor => {
            window.editor = editor;

            editor.editing.view.change(writer => {
                writer.setStyle(
                    'min-height',
                    '500px',
                    editor.editing.view.document.getRoot()
                );
            });

            // Auto-select draft type based on request's service name
            // (hanya untuk isi field kiri sebagai default, TIDAK mengubah isi editor)
            const selectDraft = document.getElementById('select-jenis-draft');
            const lowLayanan = layananName.toLowerCase();

            if (lowLayanan.includes('kuasa')) {
                selectDraft.value = 'kuasa';
            } else if (lowLayanan.includes('penguasaan') || lowLayanan.includes('fisik') || lowLayanan.includes('sengketa')) {
                selectDraft.value = 'fisik';
            } else if (lowLayanan.includes('batas') || lowLayanan.includes('pemasangan')) {
                selectDraft.value = 'batas';
            } else {
                selectDraft.value = 'default';
            }

            // Render fields kiri berdasarkan pilihan di atas
            renderFields();

            // PENTING: Berbeda dari halaman Create, di sini kita TIDAK
            // memanggil compileLetter() secara otomatis saat halaman dibuka,
            // karena editor sudah terisi isi_surat (keterangan) yang tersimpan
            // sebelumnya dan kita tidak ingin menimpanya tanpa sepengetahuan user.

            function compileLetter() {
                const selectedDraft = document.getElementById('select-jenis-draft').value;
                let template = '';
                let nomor = '';
                let jenis = '';

                const tglLengkap = document.getElementById('param_tanggal_lengkap').value;

                if (selectedDraft === 'kuasa') {
                    template = window.templateKuasa;
                    nomor = document.getElementById('param_nomor_surat').value;
                    jenis = "Surat Kuasa";

                    const nama = document.getElementById('param_nama_pemberi').value;
                    const pekerjaan = document.getElementById('param_pekerjaan_pemberi').value;
                    const alamat = document.getElementById('param_alamat_pemberi').value;
                    const nik = document.getElementById('param_nik_pemberi').value;

                    const desa = document.getElementById('param_desa_tanah').value;
                    const kec = document.getElementById('param_kecamatan_tanah').value;
                    const kota = document.getElementById('param_kota_tanah').value;
                    const prov = document.getElementById('param_provinsi_tanah').value;
                    const status = document.getElementById('param_status_hak').value;
                    const sertifikat = document.getElementById('param_nomor_sertifikat').value;

                    template = template.replaceAll('[NOMOR_SURAT]', nomor);
                    template = template.replaceAll('[TANGGAL_SURAT_LENGKAP]', tglLengkap);
                    template = template.replaceAll('[NAMA_PEMBERI]', nama);
                    template = template.replaceAll('[PEKERJAAN_PEMBERI]', pekerjaan);
                    template = template.replaceAll('[ALAMAT_PEMBERI]', alamat);
                    template = template.replaceAll('[NIK_PEMBERI]', nik);

                    template = template.replaceAll('[DESA_TANAH]', desa);
                    template = template.replaceAll('[KECAMATAN_TANAH]', kec);
                    template = template.replaceAll('[KOTA_TANAH]', kota);
                    template = template.replaceAll('[PROVINSI_TANAH]', prov);
                    template = template.replaceAll('[STATUS_HAK]', status);
                    template = template.replaceAll('[NOMOR_SERTIFIKAT]', sertifikat);

                } else if (selectedDraft === 'fisik') {
                    template = window.templateFisik;
                    jenis = "Surat Pernyataan Penguasaan Fisik Tanah";
                    nomor = "SP/" + document.getElementById('param_nama_pernyata').value.replace(/\s+/g, '_') + "/2025";

                    const hariTgl = document.getElementById('param_hari_tanggal_pernyataan').value;
                    const nama = document.getElementById('param_nama_pernyata').value;
                    const ttl = document.getElementById('param_ttl_pernyata').value;
                    const pekerjaan = document.getElementById('param_pekerjaan_pernyata').value;
                    const nik = document.getElementById('param_nik_pernyata').value;
                    const alamat = document.getElementById('param_alamat_pernyata').value;

                    const namaS1 = document.getElementById('param_nama_saksi_1').value;
                    const ttlS1 = document.getElementById('param_ttl_saksi_1').value;
                    const pekS1 = document.getElementById('param_pekerjaan_saksi_1').value;
                    const nikS1 = document.getElementById('param_nik_saksi_1').value;
                    const almtS1 = document.getElementById('param_alamat_saksi_1').value;

                    const namaS2 = document.getElementById('param_nama_saksi_2').value;
                    const ttlS2 = document.getElementById('param_ttl_saksi_2').value;
                    const pekS2 = document.getElementById('param_pekerjaan_saksi_2').value;
                    const nikS2 = document.getElementById('param_nik_saksi_2').value;
                    const almtS2 = document.getElementById('param_alamat_saksi_2').value;

                    const luas = document.getElementById('param_luas_tanah').value;
                    const lokasi = document.getElementById('param_lokasi_tanah').value;
                    const sertifikat = document.getElementById('param_nomor_sertifikat').value;
                    const tglSertifikat = document.getElementById('param_tanggal_sertifikat').value;
                    const kotaKantor = document.getElementById('param_kota_kantor').value;
                    const tahun = document.getElementById('param_tahun_mulai').value;
                    const peruntukan = document.getElementById('param_peruntukan_tanah').value;

                    template = template.replaceAll('[HARI_TANGGAL_PERNYATAAN]', hariTgl);
                    template = template.replaceAll('[TANGGAL_SURAT_LENGKAP]', tglLengkap);
                    template = template.replaceAll('[NAMA_PERNYATA]', nama);
                    template = template.replaceAll('[TTL_PERNYATA]', ttl);
                    template = template.replaceAll('[PEKERJAAN_PERNYATA]', pekerjaan);
                    template = template.replaceAll('[NIK_PERNYATA]', nik);
                    template = template.replaceAll('[ALAMAT_PERNYATA]', alamat);

                    template = template.replaceAll('[NAMA_SAKSI_1]', namaS1);
                    template = template.replaceAll('[TTL_SAKSI_1]', ttlS1);
                    template = template.replaceAll('[PEKERJAAN_SAKSI_1]', pekS1);
                    template = template.replaceAll('[NIK_SAKSI_1]', nikS1);
                    template = template.replaceAll('[ALAMAT_SAKSI_1]', almtS1);

                    template = template.replaceAll('[NAMA_SAKSI_2]', namaS2);
                    template = template.replaceAll('[TTL_SAKSI_2]', ttlS2);
                    template = template.replaceAll('[PEKERJAAN_SAKSI_2]', pekS2);
                    template = template.replaceAll('[NIK_SAKSI_2]', nikS2);
                    template = template.replaceAll('[ALAMAT_SAKSI_2]', almtS2);

                    template = template.replaceAll('[LUAS_TANAH]', luas);
                    template = template.replaceAll('[LOKASI_TANAH]', lokasi);
                    template = template.replaceAll('[NOMOR_SERTIFIKAT]', sertifikat);
                    template = template.replaceAll('[TANGGAL_SERTIFIKAT]', tglSertifikat);
                    template = template.replaceAll('[KOTA_KANTOR]', kotaKantor);
                    template = template.replaceAll('[TAHUN_MULAI]', tahun);
                    template = template.replaceAll('[PERUNTUKAN_TANAH]', peruntukan);

                } else if (selectedDraft === 'batas') {
                    template = window.templateBatas;
                    jenis = "Surat Pernyataan Pemasangan Batas";
                    const nama = document.getElementById('param_nama_pernyata').value;
                    nomor = "SPB/" + nama.replace(/\s+/g, '_') + "/2025";

                    const ttl = document.getElementById('param_ttl_pernyata').value;
                    const pekerjaan = document.getElementById('param_pekerjaan_pernyata').value;
                    const nik = document.getElementById('param_nik_pernyata').value;
                    const alamat = document.getElementById('param_alamat_pernyata').value;

                    const jalan = document.getElementById('param_jalan_tanah').value;
                    const desa = document.getElementById('param_desa_tanah').value;
                    const kec = document.getElementById('param_kecamatan_tanah').value;
                    const kota = document.getElementById('param_kota_tanah').value;
                    const luas = document.getElementById('param_luas_tanah').value;
                    const bahan = document.getElementById('param_bahan_batas').value;

                    template = template.replaceAll('[TANGGAL_SURAT_LENGKAP]', tglLengkap);
                    template = template.replaceAll('[NAMA_PERNYATA]', nama);
                    template = template.replaceAll('[TTL_PERNYATA]', ttl);
                    template = template.replaceAll('[PEKERJAAN_PERNYATA]', pekerjaan);
                    template = template.replaceAll('[NIK_PERNYATA]', nik);
                    template = template.replaceAll('[ALAMAT_PERNYATA]', alamat);

                    template = template.replaceAll('[JALAN_TANAH]', jalan);
                    template = template.replaceAll('[DESA_TANAH]', desa);
                    template = template.replaceAll('[KECAMATAN_TANAH]', kec);
                    template = template.replaceAll('[KOTA_TANAH]', kota);
                    template = template.replaceAll('[LUAS_TANAH]', luas);
                    template = template.replaceAll('[BAHAN_BATAS]', bahan);

                } else {
                    template = window.templateDefault;
                    nomor = document.getElementById('param_nomor_surat').value;
                    jenis = "Surat Keterangan";

                    const nama = document.getElementById('param_nama_pihak').value;
                    const nik = document.getElementById('param_nik_pihak').value;
                    const alamat = document.getElementById('param_alamat_pihak').value;
                    const ket = document.getElementById('param_keterangan_surat').value;

                    template = template.replaceAll('[NOMOR_SURAT]', nomor);
                    template = template.replaceAll('[TANGGAL_SURAT_LENGKAP]', tglLengkap);
                    template = template.replaceAll('[NAMA_PIHAK]', nama);
                    template = template.replaceAll('[NIK_PIHAK]', nik);
                    template = template.replaceAll('[ALAMAT_PIHAK]', alamat);
                    template = template.replaceAll('[KETERANGAN_SURAT]', ket);
                }

                // Set editor data -- ini yang menimpa isi surat lama
                window.editor.setData(template);

                document.querySelector('input[name="nomor_surat"]').value = nomor;
                document.querySelector('input[name="jenis_surat"]').value = jenis;
            }

            // Bind change event to dropdown -- hanya update field kiri
            // Prefill otomatis saat halaman dibuka (sama seperti halaman Create)
            compileLetter();

            // Bind change event to dropdown -- otomatis terapkan ulang template juga
            selectDraft.addEventListener('change', function() {
                renderFields();
            });

            // Bind apply button
            document.getElementById('btnApplyTemplate').addEventListener('click', compileLetter);

        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection