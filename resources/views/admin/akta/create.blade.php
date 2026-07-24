@extends('layouts.app')

@section('title', 'Buat Akta - Notaris Eka Sulistya')

@section('content')
<div class="container-fluid py-4">

    <div class="card card-premium">

        <div class="card-header bg-white py-3">
            <h3 class="fw-bold mb-0">
                <i class="fa fa-file-signature text-primary me-2"></i>
                Buat Akta Baru
            </h3>
        </div>

        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Nama Client</label>
                    <div class="fw-semibold text-dark">{{ $permintaan->client->user->nama }}</div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small">Layanan</label>
                    <div class="fw-semibold text-primary">{{ $permintaan->layanan->nama_layanan }}</div>
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
                        <h5 class="fw-bold mb-0 text-primary">Parameter Akta</h5>
                    </div>
                    <p class="text-muted small">Sesuaikan parameter di bawah ini. Tekan tombol kuning untuk mempopulerkan isi draft akta.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary">Pilih Jenis Draft Akta</label>
                        <select id="select-jenis-draft" class="form-select form-select-sm border-primary">
                            <option value="pt">Pendirian PT / CV (30 Halaman) [Sesuai PDF Putri 3]</option>
                            <option value="hibah">Akta Hibah (2 Halaman)</option>
                            <option value="ajb">Akta Jual Beli (2 Halaman)</option>
                            <option value="legalisasi">Legalisasi Dokumen (1 Halaman)</option>
                            <option value="default">Default / Akta Umum (2 Halaman)</option>
                        </select>
                        <small class="text-muted d-block mt-1">Pilih "Pendirian PT / CV" untuk memuat draf akta lengkap 30 halaman dari PDF Putri 3.</small>
                    </div>

                    <div id="dynamic-fields-container">
                        <!-- Dynamic fields will be rendered here by JS -->
                    </div>
                    
                    <button type="button" id="btnApplyTemplate" class="btn btn-warning w-100 fw-bold mb-4 shadow-sm py-2">
                        <i class="fa fa-file-invoice me-1"></i> Terapkan ke Template Akta
                    </button>
                </div>
                
                <!-- Right Column: Standard Form -->
                <div class="col-lg-8 ps-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="bg-primary text-white rounded-circle px-2 py-1 me-2 fw-bold small">2</span>
                        <h5 class="fw-bold mb-0 text-primary">Informasi & Isi Akta</h5>
                    </div>

                    <form method="POST" action="{{ route('admin.akta.store', $permintaan->id) }}">
                        @csrf
                        <input type="hidden" name="permintaan_id" value="{{ $permintaan->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Nomor Akta</label>
                                    <input type="text" name="nomor_akta" class="form-control @error('nomor_akta') is-invalid @enderror" value="{{ old('nomor_akta') }}" required placeholder="Contoh: 01">
                                    @error('nomor_akta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-muted small">Nama Akta</label>
                                    <input type="text" name="nama_akta" class="form-control @error('nama_akta') is-invalid @enderror" value="{{ old('nama_akta') }}" required placeholder="Contoh: Akta Pendirian PT">
                                    @error('nama_akta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">Tanggal Akta</label>
                            <input type="date" name="tanggal_akta" class="form-control @error('tanggal_akta') is-invalid @enderror" value="{{ old('tanggal_akta', date('Y-m-d')) }}" required>
                            @error('tanggal_akta')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">Isi Akta</label>
                            <textarea id="editor" name="isi_akta" class="@error('isi_akta') is-invalid @enderror">{{ old('isi_akta') }}</textarea>
                            @error('isi_akta')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.akta.index') }}" class="btn btn-light border fw-semibold">
                                <i class="fa fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="fa fa-save me-1"></i> Simpan Akta
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
    const layananName = "{{ $permintaan->layanan->nama_layanan }}";

    // Inlined Templates matching professional PDF style
    window.templatePT = `<p>AKTA PENDIRIAN PERSEROAN TERBATAS [NAMA_PT]  Nomor : [NOMOR_AKTA]  Pada hari ini, [HARI_TANGGAL_AKTA].-------------------- Pukul [JAM_AKTA] ([JAM_AKTA_TERBILANG]) Waktu Indonesia Barat.----------------------------- Berhadapan dengan saya, EKA SULISTYA, Sarjana Hukum, Magister Kenotariatan, Notaris, berkedudukan di Kota Pontianak, dengan wilayah jabatan seluruh wilayah Propinsi Kalimantan Barat, dengan dihadiri oleh saksi-saksi yang akan disebutkan pada bagian akhir akta ini : ---------------------------------------- 1. Tuan [NAMA_PENDIRI_1], lahir di [TTL_PENDIRI_1], Warga Negara Indonesia, Wiraswasta, bertempat tinggal di [ALAMAT_PENDIRI_1], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PENDIRI_1];------------------------------ Untuk sementara berada di Kota Pontianak.-----</p>
<p>2</p>
<p>2. Tuan [NAMA_PENDIRI_2], lahir di [TTL_PENDIRI_2], Warga Negara Indonesia, Mahasiswa, bertempat tinggal di [ALAMAT_PENDIRI_2],  Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PENDIRI_2].--------------- Para penghadap bertindak untuk diri sendiri dan dalam kedudukannya sebagaimana tersebut di atas dengan ini menerangkan, bahwa dengan tidak mengurangi izin dari pihak yang berwenang telah sepakat dan setuju untuk bersama-sama mendirikan suatu perseroan terbatas dengan anggaran dasar sebagaimana yang termuat dalam akta pendirian ini (untuk selanjutnya cukup disingkat dengan &quot;Anggaran Dasar&quot;) sebagai berikut : ------------------------- ------------- NAMA DAN TEMPAT KEDUDUKAN ----------- --------------------- Pasal 1 --------------------- 1. Perseroan terbatas ini Bernama : --------------- ------- &quot;[NAMA_PT]&quot; ------ (selanjutnya cukup disingkat dengan &quot;Perseroan&quot;), berkedudukan di [KEDUDUKAN_PT].-------------</p>
<p>3</p>
<p>2. Perseroan  dapat  membuka  kantor  cabang  atau kantor perwakilan, baik di dalam maupun di luar wilayah    Republik    Indonesia    sebagaimana ditetapkan oleh Direksi, dengan persetujuan dari Dewan Komisaris.-------------------------------- -------- JANGKA WAKTU BERDIRINYA PERSEROAN -------- --------------------- Pasal 2 --------------------- Perseroan didirikan untuk jangka waktu tidak  terbatas.------------------------------------------ ------ MAKSUD DAN TUJUAN SERTA KEGIATAN USAHA ----- --------------------- Pasal 3 --------------------- 1. Maksud dan tujuan dari Perseroan ini adalah menjalankan usaha dibidang : -------------------- a. Industri Pengolahan;-------------------------- b. Konstruksi;----------------------------------- c. Perdagangan Besar dan Eceran; Reparasi dan Perawatan Mobil dan Sepeda Motor;------------- d. Aktivitas Kesehatan Manusia Dan Aktivitas Sosial;--------------------------------------- e. Aktivitas Jasa lainnya.----------------------- 2. Untuk mencapai maksud dan tujuan tersebut di atas Perseroan dapat melaksanakan kegiatan usaha sebagai berikut : -------------------------------  a. Industri Pengolahan;--------------------------- - Industri minyak mentah kelapa (10422);------</p>
<p>4</p>
<p>- Industri pemurnian minyak mentah kelapa sawit dan minyak mentah inti kelapa sawit (10434);----------------------------------- - Industri pemisahan/fraksinasi minyak murni inti kelapa sawit (10436);------------------  b. Konstruksi;------------------------------------ - Konstruksi gedung hunian (41011);----------- - Konstruksi Gedung perkantoran (41012);------ - Konstruksi lainnya (41019);----------------- - Konstruksi bangunan sipil jalan (42101);---- - Konstruksi bangunan sipil jembatan, jalan layang, fly over, dan underpass (42102);---- - Konstruksi jaringan irigasi dan drainase (42201);-----------------------------------  c. Perdagangan  Besar  dan  Eceran;  Reparasi  dan      Perawatan Mobil dan Sepeda Motor;-------------- - Perdagangan besar atas dasar balas jasa (fee) atau kontrak (46100);----------------------- - Perdagangan besar buah yang mengandung minyak (46202);---------------------------- - Perdagangan besar minyak dan lemak nabati (46315);----------------------------------- - Perdagangan besar kosmetik untuk manusia (46443);-----------------------------------</p>
<p>5</p>
<p>- Perdagangan besar perhiasan dan jam (46494);----------------------------------- - Perdagangan besar bahan bakar padat, cair dan gas dan produk YBDI (46610);---------------- - Perdagangan besar bahan konstruksi dari kayu (46636);----------------------------------- - Perdagangan besar bahan dan barang kimia (46651);----------------------------------- - Perdagangan besar pupuk dan produk agrokimia (46652);----------------------------------- - Perdagangan besar bahan berbahaya (B2) (46653);----------------------------------- - Perdagangan besar karet dan plastik dalam bentuk dasar (46693);----------------------- - Perdagangan besar berbagai macam barang (46900);-----------------------------------  d. Aktivitas   Kesehatan   Manusia   Dan   Aktivitas      Sosial;---------------------------------------- - Aktivitas pelayanan penunjang kesehatan (869030;-----------------------------------  e. aktivitas jasa Lainnya;------------------------ - Aktivitas salon kecantikan (96112).---------</p>
<p>6</p>
<p>----------------------- MODAL --------------------- ---------------------- Pasal 4 -------------------- 1. Modal dasar perseroan berjumlah ----------------- [MODAL_DASAR] terbagi atas [JUMLAH_SAHAM_MODAL_DASAR] saham, masing-masing saham bernilai nominal [NOMINAL_PER_SAHAM].--------------- 2. Dari modal dasar tersebut telah ditempatkan dan disetor sebesar 50% (limapuluh persen) atau sejumlah [JUMLAH_SAHAM_MODAL_DISETOR] saham dengan nilai nominal seluruhnya sebesar [MODAL_DISETOR] oleh para pendiri yang telah mengambil bagian saham dari rincian serta nilai nominal saham yang disebutkan sebelum akhir akta ini.-------------------------- 3. Saham yang masih dalam simpanan akan dikeluarkan oleh perseroan menurut keperluan modal Perseroan, dengan persetujuan Rapat Umum Pemegang Saham.------ Para pemegang saham yang namanya tercatat dalam Daftar Pemegang Saham mempunyai hak terlebih dahulu untuk mengambil bagian atas saham yang hendak dikeluarkan dalam jangka waktu 14 (empatbelas) hari sejak tanggal penawaran dilakukan dan masing-masing pemegang saham berhak mengambil bagian seimbang dengan jumlah saham yang mereka miliki</p>
<p>7</p>
<p>(proporsional) baik terhadap saham yang menjadi bagiannya maupun terhadap sisa saham yang tidak diambil oleh pemegang saham lainnya.--------------- ---------------------- SAHAM ---------------------- ---------------------- Pasal 5 -------------------- 1. Semua saham yang dikeluarkan oleh Perseroan adalah Saham Atas Nama.--------------------------------- 2. Yang boleh memiliki dan mempergunakan hak atas saham adalah Warga Negara Indonesia dan/atau badan hukum Indonesia.-------------------------------- 3. Bukti pemilikan saham dapat berupa surat saham.-- 4. Dalam hal Perseroan tidak menerbitkan surat saham, pemilikan saham dapat dibuktikan dengan surat keterangan atau catatan yang dikeluarkan oleh Perseroan.-------------------------------------- 5. Jika dikeluarkan surat saham, maka untuk setiap surat saham diberi sehelai surat saham.---------- 6. Surat kolektif saham dapat dikeluarkan sebagai bukti pemilikan 2 (dua) atau lebih saham yang dimiliki oleh seorang pemegang saham.------------ 7. Pada surat saham harus dicantumkan sekurangnya :  a. Nama dan alamat pemegang saham;--------------- b. Nomor surat saham;---------------------------- c. Nilai nominal saham;-------------------------- d. Tanggal pengeluaran surat saham.--------------</p>
<p>8</p>
<p>8. Pada surat kolektif saham sekurangnya harus dicantumkan : ----------------------------------- a. Nama dan alamat pemegang saham;--------------- b. Nomor surat kolektif saham;------------------- c. Nomor surat saham dan jumlah saham;----------- d. Nilai nominal saham;-------------------------- e. Tanggal pengeluaran surat kolektif saham.----- 9. Surat saham dan surat kolektif saham harus ditandatangani oleh Direksi.--------------------- -------------- PENGGANTI SURAT SAHAM -------------- --------------------- Pasal 6 --------------------- 1. Jika surat saham rusak atau tidak dapat dipakai, atas permintaan mereka yang berkepentingan, Direksi mengeluarkan surat saham pengganti, setelah surat saham yang rusak atau tidak dapat dipakai tersebut diserahkan kembali kepada Direksi.---------------------------------------- 2. Surat saham sebagaimana dimaksud dalam ayat (1) harus dimusnahkan dan dibuat berita acara oleh Direksi untuk dilaporkan dalam RUPS berikutnya.-- 3. Jika surat saham hilang, atas permintaan mereka yang berkepentingan, Direksi mengeluarkan surat saham pengganti setelah menurut pendapat Direksi kehilangan tersebut cukup dibuktikan dan dengan</p>
<p>9</p>
<p>jaminan yang dipandang perlu oleh Direksi untuk tiap peristiwa yang khusus.---------------------- 4. Setelah surat saham pengganti dikeluarkan, surat saham yang dinyatakan hilang tersebut, tidak berlaku lagi terhadap Perseroan.----------------- 5. Semua biaya yang berhubungan dengan pengeluaran surat saham pengganti, ditanggung oleh pemegang saham yang berkepentingan.----------------------- 6. Ketentuan sebagaimana dimaksud pada ayat (1), ayat (2), ayat (3), ayat (4) dan ayat (5) mutatis-mutandis berlaku bagi pengeluaran surat kolektif saham pengganti.-------------------------------- ------------- PEMINDAHAN HAK ATAS SAHAM ----------- ----------------------- Pasal 7 ------------------- 1. Pemindahan hak atas saham, harus berdasarkan akta pemindahan hak yang ditandatangani oleh yang memindahkan dan yang menerima pemindahan atau  kuasanya yang sah.------------------------------- 2. Pemegang saham yang hendak memindahkan hak atas saham, harus menawarkan terlebih dahulu kepada pemegang saham lain dengan menyebutkan harga serta persyaratan penjualan dan memberitahukan kepada Direksi secara tertulis tentang penawaran tersebut.---------------------------------------</p>
<p>10</p>
<p>3. Pemindahan hak atas saham harus mendapat persetujuan dari instansi yang berwenang, jika peraturan perundang-undangan mensyaratkan hal tersebut.--------------------------------------- 4. Mulai hari panggilan RUPS sampai dengan hari dilaksanakan RUPS pemindahan hak atas saham tidak diperkenankan.---------------------------------- 5. Apabila karena warisan, perkawinan atau sebab lain saham tidak lagi menjadi milik warga negara Indonesia atau badan hukum Indonesia, maka dalam jangka waktu 1 (satu) tahun orang atau badan hukum tersebut wajib memindahkan hak atas sahamnya kepada warga negara Indonesia atau badan hukum Indonesia, sesuai ketentuan Anggaran Dasar.------  ------------ RAPAT UMUM PEMEGANG SAHAM ------------ ----------------------- Pasal 8 ----------------- 1. Rapat Umum Pemegang Saham yang selanjutnya disebut RUPS adalah : -----------------------------------    a. RUPS tahunan; -------------------------------    b. RUPS lainnya,  yang  dalam  Anggaran Dasar ini         disebut RUPS luar biasa.---------------------  2. Istilah RUPS dalam Anggaran Dasar ini berarti keduanya, yaitu : RUPS tahunan dan RUPS luar biasa kecuali dengan tegas ditentukan lain.------------</p>
<p>11</p>
<p>3. Dalam RUPS tahunan : ---------------------------- a. Direksi menyampaikan : ----------------------- - Laporan tahunan yang telah ditelaah oleh Dewan Komisaris untuk mendapat persetujuan RUPS;-------------------------------------- - Laporan keuangan untuk mendapat pengesahan RUPS;-------------------------------------- b. Ditetapkan penggunaan laba, jika perseroan mempunyai saldo laba yang positif;------------ c. Diputuskan mata acara RUPS lainnya yang telah diajukan sebagaimana mestinya dengan memperhatikan ketentuan anggaran dasar.------- 4. Persetujuan laporan tahunan dan pengesahan laporan keuangan oleh RUPS tahunan berarti memberikan pelunasan dan pembebasan tanggung jawab sepenuhnya kepada anggota Direksi dan Dewan Komisaris atas pengurusan dan pengawasan yang telah dijalankan selama tahun buku yang lalu, sejauh tindakan tersebut tercermin dalam Laporan Tahunan dan Laporan Keuangan.-------------------- 5. RUPS Luar Biasa dapat diselenggarakan sewaktu-waktu berdasarkan kebutuhan untuk membicarakan dan memutuskan mata acara rapat kecuali mata acara rapat yang dimaksud pada ayat (3) huruf a dan</p>
<p>12</p>
<p>huruf b, dengan memperhatikan peraturan  perundang-undangan serta Anggaran Dasar.--------- ----- TEMPAT, PEMANGGILAN, DAN PIMPINAN RUPS ------ ---------------------- Pasal 9 -------------------- 1. RUPS diadakan di tempat kedudukan perseroan atau di tempat perseroan melakukan kegiatan usaha.---- 2. RUPS diselenggarakan dengan melakukan pemanggilan terlebih dahulu kepada para pemegang saham dengan surat tercatat dan/atau dengan iklan dalam surat kabar.------------------------------------------ 3. Pemanggilan dilakukan paling lambat 14 (empat belas) hari sebelum tanggal RUPS diadakan dengan tidak memperhitungkan tanggal pemanggilan dan tanggal RUPS diadakan.--------------------------- 4. Pemanggilan RUPS tidak diperlukan dalam hal semua pemegang saham hadir dan semua menyetujui agenda rapat dan keputusan disetujui dengan suara bulat.  5. RUPS dipimpin oleh Direktur Utama. Selain itu sebagai alternatif lain RUPS dapat dipimpin oleh Komisaris Utama/Presiden Komisaris (pilih salah satu).------------------------------------------ 6. Jika Direktur Utama tidak ada atau berhalangan karena sebab apapun yang tidak perlu dibuktikan kepada pihak ketiga RUPS dipimpin oleh salah seorang Direktur.-------------------------------</p>
<p>13</p>
<p>7. Jika semua Direktur tidak hadir atau berhalangan karena sebab apapun yang tidak perlu dibuktikan kepada pihak ketiga RUPS dipimpin oleh salah seorang anggota Dewan Komisaris.----------------- 8. Jika semua anggota Dewan Komisaris tidak hadir atau berhalangan karena sebab apa pun yang tidak perlu dibuktikan kepada pihak ketiga, RUPS dipimpin oleh seorang yang dipilih oleh dan diantara mereka yang hadir dalam RUPS.----------- 9. RUPS dapat juga diselenggarakan melalui media telekonferensi, video konferensi atau sarana media elektronik lainnya yang memungkinkan semua peserta RUPS saling melihat dan mendengarkan  secara langsung serta berpartisipasi dalam rapat.  Penyelenggaraan RUPS melalui media elektronik tersebut harus dibuatkan risalah rapat yang disetujui dan ditandatangani oleh semua peserta RUPS.------------------------------------------- ------ KUORUM, HAK SUARA, DAN KEPUTUSAN RUPS ------ --------------------- Pasal 10 -------------------- 1. RUPS dapat dilangsungkan apabila kuorum kehadiran sebagaimana disyaratkan dalam undang-undang tentang Perseroan Terbatas telah dipenuhi.------- 2. Pemungutan suara mengenai diri orang dilakukan dengan surat tertutup yang tidak ditandatangani</p>
<p>14</p>
<p>dan mengenai hal lain secara lisan, kecuali apabila ketua RUPS menentukan lain tanpa ada keberatan dari pemegang saham yang hadir dalam RUPS.------------------------------------------- 3. Suara blanko atau suara yang tidak sah dianggap tidak ada dan tidak dihitung dalam menentukan jumlah suara yang dikeluarkan dalam RUPS.-------- 4. RUPS dapat mengambil keputusan berdasarkan musyawarah untuk mufakat atau berdasarkan suara setuju dari jumlah suara yang dikeluarkan dalam RUPS sebagaimana ditentukan dalam Undang-undang.-  5. Pemegang saham dapat juga mengambil keputusan yang sah tanpa mengadakan RUPS, dengan ketentuan semua pemegang saham telah diberitahu secara tertulis dan semua pemegang saham memberikan persetujuan mengenai usul yang diajukan secara tertulis serta menandatangani persetujuan tersebut.------------- 6. Keputusan yang diambil dengan cara demikian mempunyai kekuatan yang sama dengan keputusan yang diambil dengan sah dalam RUPS.------------------- ------------------ D I R E K S I ------------------ --------------------- Pasal 11 -------------------- 1. Perseroan diurus dan dipimpin oleh Direksi yang terdiri dari seorang Direksi atau lebih.---------</p>
<p>15</p>
<p>2. Jika diangkat lebih dari seorang Direktur, maka seorang diantaranya dapat diangkat sebagai Direktur Utama.--------------------------------- 3. Anggota Direksi diangkat oleh Rapat Umum Pemegang Saham, untuk jangka waktu 5 (lima) tahun dengan tidak mengurangi hak Rapat Umum Pemegang Saham untuk memberhentikannya sewaktu-waktu.----------- 4. Jika oleh suatu sebab apapun jabatan seorang atau lebih atau semua anggota Direksi lowong, maka dalam jangka waktu 30 (tigapuluh) hari sejak terjadi lowongan harus diselenggarakan Rapat Umum Pemegang Saham, untuk mengisi lowongan itu dengan memperhatikan ketentuan peraturan perundang undangan dan Anggaran Dasar.--------------------- 5. Jika oleh suatu sebab apapun semua jabatan anggota Direksi lowong, untuk sementara Perseroan diurus oleh anggota Dewan Komisaris yang ditunjuk oleh rapat Dewan Komisaris.--------------------------- 6. Anggota Direksi berhak mengundurkan diri dari jabatannya dengan memberitahukan secara tertulis kepada Perseroan sekurangnya 30 (tigapuluh) hari sebelum tanggal pengunduran dirinya.------------- 7. Jabatan anggota Direksi berakhir, jika : -------- a. Mengundurkan diri sesuai ketentuan ayat (6);--</p>
<p>16</p>
<p>b. Tidak lagi memenuhi persyaratan peraturan perundang undangan;--------------------------- c. Meninggal dunia;------------------------------ d. Diberhentikan berdasarkan keputusan Rapat Umum Pemegang Saham.------------------------------- ------------ TUGAS DAN WEWENANG DIREKSI ----------- --------------------- Pasal 12 -------------------- 1. Direksi berhak mewakili Perseroan di dalam dan di luar Pengadilan tentang segala hal dan dalam segala kejadian, mengikat Perseroan dengan pihak lain dan pihak lain dengan Perseroan, serta menjalankan segala tindakan, baik yang mengenai kepengurusan maupun kepemilikan, akan tetapi dengan pembatasan bahwa untuk : ----------------- a. Meminjam atau meminjamkan uang atas nama Perseroan (tidak termasuk mengambil uang Perseroan di Bank);--------------------------- b. Mendirikan suatu usaha atau turut serta pada perusahaan lain baik di dalam maupun di luar negeri;--------------------------------------- -Harus dengan persetujuan Dewan Komisaris.---- 2. a. Direktur Utama berhak dan berwenang bertindak untuk dan atas nama Direksi serta mewakili perseroan.------------------------------------</p>
<p>17</p>
<p>b. Dalam hal Direktur Utama tidak hadir atau berhalangan karena sebab apapun juga, yang tidak perlu dibuktikan kepada pihak ketiga, maka salah  seorang anggota Direksi lainnya berhak dan berwenang bertindak untuk dan atas nama Direksi serta mewakili Perseroan.-------- ------------------ RAPAT DIREKSI ------------------ --------------------- Pasal 13 -------------------- 1. Penyelenggaraan Rapat Direksi dapat dilakukan setiap waktu apabila dipandang perlu : -------- a. Oleh seorang atau lebih anggota Direksi;---- b. Atas permintaan tertulis dari seorang atau lebih anggota Dewan Komisaris; atau -------- c. Atas permintaan tertulis dari 1 (satu) orang atau lebih pemegang saham yang bersama-sama mewakili 1/10 (satu per sepuluh) atau lebih dari jumlah seluruh saham dengan hak suara.- 2. Panggilan Rapat Direksi dilakukan oleh anggota Direksi yang berhak bertindak untuk dan atas nama Direksi menurut ketentuan pasal 9 Anggaran Dasar ini.------------------------------------- 3. Panggilan Rapat Direksi disampaikan dengan surat tercatat atau dengan surat yang disampaikan langsung kepada setiap anggota Direksi dengan mendapat tanda terima paling lambat 3 (tiga)</p>
<p>18</p>
<p>hari sebelum rapat diadakan, dengan tidak memperhitungkan tanggal panggilan dan tanggal rapat.----------------------------------------- 4. Panggilan rapat itu harus mencantumkan acara, tanggal, waktu dan tempat rapat.--------------- 5. Rapat Direksi diadakan ditempat kedudukan Perseroan atau tempat kegiatan usaha Perseroan. 6. Apabila semua anggota Direksi hadir atau  diwakili, panggilan terlebih dahulu tersebut tidak disyaratkan dan Rapat Direksi dapat diadakan dimanapun juga dan berhak mengambil keputusan yang sah dan mengikat.--------------- 7. Rapat Direksi dipimpin oleh Direktur Utama dalam hal Direktur Utama tidak dapat hadir atau berhalangan yang tidak perlu dibuktikan kepada pihak ketiga, Rapat Direksi dipimpin oleh seorang anggota Direksi yang dipilih oleh dan dari antara anggota Direksi yang hadir.-------- 8. Seorang anggota Direksi dapat diwakili dalam --Rapat Direksi hanya oleh anggota Direksi lainnya berdasarkan surat kuasa.----------------------- 9. Rapat Direksi adalah sah dan berhak mengambil keputusan yang mengikat apabila lebih dari 1/2 (satu per dua) dari jumlah anggota Direksi hadir atau diwakili dalam rapat.---------------------</p>
<p>19</p>
<p>10. Keputusan Rapat Direksi harus diambil berdasarkan musyawarah untuk mufakat.---------- 11. Apabila tidak tercapai maka keputusan diambil dengan pemungutan suara berdasarkan suara setuju paling sedikit lebih dari 1/2 (satu per dua) dari jumlah suara yang dikeluarkan dalam rapat.----- 12. Apabila suara yang setuju dan yang tidak setuju berimbang, ketua rapat Direksi yang akan menentukan.------------------------------------ 13. a. Setiap  anggota  Direksi  yang  hadir  berhak  mengeluarkan 1 (satu) suara dan tambahan 1 (satu) suara untuk setiap anggota Direksi lain yang diwakilinya.----------------------   b. Pemungutan    suara    mengenai    diri   orang  dilakukan dengan surat suara tertutup tanpa tanda tangan sedangkan pemungutan suara mengenai hal-hal lain dilakukan secara lisan kecuali ketua rapat menentukan lain tanpa ada keberatan dari yang hadir.------------------     c. Suara blanko dan suara yang tidak sah dianggap      tidak  dikeluarkan  secara  sah  dan  dianggap          tidak   ada   serta   tidak   dihitung   dalam         menentukan jumlah suara yang dikeluarkan.---</p>
<p>20</p>
<p>13. Direksi dapat juga mengambil keputusan yang sah      tanpa mengadakan Rapat Direksi, dengan ketentuan       semua  anggota  Direksi  telah  diberitahu secara      tertulis  dan  semua  anggota  Direksi memberikan       persetujuan  mengenai  usul  yang diajukan secara      tertulis   dengan   menandatangani   persetujuan      tersebut.-------------------------------------- 14. Keputusan  yang   telah   diambil   dengan   cara       demikian  mempunyai  kekuatan  yang  sama  dengan      keputusan yang diambil dengan sah  dalam  Rapat      Direksi.--------------------------------------- ----------------- DEWAN KOMISARIS ----------------- --------------------- Pasal 14 -------------------- 1. Dewan Komisaris terdiri dari seorang atau lebih anggota Dewan Komisaris, apabila diangkat lebih dari seorang anggota Dewan Komisaris, maka seorang diantaranya dapat diangkat sebagai Komisaris Utama.------------------------------------------ 2. Yang boleh diangkat sebagai anggota Dewan Komisaris hanya warga Negara Indonesia yang memenuhi persyaratan yang ditentukan peraturan perundang-undangan yang berlaku.----------------- 3. Anggota Dewan Komisaris diangkat oleh rapat umum Pemegang Saham untuk jangka waktu 5 (lima) tahun</p>
<p>21</p>
<p>dengan tidak mengurangi hak Rapat Umum Pemegang Saham untuk memberhentikan sewaktu-waktu.-------- 4. Jika oleh suatu sebab jabatan anggota Dewan Komisaris lowong, maka dalam jangka waktu 30 (tiga puluh) hari setelah terjadinya lowongan, harus diselenggarakan Rapat Umum Pemegang Saham untuk mengisi lowongan itu dengan memperhatikan ketentuan ayat 2 pasal ini.---------------------- 5. Seorang anggota Dewan Komisaris berhak mengundurkan diri dari jabatannya dengan memberitahukan secara tertulis mengenai maksud tersebut kepada Perseroan sekurangnya 30 (tiga puluh) hari sebelum tanggal pengunduran dirinya.- 6. Jabatan anggota Dewan Komisaris berakhir apabila:  a. Kehilangan Kewarganegaraan Indonesia;--------- b. Mengundurkan diri sesuai dengan ketentuan ayat 5;-------------------------------------------- c. Tidak lagi memenuhi persyaratan perundang-undangan yang berlaku;------------------------ d. Meninggal dunia;------------------------------ e. Diberhentikan berdasarkan keputusan Rapat Umum Pemegang Saham.-------------------------------</p>
<p>22</p>
<p>-------- TUGAS DAN WEWENANG DEWAN KOMISARIS ------- --------------------- Pasal 15 -------------------- 1. Dewan Komisaris setiap waktu dalam jam kerja kantor Perseroan berhak memasuki bangunan dan halaman atau tempat lain yang dipergunakan atau yang dikuasai oleh Perseroan dan berhak memeriksa semua pembukuan, surat dan alat bukti lainnya, memeriksa dan mencocokkan keadaan uang kas dan lain-lain serta berhak untuk mengetahui segala tindakan yang telah dijalankan oleh Direksi.----- 2. Direksi dan setiap anggota Direksi wajib untuk memberikan penjelasan tentang segala hal yang ditanyakan oleh Dewan Komisaris.----------------- 3. Apabila seluruh anggota Direksi diberhentikan sementara dan Perseroan tidak mempunyai seorangpun anggota Direksi maka untuk sementara Dewan Komisaris diwajibkan untuk mengurus Perseroan.-------------------------------------- 4. Dalam hal demikian Dewan Komisaris berhak untuk memberikan kekuasaan sementara kepada seorang atau lebih diantara anggota Dewan Komisaris atas tanggungan Dewan Komisaris.---------------------- 5. Dalam hal hanya ada seorang anggota Dewan Komisaris, segala tugas dan wewenang yang</p>
<p>23</p>
<p>diberikan kepada Komisaris dalam anggaran dasar ini berlaku pula baginya.------------------------ --------------- RAPAT DEWAN KOMISARIS ------------- --------------------- Pasal 16 -------------------- Ketentuan sebagaimana dimaksud dalam Pasal 13 mutatis-mutandis berlaku bagi rapat Dewan Komisaris.  -- RENCANA KERJA, TAHUN BUKU DAN LAPORAN TAHUNAN -- --------------------- Pasal 17 -------------------- a. Direksi menyampaikan rencana kerja yang memuat juga anggaran tahunan Perseroan kepada Dewan Komisaris untuk mendapat persetujuan, sebelum tahun buku dimulai.------------------------------ b. Rencana kerja sebagaimana dimaksud pada ayat (1) harus disampaikan paling lambat 60 (enam puluh) hari sebelum dimulainya tahun buku yang akan datang.----------------------------------------- c. Tahun buku Perseroan berjalan dari tanggal 1 (satu) Januari sampai dengan tanggal 31 (tiga puluh satu) Desember.---------------------------- -Pada akhir bulan Desember tiap tahun, buku Perseroan ditutup.------------------------------ -Untuk pertama kalinya buku Perseroan dimulai pada tanggal dari akta pendirian ini dan ditutup pada tanggal 31 (tiga puluh satu) Desember 2025.------</p>
<p>24</p>
<p>d. Direksi menyusun laporan tahunan dan menyediakannya di kantor Perseroan untuk dapat diperiksa oleh para pemegang saham terhitung sejak tanggal panggilan RUPS tahunan.------------------ ------ PENGGUNAAN LABA DAN PEMBAGIAN DIVIDEN ------ --------------------- Pasal 18 -------------------- 1. Laba bersih Perseroan dalam suatu tahun buku seperti tercantum dalam neraca dan perhitungan laba rugi yang telah disahkan oleh RUPS tahunan dan merupakan saldo laba yang positif, dibagi menurut cara penggunaannya yang ditentukan oleh RUPS tersebut.---------------------------------- 2. Jika perhitungan laba rugi pada suatu tahun buku menunjukkan kerugian yang tidak dapat ditutup dengan dana cadangan, maka kerugian itu akan tetap dicatat dan dimasukkan dalam perhitungan laba rugi dan dalam tahun buku selanjutnya perseroan dianggap tidak mendapat laba selama kerugian yang tercatat dan dimasukkan dalam perhitungan laba rugi itu belum sama sekali tertutup.--------------------------------------- --------------- PENGGUNAAN CADANGAN --------------- --------------------- Pasal 19 -------------------- 1. Penyisihan laba bersih untuk cadangan dilakukan sampai mencapai 20% (dua puluh persen) dari jumlah</p>
<p>25</p>
<p>modal ditempatkan dan disetor hanya boleh dipergunakan untuk menutup kerugian yang tidak dipenuhi oleh cadangan lain.--------------------- 2. Jika jumlah cadangan telah melebihi jumlah 20% (dua puluh persen), RUPS dapat memutuskan agar jumlah kelebihannya digunakan bagi keperluan Perseroan.-------------------------------------- 3. Cadangan sebagaimana dimaksud pada ayat (1) yang belum dipergunakan untuk menutup kerugian dan kelebihan cadangan sebagaimana dimaksud pada ayat (2) yang penggunaannnya belum ditentukan oleh RUPS harus dikelola oleh Direksi dengan cara yang tepat menurut pertimbangan Direksi, setelah memperoleh persetujuan Dewan Komisaris dan memperhatikan peraturan perundang-undangan agar memperoleh laba.------------------------------------------- ---------------- KETENTUAN PENUTUP ---------------- --------------------- Pasal 20 -------------------- Segala sesuatu yang tidak atau belum cukup diatur dalam Anggaran Dasar ini,  akan diputus dalam RUPS.  Akhirnya, para penghadap bertindak dalam kedudukannya sebagaimana tersebut diatas menerangkan bahwa : ------------------------------------------- 1. Untuk pertama kalinya telah diambil bagian dan disetor penuh dengan uang tunai melalui kas</p>
<p>26</p>
<p>Perseroan sejumlah [JUMLAH_SAHAM_MODAL_DISETOR] saham atau seluruhnya dengan nilai nominal sebesar  [MODAL_DISETOR] yaitu oleh pendiri : -------------------- a. Tuan [NAMA_PENDIRI_1], tersebut sejumlah [SAHAM_PENDIRI_1].----------- b. Tuan [NAMA_PENDIRI_2], tersebut sejumlah [SAHAM_PENDIRI_2].------ sehingga seluruhnya berjumlah [JUMLAH_SAHAM_MODAL_DISETOR] saham dengan nilai nominal seluruhnya</p>
<p>27</p>
<p>sebesar [MODAL_DISETOR].----------------------------------- 2. Menyimpang dari ketentuan dalam pasal 11 dan pasal 14 Anggaran Dasar ini mengenai tata cara pengangkatan anggota Direksi dan Komisaris, telah diangkat sebagai : ------------------------------ DIREKSI : --------------------------------------- - Direktur       : Tuan [NAMA_PENDIRI_2], ---------                      tersebut.------------------ DEWAN KOMISARIS : ------------------------------- - Komisaris       : Tuan [NAMA_PENDIRI_1], -------                   tersebut.------------------- Pengangkatan anggota Direksi dan Dewan Komisaris tersebut telah diterima oleh masing-masing yang bersangkutan.-------------------------------------- Akhirnya penghadap bertindak dalam kedudukannya sebagaimana tersebut diatas menerangkan dengan ini memberi kuasa kepada saya, Notaris dengan hak untuk memindahkan kekuasaan ini kepada orang lain dikuasakan untuk memohon pengesahan atas Anggaran Dasar ini dari instansi yang berwenang dan untuk membuat perubahan dan/atau tambahan dalam bentuk yang bagaimanapun juga yang diperlukan untuk memperoleh pengesahan tersebut dan untuk mengajukan dan menandatangani semua permohonan dan dokumen</p>
<p>28</p>
<p>lainnya, dan untuk melaksanakan tindakan lain yang mungkin diperlukan.-------------------------------- Penghadap menyatakan dengan ini menjamin akan kebenaran keaslian, dan kelengkapan identitas pihak-pihak yang namanya tersebut dalam akta ini dan seluruh dokumen yang menjadi dasar dibuatnya akta ini tanpa ada yang dikecualikan, yang disampaikan kepada saya Notaris, maka apabila dikemudian hari sejak ditanda tangani akta ini timbul sengketa dalam bentuk apapun yang disebabkan oleh akta ini, para pihak bertanggung jawab sepenuhnya, dengan ini para penghadap menyatakan membebaskan/melepaskan saya Notaris dan saksi dari tuntutan pihak ketiga atau siapapun.------------------------------------------ Selanjutnya penghadap membubuhkan paraf di setiap halaman yang menyatakan bahwa mereka telah mengerti, memahami dan menyetujui isi dari setiap halaman di dalam akta ini.------------------------------------ Para penghadap dikenal oleh saya, Notaris, berdasarkan identitas yang diberikan.-------------- --------------- DEMIKIANLAH AKTA INI --------------Dibuat sebagai minuta yang dibacakan serta ditandatangani di Kota Pontianak, pada hari, tanggal, bulan dan tahun seperti yang tersebut pada bagian awal akta ini, dengan dihadiri oleh : ------</p>
<p>29</p>
<p>1. [SAKSI_1];------------------------------- 2. [SAKSI_2];-------------------------------    Keduanya sebagai saksi-saksi.------------------- Segera setelah saya Notaris, membacakan akta ini kepada para Penghadap dan para Saksi, maka segera para Penghadap, para Saksi dan saya, Notaris,</p>
<p>30</p>
<p>memparaf dan menandatangani akta ini, serta untuk memenuhi Ketentuan pasal 16 (enambelas) ayat 1(satu) huruf C Undang-Undang Republik Indonesia Nomor 30 (tiga puluh) Tahun 2004 (duaribu empat) tentang jabatan Notaris sebagaimana yang telah diubah dengan Undang-Undang Republik Indonesia Nomor 2 (dua) Tahun 2014 (duaribu empatbelas), maka para Penghadap juga membubuhkan sidik jari pada lembaran tersendiri untuk dilekatkan pada minuta akta ini.------------- Dilangsungkan dengan tanpa perubahan.-------------- Minuta  akta  ini  telah   ditandatangani   dengan sempurna.------------------------------------------ -- DIBERIKAN SEBAGAI SALINAN YANG SAMA BUNYINYA -–  NOTARIS KOTA PONTIANAK    (EKA SULISTYA, S.H., M.Kn.)</p>`;
    window.templateHibah = `<p>AKTA HIBAH  Nomor : [NOMOR_AKTA]  Pada hari ini, [HARI_TANGGAL_AKTA].-------------------- Pukul [JAM_AKTA] ([JAM_AKTA_TERBILANG]) Waktu Indonesia Barat.----------------------------- Berhadapan dengan saya, EKA SULISTYA, Sarjana Hukum, Magister Kenotariatan, Notaris, berkedudukan di Kota Pontianak, dengan wilayah jabatan seluruh wilayah Propinsi Kalimantan Barat, dengan dihadiri oleh saksi-saksi yang akan disebutkan pada bagian akhir akta ini : ---------------------------------------- 1. Tuan [NAMA_PIHAK_1], lahir di [TTL_PIHAK_1], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_1], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_1];------------------------------ Selanjutnya disebut PEMBERI HIBAH.-------------------------------- 2. Tuan [NAMA_PIHAK_2], lahir di [TTL_PIHAK_2], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_2], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_2].--------------- Selanjutnya disebut PENERIMA HIBAH.------------------------------- Para penghadap bertindak untuk diri sendiri dan dalam kedudukannya sebagaimana tersebut di atas dengan ini menerangkan, bahwa Pemberi Hibah dengan ini menghibahkan kepada Penerima Hibah objek tanah dan bangunan berikut : ------------------------- ------------- OBJEK HIBAH ----------- --------------------- Pasal 1 --------------------- Sebidang tanah dan bangunan yang berdiri di atasnya sebagaimana diuraikan dalam Sertifikat Hak Milik Nomor [NOMOR_SERTIFIKAT] seluas [LUAS_TANAH] meter persegi yang terletak di [LOKASI_TANAH].------------- --------------------- Pasal 2 --------------------- Pemberi Hibah menjamin bahwa objek hibah tersebut adalah benar miliknya sendiri, tidak dalam sengketa, tidak sedang dijaminkan kepada pihak lain, dan bebas dari segala sitaan.--------------------- Pasal 3 --------------------- Mulai hari ini objek hibah telah diserahkan dan menjadi hak sepenuhnya bagi Penerima Hibah. Segala keuntungan dan kerugian atas objek tersebut sejak saat ini ditanggung oleh Penerima Hibah.------------- ---------------- KETENTUAN PENUTUP ---------------- Akhirnya para penghadap bertindak dalam kedudukannya sebagaimana tersebut diatas menerangkan dengan ini memberi kuasa kepada saya, Notaris untuk memproses balik nama sertifikat objek hibah ini.-------------------------------- Akhirnya penghadap menyatakan dengan ini menjamin akan kebenaran keaslian, dan kelengkapan identitas pihak-pihak yang namanya tersebut dalam akta ini.------------------------------------------ --------------- DEMIKIANLAH AKTA INI --------------Dibuat sebagai minuta yang dibacakan serta ditandatangani di Kota Pontianak, pada hari, tanggal, bulan dan tahun seperti yang tersebut pada bagian awal akta ini, dengan dihadiri oleh : ------ 1. [SAKSI_1];------------------------------- 2. [SAKSI_2];-------------------------------    Keduanya sebagai saksi-saksi.------------------- Segera setelah saya Notaris, membacakan akta ini kepada para Penghadap dan para Saksi, maka segera para Penghadap, para Saksi dan saya, Notaris, memparaf dan menandatangani akta ini.------------- NOTARIS KOTA PONTIANAK    (EKA SULISTYA, S.H., M.Kn.)</p>`;
    window.templateAJB = `<p>AKTA JUAL BELI  Nomor : [NOMOR_AKTA]  Pada hari ini, [HARI_TANGGAL_AKTA].-------------------- Pukul [JAM_AKTA] ([JAM_AKTA_TERBILANG]) Waktu Indonesia Barat.----------------------------- Berhadapan dengan saya, EKA SULISTYA, Sarjana Hukum, Magister Kenotariatan, Notaris, berkedudukan di Kota Pontianak, dengan wilayah jabatan seluruh wilayah Propinsi Kalimantan Barat, dengan dihadiri oleh saksi-saksi yang akan disebutkan pada bagian akhir akta ini : ---------------------------------------- 1. Tuan [NAMA_PIHAK_1], lahir di [TTL_PIHAK_1], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_1], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_1];------------------------------ Selanjutnya disebut PENJUAL.-------------------------------- 2. Tuan [NAMA_PIHAK_2], lahir di [TTL_PIHAK_2], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_2], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_2].--------------- Selanjutnya disebut PEMBELI.------------------------------- Para penghadap bertindak untuk diri sendiri dan dalam kedudukannya sebagaimana tersebut di atas dengan ini menerangkan, bahwa Penjual menjual kepada Pembeli objek tanah dan bangunan berikut : ------------------------- ------------- OBJEK JUAL BELI ----------- --------------------- Pasal 1 --------------------- Sebidang tanah dan bangunan yang berdiri di atasnya sebagaimana diuraikan dalam Sertifikat Hak Milik Nomor [NOMOR_SERTIFIKAT] seluas [LUAS_TANAH] meter persegi yang terletak di [LOKASI_TANAH] dengan harga transaksi sebesar [HARGA_TRANSAKSI].------------- --------------------- Pasal 2 --------------------- Penjual menjamin bahwa objek jual beli tersebut adalah benar miliknya sendiri, tidak dalam sengketa, tidak sedang dijaminkan kepada pihak lain, dan bebas dari segala sitaan.--------------------- Pasal 3 --------------------- Mulai hari ini objek jual beli telah diserahkan dan menjadi hak sepenuhnya bagi Pembeli. Segala keuntungan dan kerugian atas objek tersebut sejak saat ini ditanggung oleh Pembeli.------------- ---------------- KETENTUAN PENUTUP ---------------- Akhirnya para penghadap bertindak dalam kedudukannya sebagaimana tersebut diatas menerangkan dengan ini memberi kuasa kepada saya, Notaris untuk memproses balik nama sertifikat objek jual beli ini.-------------------------------- Akhirnya penghadap menyatakan dengan ini menjamin akan kebenaran keaslian, dan kelengkapan identitas pihak-pihak yang namanya tersebut dalam akta ini.------------------------------------------ --------------- DEMIKIANLAH AKTA INI --------------Dibuat sebagai minuta yang dibacakan serta ditandatangani di Kota Pontianak, pada hari, tanggal, bulan dan tahun seperti yang tersebut pada bagian awal akta ini, dengan dihadiri oleh : ------ 1. [SAKSI_1];------------------------------- 2. [SAKSI_2];-------------------------------    Keduanya sebagai saksi-saksi.------------------- Segera setelah saya Notaris, membacakan akta ini kepada para Penghadap dan para Saksi, maka segera para Penghadap, para Saksi dan saya, Notaris, memparaf dan menandatangani akta ini.------------- NOTARIS KOTA PONTIANAK    (EKA SULISTYA, S.H., M.Kn.)</p>`;
    window.templateLegalisasi = `<p>LEGALISASI DOKUMEN  Nomor : [NOMOR_AKTA]  Pada hari ini, [HARI_TANGGAL_AKTA].-------------------- Saya, EKA SULISTYA, Sarjana Hukum, Magister Kenotariatan, Notaris di Kota Pontianak, dengan wilayah jabatan seluruh wilayah Propinsi Kalimantan Barat, menerangkan bahwa yang menandatangani dokumen [NAMA_DOKUMEN] di bawah ini telah dikenal oleh saya atau telah diperkenalkan kepada saya, dan tanda tangan tersebut adalah benar dibubuhkan di hadapan saya : ---------------------------------------- 1. Tuan [NAMA_PIHAK_1], lahir di [TTL_PIHAK_1], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_1], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_1];------------------------------ Demikian surat legalisasi ini dibuat agar dapat dipergunakan sebagaimana mestinya.------------- NOTARIS KOTA PONTIANAK    (EKA SULISTYA, S.H., M.Kn.)</p>`;
    window.templateDefault = `<p>AKTA NOTARIS  Nomor : [NOMOR_AKTA]  Pada hari ini, [HARI_TANGGAL_AKTA].-------------------- Pukul [JAM_AKTA] ([JAM_AKTA_TERBILANG]) Waktu Indonesia Barat.----------------------------- Berhadapan dengan saya, EKA SULISTYA, Sarjana Hukum, Magister Kenotariatan, Notaris, berkedudukan di Kota Pontianak, dengan wilayah jabatan seluruh wilayah Propinsi Kalimantan Barat, dengan dihadiri oleh saksi-saksi yang akan disebutkan pada bagian akhir akta ini : ---------------------------------------- 1. Tuan [NAMA_PIHAK_1], lahir di [TTL_PIHAK_1], Warga Negara Indonesia, bertempat tinggal di [ALAMAT_PIHAK_1], Pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : [NIK_PIHAK_1];------------------------------ Para penghadap dengan ini sepakat membuat akta persetujuan/pernyataan bersama dengan syarat-syarat sebagai berikut : --------------------- Pasal 1 --------------------- [ISI_AKTA_UMUM]--------------------- Pasal 2 --------------------- Demikian akta ini dibuat agar dipergunakan sebagaimana mestinya.------------- ---------------- KETENTUAN PENUTUP ---------------- Akhirnya penghadap menyatakan dengan ini menjamin akan kebenaran keaslian, dan kelengkapan identitas pihak-pihak yang namanya tersebut dalam akta ini.------------------------------------------ --------------- DEMIKIANLAH AKTA INI --------------Dibuat sebagai minuta yang dibacakan serta ditandatangani di Kota Pontianak, pada hari, tanggal, bulan dan tahun seperti yang tersebut pada bagian awal akta ini, dengan dihadiri oleh : ------ 1. [SAKSI_1];------------------------------- 2. [SAKSI_2];-------------------------------    Keduanya sebagai saksi-saksi.------------------- Segera setelah saya Notaris, membacakan akta ini kepada para Penghadap dan para Saksi, maka segera para Penghadap, para Saksi dan saya, Notaris, memparaf dan menandatangani akta ini.------------- NOTARIS KOTA PONTIANAK    (EKA SULISTYA, S.H., M.Kn.)</p>`;

    // Function to render fields dynamically based on selected draft
    function renderFields() {
        const container = document.getElementById('dynamic-fields-container');
        const selectedDraft = document.getElementById('select-jenis-draft').value;
        
        let html = '';

        if (selectedDraft === 'pt') {
            // PT/CV Fields
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama PT</label>
                    <input type="text" id="param_nama_pt" class="form-control form-control-sm" value="PT. SINERGI KARYA INTERNASIONAL">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Akta</label>
                    <input type="text" id="param_nomor_akta" class="form-control form-control-sm" value="01">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Terbit</label>
                    <input type="text" id="param_hari_tanggal_akta" class="form-control form-control-sm" value="Rabu, tanggal 08-10-2025 (delapan Oktober duaribu duapuluh lima)">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (HH.MM)</label>
                    <input type="text" id="param_jam_akta" class="form-control form-control-sm" value="15.22">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (Terbilang)</label>
                    <input type="text" id="param_jam_akta_terbilang" class="form-control form-control-sm" value="limabelas lebih duapuluh dua menit">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pendiri 1</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Pendiri 1</label>
                    <input type="text" id="param_nama_pendiri_1" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL Pendiri 1</label>
                    <input type="text" id="param_ttl_pendiri_1" class="form-control form-control-sm" value="Sungai Ulin, pada tanggal 10-02-1992 (sepuluh Februari seribu sembilanratus sembilanpuluh dua)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat Pendiri 1</label>
                    <textarea id="param_alamat_pendiri_1" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya, Provinsi Kalimantan Barat</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK Pendiri 1</label>
                    <input type="text" id="param_nik_pendiri_1" class="form-control form-control-sm" value="3217091002920015">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pendiri 2</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Pendiri 2</label>
                    <input type="text" id="param_nama_pendiri_2" class="form-control form-control-sm" value="INDRA SAFARI">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL Pendiri 2</label>
                    <input type="text" id="param_ttl_pendiri_2" class="form-control form-control-sm" value="Pontianak, pada tanggal 09-04-2004 (sembilan April duaribu empat)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat Pendiri 2</label>
                    <textarea id="param_alamat_pendiri_2" class="form-control form-control-sm" rows="2">Jalan Merdeka Barat Gang Belibis Nomor 17, Rukun Tetangga 003, Rukun Warga 008, Kelurahan Tengah, Kecamatan Pontianak Kota, Kota Pontianak, Provinsi Kalimantan Barat</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK Pendiri 2</label>
                    <input type="text" id="param_nik_pendiri_2" class="form-control form-control-sm" value="6171050904040002">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Modal & Kedudukan PT</h6>
                <div class="mb-2">
                    <label class="small text-muted">Kedudukan PT</label>
                    <input type="text" id="param_kedudukan_pt" class="form-control form-control-sm" value="Kabupaten Kubu Raya">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Modal Dasar</label>
                    <input type="text" id="param_modal_dasar" class="form-control form-control-sm" value="Rp.1.500.000.000,- (satu milyar limaratus juta rupiah)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Jumlah Saham Modal Dasar</label>
                    <input type="text" id="param_jumlah_saham_modal_dasar" class="form-control form-control-sm" value="1.500 (seribu limaratus)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Nominal Per Saham</label>
                    <input type="text" id="param_nominal_per_saham" class="form-control form-control-sm" value="Rp.1.000.000,- (satu juta rupiah)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Modal Disetor</label>
                    <input type="text" id="param_modal_disetor" class="form-control form-control-sm" value="Rp.750.000.000,- (tujuhratus limapuluh juta rupiah)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Jumlah Saham Modal Disetor</label>
                    <input type="text" id="param_jumlah_saham_modal_disetor" class="form-control form-control-sm" value="750 (tujuhratus limapuluh)">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Saham Pendiri 1</label>
                    <textarea id="param_saham_pendiri_1" class="form-control form-control-sm" rows="2">80% (delapan puluh persen) atau 600 (enam ratus) saham, dengan nilai nominal seluruhnya sebesar : ----- Rp. 600.000.000,- (enamratus juta rupiah)</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Saham Pendiri 2</label>
                    <textarea id="param_saham_pendiri_2" class="form-control form-control-sm" rows="2">20% (dua puluh persen) atau 150 (seratus limapuluh) saham, dengan nilai nominal seluruhnya sebesar : ---------------- Rp. 150.000.000,- (seratus limapuluh juta rupiah)</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi-Saksi</h6>
                <div class="mb-2">
                    <label class="small text-muted">Saksi 1</label>
                    <textarea id="param_saksi_1" class="form-control form-control-sm" rows="3">Tuan IKHLASUL IMAM SYAWALUDDIN, lahir di Pontianak, pada tanggal 13-02-1999 (tigabelas Februari seribu sembilanratus sembilanpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Apel Gang Langsat Nomor 31 B, Rukun Tetangga 004, Rukun Warga 020, Kelurahan Sungai Jawi Luar, Kecamatan Pontianak Barat, Kota Pontianak, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6171031302990006</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Saksi 2</label>
                    <textarea id="param_saksi_2" class="form-control form-control-sm" rows="3">Nonya DEWI DAYATI, lahir di Ketapang, pada tanggal 25-06-1979 (duapuluh lima Juni seribu sembilanratus tujuhpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Tanggul Laut, Rukun Tetangga 013, Rukun Warga 010, Kelurahan Sungai Rengas, Kecamatan Sungai Kakap, Kabupaten Kubu Raya, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6112096506790006</textarea>
                </div>
            `;
        } else if (selectedDraft === 'hibah') {
            // Akta Hibah Fields
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Akta</label>
                    <input type="text" id="param_nomor_akta" class="form-control form-control-sm" value="02">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Terbit</label>
                    <input type="text" id="param_hari_tanggal_akta" class="form-control form-control-sm" value="Kamis, tanggal 09-10-2025 (sembilan Oktober duaribu duapuluh lima)">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (HH.MM)</label>
                    <input type="text" id="param_jam_akta" class="form-control form-control-sm" value="10.00">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (Terbilang)</label>
                    <input type="text" id="param_jam_akta_terbilang" class="form-control form-control-sm" value="sepuluh nol-nol">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pemberi Hibah (Pihak 1)</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_1" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_1" class="form-control form-control-sm" value="Sungai Ulin, 10-02-1992">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_1" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_1" class="form-control form-control-sm" value="3217091002920015">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Penerima Hibah (Pihak 2)</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_2" class="form-control form-control-sm" value="INDRA SAFARI">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_2" class="form-control form-control-sm" value="Pontianak, 09-04-2004">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_2" class="form-control form-control-sm" rows="2">Jalan Merdeka Barat Gang Belibis Nomor 17, Rukun Tetangga 003, Rukun Warga 008, Kelurahan Tengah, Kecamatan Pontianak Kota, Kota Pontianak</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_2" class="form-control form-control-sm" value="6171050904040002">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Objek Sertifikat</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nomor Sertifikat</label>
                    <input type="text" id="param_nomor_sertifikat" class="form-control form-control-sm" value="SHM No. 9812/Kubu Raya">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Luas Tanah (m2)</label>
                    <input type="text" id="param_luas_tanah" class="form-control form-control-sm" value="250">
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Lokasi Objek</label>
                    <textarea id="param_lokasi_tanah" class="form-control form-control-sm" rows="2">Desa Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi-Saksi</h6>
                <div class="mb-2">
                    <label class="small text-muted">Saksi 1</label>
                    <textarea id="param_saksi_1" class="form-control form-control-sm" rows="2">Tuan IKHLASUL IMAM SYAWALUDDIN, lahir di Pontianak, pada tanggal 13-02-1999 (tigabelas Februari seribu sembilanratus sembilanpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Apel Gang Langsat Nomor 31 B, Rukun Tetangga 004, Rukun Warga 020, Kelurahan Sungai Jawi Luar, Kecamatan Pontianak Barat, Kota Pontianak, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6171031302990006</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Saksi 2</label>
                    <textarea id="param_saksi_2" class="form-control form-control-sm" rows="2">Nonya DEWI DAYATI, lahir di Ketapang, pada tanggal 25-06-1979 (duapuluh lima Juni seribu sembilanratus tujuhpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Tanggul Laut, Rukun Tetangga 013, Rukun Warga 010, Kelurahan Sungai Rengas, Kecamatan Sungai Kakap, Kabupaten Kubu Raya, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6112096506790006</textarea>
                </div>
            `;
        } else if (selectedDraft === 'ajb') {
            // AJB Fields
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Akta</label>
                    <input type="text" id="param_nomor_akta" class="form-control form-control-sm" value="03">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Terbit</label>
                    <input type="text" id="param_hari_tanggal_akta" class="form-control form-control-sm" value="Jumat, tanggal 10-10-2025 (sepuluh Oktober duaribu duapuluh lima)">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (HH.MM)</label>
                    <input type="text" id="param_jam_akta" class="form-control form-control-sm" value="14.30">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (Terbilang)</label>
                    <input type="text" id="param_jam_akta_terbilang" class="form-control form-control-sm" value="empatbelas lebih tigapuluh menit">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Penjual (Pihak 1)</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_1" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_1" class="form-control form-control-sm" value="Sungai Ulin, 10-02-1992">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_1" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_1" class="form-control form-control-sm" value="3217091002920015">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pembeli (Pihak 2)</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_2" class="form-control form-control-sm" value="INDRA SAFARI">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_2" class="form-control form-control-sm" value="Pontianak, 09-04-2004">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_2" class="form-control form-control-sm" rows="2">Jalan Merdeka Barat Gang Belibis Nomor 17, Rukun Tetangga 003, Rukun Warga 008, Kelurahan Tengah, Kecamatan Pontianak Kota, Kota Pontianak</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_2" class="form-control form-control-sm" value="6171050904040002">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Transaksi & Objek</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nomor Sertifikat</label>
                    <input type="text" id="param_nomor_sertifikat" class="form-control form-control-sm" value="SHM No. 4415/Kubu Raya">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Luas Tanah (m2)</label>
                    <input type="text" id="param_luas_tanah" class="form-control form-control-sm" value="300">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Lokasi Objek</label>
                    <textarea id="param_lokasi_tanah" class="form-control form-control-sm" rows="2">Desa Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Harga Transaksi</label>
                    <input type="text" id="param_harga_transaksi" class="form-control form-control-sm" value="Rp 500.000.000,- (limaratus juta rupiah)">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi-Saksi</h6>
                <div class="mb-2">
                    <label class="small text-muted">Saksi 1</label>
                    <textarea id="param_saksi_1" class="form-control form-control-sm" rows="2">Tuan IKHLASUL IMAM SYAWALUDDIN, lahir di Pontianak, pada tanggal 13-02-1999 (tigabelas Februari seribu sembilanratus sembilanpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Apel Gang Langsat Nomor 31 B, Rukun Tetangga 004, Rukun Warga 020, Kelurahan Sungai Jawi Luar, Kecamatan Pontianak Barat, Kota Pontianak, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6171031302990006</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Saksi 2</label>
                    <textarea id="param_saksi_2" class="form-control form-control-sm" rows="2">Nonya DEWI DAYATI, lahir di Ketapang, pada tanggal 25-06-1979 (duapuluh lima Juni seribu sembilanratus tujuhpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Tanggul Laut, Rukun Tetangga 013, Rukun Warga 010, Kelurahan Sungai Rengas, Kecamatan Sungai Kakap, Kabupaten Kubu Raya, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6112096506790006</textarea>
                </div>
            `;
        } else if (selectedDraft === 'legalisasi') {
            // Legalisasi Fields
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Legalisasi</label>
                    <input type="text" id="param_nomor_akta" class="form-control form-control-sm" value="120/L/Not/2025">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Legalisasi</label>
                    <input type="text" id="param_hari_tanggal_akta" class="form-control form-control-sm" value="Senin, tanggal 13-10-2025 (tigabelas Oktober duaribu duapuluh lima)">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Dokumen</label>
                    <input type="text" id="param_nama_dokumen" class="form-control form-control-sm" value="Surat Pernyataan Persetujuan Ahli Waris">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pihak yang Menandatangani</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_1" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_1" class="form-control form-control-sm" value="Sungai Ulin, 10-02-1992">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_1" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_1" class="form-control form-control-sm" value="3217091002920015">
                </div>
            `;
        } else {
            // Default Generic Fields
            html = `
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor Akta</label>
                    <input type="text" id="param_nomor_akta" class="form-control form-control-sm" value="01">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Hari & Tanggal Terbit</label>
                    <input type="text" id="param_hari_tanggal_akta" class="form-control form-control-sm" value="Rabu, tanggal 08-10-2025 (delapan Oktober duaribu duapuluh lima)">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (HH.MM)</label>
                    <input type="text" id="param_jam_akta" class="form-control form-control-sm" value="15.22">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Jam Terbit (Terbilang)</label>
                    <input type="text" id="param_jam_akta_terbilang" class="form-control form-control-sm" value="limabelas lebih duapuluh dua menit">
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Pihak Penghadap</h6>
                <div class="mb-2">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" id="param_nama_pihak_1" class="form-control form-control-sm" value="ANDREA ANGGANA">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">TTL</label>
                    <input type="text" id="param_ttl_pihak_1" class="form-control form-control-sm" value="Sungai Ulin, 10-02-1992">
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Alamat</label>
                    <textarea id="param_alamat_pihak_1" class="form-control form-control-sm" rows="2">Komplek Grand Milenial Blok C 13, Rukun Tetangga 008, Rukun Warga 003, Kelurahan Pal Sembilan, Kecamatan Sungai Kakap, Kabupaten Kubu Raya</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">NIK</label>
                    <input type="text" id="param_nik_pihak_1" class="form-control form-control-sm" value="3217091002920015">
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Isi Utama Akta</label>
                    <textarea id="param_isi_akta_umum" class="form-control form-control-sm" rows="3">Bahwa para pihak dengan ini menyatakan bersedia melakukan kesepakatan bersama secara hukum Notaris.</textarea>
                </div>
                <hr>
                <h6 class="fw-bold text-secondary small mb-2">Saksi-Saksi</h6>
                <div class="mb-2">
                    <label class="small text-muted">Saksi 1</label>
                    <textarea id="param_saksi_1" class="form-control form-control-sm" rows="2">Tuan IKHLASUL IMAM SYAWALUDDIN, lahir di Pontianak, pada tanggal 13-02-1999 (tigabelas Februari seribu sembilanratus sembilanpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Apel Gang Langsat Nomor 31 B, Rukun Tetangga 004, Rukun Warga 020, Kelurahan Sungai Jawi Luar, Kecamatan Pontianak Barat, Kota Pontianak, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6171031302990006</textarea>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Saksi 2</label>
                    <textarea id="param_saksi_2" class="form-control form-control-sm" rows="2">Nonya DEWI DAYATI, lahir di Ketapang, pada tanggal 25-06-1979 (duapuluh lima Juni seribu sembilanratus tujuhpuluh sembilan), Warga Negara Indonesia, Pegawai Kantor Notaris, bertempat tinggal di Jalan Tanggul Laut, Rukun Tetangga 013, Rukun Warga 010, Kelurahan Sungai Rengas, Kecamatan Sungai Kakap, Kabupaten Kubu Raya, Provinsi Kalimantan Barat, pemegang Kartu Tanda Penduduk dengan Nomor Induk Kependudukan : 6112096506790006</textarea>
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

            // Render fields
            renderFields();

            // Function to compile template
            function compileDeed() {
                const selectedDraft = document.getElementById('select-jenis-draft').value;
                let template = '';
                let namaForm = '';

                const nomor = document.getElementById('param_nomor_akta').value;
                const hariTgl = document.getElementById('param_hari_tanggal_akta').value;

                if (selectedDraft === 'pt') {
                    template = window.templatePT;
                    namaForm = document.getElementById('param_nama_pt').value;

                    const jam = document.getElementById('param_jam_akta').value;
                    const jamTerbilang = document.getElementById('param_jam_akta_terbilang').value;
                    
                    const namaPendiri1 = document.getElementById('param_nama_pendiri_1').value;
                    const ttl1 = document.getElementById('param_ttl_pendiri_1').value;
                    const alamat1 = document.getElementById('param_alamat_pendiri_1').value;
                    const nik1 = document.getElementById('param_nik_pendiri_1').value;

                    const namaPendiri2 = document.getElementById('param_nama_pendiri_2').value;
                    const ttl2 = document.getElementById('param_ttl_pendiri_2').value;
                    const alamat2 = document.getElementById('param_alamat_pendiri_2').value;
                    const nik2 = document.getElementById('param_nik_pendiri_2').value;

                    const kedudukan = document.getElementById('param_kedudukan_pt').value;
                    const modalDasar = document.getElementById('param_modal_dasar').value;
                    const sahamDasar = document.getElementById('param_jumlah_saham_modal_dasar').value;
                    const nominalSaham = document.getElementById('param_nominal_per_saham').value;
                    const modalDisetor = document.getElementById('param_modal_disetor').value;
                    const sahamDisetor = document.getElementById('param_jumlah_saham_modal_disetor').value;

                    const saham1 = document.getElementById('param_saham_pendiri_1').value;
                    const saham2 = document.getElementById('param_saham_pendiri_2').value;

                    const saksi1 = document.getElementById('param_saksi_1').value;
                    const saksi2 = document.getElementById('param_saksi_2').value;

                    // Perform substitutions
                    template = template.replaceAll('[NAMA_PT]', namaForm);
                    template = template.replaceAll('[NAMA_PT_RAW]', namaForm.replace('PT. ', '').replace('PT ', ''));
                    template = template.replaceAll('[NOMOR_AKTA]', nomor);
                    template = template.replaceAll('[HARI_TANGGAL_AKTA]', hariTgl);
                    template = template.replaceAll('[JAM_AKTA]', jam);
                    template = template.replaceAll('[JAM_AKTA_TERBILANG]', jamTerbilang);

                    template = template.replaceAll('[NAMA_PENDIRI_1]', namaPendiri1);
                    template = template.replaceAll('[TTL_PENDIRI_1]', ttl1);
                    template = template.replaceAll('[ALAMAT_PENDIRI_1]', alamat1);
                    template = template.replaceAll('[NIK_PENDIRI_1]', nik1);

                    template = template.replaceAll('[NAMA_PENDIRI_2]', namaPendiri2);
                    template = template.replaceAll('[TTL_PENDIRI_2]', ttl2);
                    template = template.replaceAll('[ALAMAT_PENDIRI_2]', alamat2);
                    template = template.replaceAll('[NIK_PENDIRI_2]', nik2);

                    template = template.replaceAll('[KEDUDUKAN_PT]', kedudukan);
                    template = template.replaceAll('[MODAL_DASAR]', modalDasar);
                    template = template.replaceAll('[JUMLAH_SAHAM_MODAL_DASAR]', sahamDasar);
                    template = template.replaceAll('[NOMINAL_PER_SAHAM]', nominalSaham);
                    template = template.replaceAll('[MODAL_DISETOR]', modalDisetor);
                    template = template.replaceAll('[JUMLAH_SAHAM_MODAL_DISETOR]', sahamDisetor);

                    template = template.replaceAll('[SAHAM_PENDIRI_1]', saham1);
                    template = template.replaceAll('[SAHAM_PENDIRI_2]', saham2);

                    template = template.replaceAll('[SAKSI_1]', saksi1);
                    template = template.replaceAll('[SAKSI_2]', saksi2);

                } else if (selectedDraft === 'hibah') {
                    template = window.templateHibah;
                    namaForm = "Akta Hibah - " + document.getElementById('param_nama_pihak_1').value;

                    const jam = document.getElementById('param_jam_akta').value;
                    const jamTerbilang = document.getElementById('param_jam_akta_terbilang').value;

                    const nama1 = document.getElementById('param_nama_pihak_1').value;
                    const ttl1 = document.getElementById('param_ttl_pihak_1').value;
                    const alamat1 = document.getElementById('param_alamat_pihak_1').value;
                    const nik1 = document.getElementById('param_nik_pihak_1').value;

                    const nama2 = document.getElementById('param_nama_pihak_2').value;
                    const ttl2 = document.getElementById('param_ttl_pihak_2').value;
                    const alamat2 = document.getElementById('param_alamat_pihak_2').value;
                    const nik2 = document.getElementById('param_nik_pihak_2').value;

                    const sertifikat = document.getElementById('param_nomor_sertifikat').value;
                    const luas = document.getElementById('param_luas_tanah').value;
                    const lokasi = document.getElementById('param_lokasi_tanah').value;

                    const saksi1 = document.getElementById('param_saksi_1').value;
                    const saksi2 = document.getElementById('param_saksi_2').value;

                    template = template.replaceAll('[NOMOR_AKTA]', nomor);
                    template = template.replaceAll('[HARI_TANGGAL_AKTA]', hariTgl);
                    template = template.replaceAll('[JAM_AKTA]', jam);
                    template = template.replaceAll('[JAM_AKTA_TERBILANG]', jamTerbilang);

                    template = template.replaceAll('[NAMA_PIHAK_1]', nama1);
                    template = template.replaceAll('[TTL_PIHAK_1]', ttl1);
                    template = template.replaceAll('[ALAMAT_PIHAK_1]', alamat1);
                    template = template.replaceAll('[NIK_PIHAK_1]', nik1);

                    template = template.replaceAll('[NAMA_PIHAK_2]', nama2);
                    template = template.replaceAll('[TTL_PIHAK_2]', ttl2);
                    template = template.replaceAll('[ALAMAT_PIHAK_2]', alamat2);
                    template = template.replaceAll('[NIK_PIHAK_2]', nik2);

                    template = template.replaceAll('[NOMOR_SERTIFIKAT]', sertifikat);
                    template = template.replaceAll('[LUAS_TANAH]', luas);
                    template = template.replaceAll('[LOKASI_TANAH]', lokasi);

                    template = template.replaceAll('[SAKSI_1]', saksi1);
                    template = template.replaceAll('[SAKSI_2]', saksi2);

                } else if (selectedDraft === 'ajb') {
                    template = window.templateAJB;
                    namaForm = "AJB - " + document.getElementById('param_nama_pihak_2').value;

                    const jam = document.getElementById('param_jam_akta').value;
                    const jamTerbilang = document.getElementById('param_jam_akta_terbilang').value;

                    const nama1 = document.getElementById('param_nama_pihak_1').value;
                    const ttl1 = document.getElementById('param_ttl_pihak_1').value;
                    const alamat1 = document.getElementById('param_alamat_pihak_1').value;
                    const nik1 = document.getElementById('param_nik_pihak_1').value;

                    const nama2 = document.getElementById('param_nama_pihak_2').value;
                    const ttl2 = document.getElementById('param_ttl_pihak_2').value;
                    const alamat2 = document.getElementById('param_alamat_pihak_2').value;
                    const nik2 = document.getElementById('param_nik_pihak_2').value;

                    const sertifikat = document.getElementById('param_nomor_sertifikat').value;
                    const luas = document.getElementById('param_luas_tanah').value;
                    const lokasi = document.getElementById('param_lokasi_tanah').value;
                    const harga = document.getElementById('param_harga_transaksi').value;

                    const saksi1 = document.getElementById('param_saksi_1').value;
                    const saksi2 = document.getElementById('param_saksi_2').value;

                    template = template.replaceAll('[NOMOR_AKTA]', nomor);
                    template = template.replaceAll('[HARI_TANGGAL_AKTA]', hariTgl);
                    template = template.replaceAll('[JAM_AKTA]', jam);
                    template = template.replaceAll('[JAM_AKTA_TERBILANG]', jamTerbilang);

                    template = template.replaceAll('[NAMA_PIHAK_1]', nama1);
                    template = template.replaceAll('[TTL_PIHAK_1]', ttl1);
                    template = template.replaceAll('[ALAMAT_PIHAK_1]', alamat1);
                    template = template.replaceAll('[NIK_PIHAK_1]', nik1);

                    template = template.replaceAll('[NAMA_PIHAK_2]', nama2);
                    template = template.replaceAll('[TTL_PIHAK_2]', ttl2);
                    template = template.replaceAll('[ALAMAT_PIHAK_2]', alamat2);
                    template = template.replaceAll('[NIK_PIHAK_2]', nik2);

                    template = template.replaceAll('[NOMOR_SERTIFIKAT]', sertifikat);
                    template = template.replaceAll('[LUAS_TANAH]', luas);
                    template = template.replaceAll('[LOKASI_TANAH]', lokasi);
                    template = template.replaceAll('[HARGA_TRANSAKSI]', harga);

                    template = template.replaceAll('[SAKSI_1]', saksi1);
                    template = template.replaceAll('[SAKSI_2]', saksi2);

                } else if (selectedDraft === 'legalisasi') {
                    template = window.templateLegalisasi;
                    const docName = document.getElementById('param_nama_dokumen').value;
                    namaForm = "Legalisasi - " + docName;

                    const nama1 = document.getElementById('param_nama_pihak_1').value;
                    const ttl1 = document.getElementById('param_ttl_pihak_1').value;
                    const alamat1 = document.getElementById('param_alamat_pihak_1').value;
                    const nik1 = document.getElementById('param_nik_pihak_1').value;

                    template = template.replaceAll('[NOMOR_AKTA]', nomor);
                    template = template.replaceAll('[HARI_TANGGAL_AKTA]', hariTgl);
                    template = template.replaceAll('[NAMA_DOKUMEN]', docName);

                    template = template.replaceAll('[NAMA_PIHAK_1]', nama1);
                    template = template.replaceAll('[TTL_PIHAK_1]', ttl1);
                    template = template.replaceAll('[ALAMAT_PIHAK_1]', alamat1);
                    template = template.replaceAll('[NIK_PIHAK_1]', nik1);

                } else {
                    template = window.templateDefault;
                    namaForm = "Akta Notaris - " + document.getElementById('param_nama_pihak_1').value;

                    const jam = document.getElementById('param_jam_akta').value;
                    const jamTerbilang = document.getElementById('param_jam_akta_terbilang').value;

                    const nama1 = document.getElementById('param_nama_pihak_1').value;
                    const ttl1 = document.getElementById('param_ttl_pihak_1').value;
                    const alamat1 = document.getElementById('param_alamat_pihak_1').value;
                    const nik1 = document.getElementById('param_nik_pihak_1').value;
                    const isiUmum = document.getElementById('param_isi_akta_umum').value;

                    const saksi1 = document.getElementById('param_saksi_1').value;
                    const saksi2 = document.getElementById('param_saksi_2').value;

                    template = template.replaceAll('[NOMOR_AKTA]', nomor);
                    template = template.replaceAll('[HARI_TANGGAL_AKTA]', hariTgl);
                    template = template.replaceAll('[JAM_AKTA]', jam);
                    template = template.replaceAll('[JAM_AKTA_TERBILANG]', jamTerbilang);

                    template = template.replaceAll('[NAMA_PIHAK_1]', nama1);
                    template = template.replaceAll('[TTL_PIHAK_1]', ttl1);
                    template = template.replaceAll('[ALAMAT_PIHAK_1]', alamat1);
                    template = template.replaceAll('[NIK_PIHAK_1]', nik1);
                    template = template.replaceAll('[ISI_AKTA_UMUM]', isiUmum);

                    template = template.replaceAll('[SAKSI_1]', saksi1);
                    template = template.replaceAll('[SAKSI_2]', saksi2);
                }

                // Set CKEditor content
                window.editor.setData(template);

                // Auto populate main fields
                document.querySelector('input[name="nomor_akta"]').value = nomor;
                document.querySelector('input[name="nama_akta"]').value = namaForm;
            }

            // Auto-select draft type based on request's service name
            const selectDraft = document.getElementById('select-jenis-draft');
            const lowLayanan = layananName.toLowerCase();
            
            if (lowLayanan.includes('pt') || lowLayanan.includes('cv')) {
                selectDraft.value = 'pt';
            } else if (lowLayanan.includes('hibah')) {
                selectDraft.value = 'hibah';
            } else if (lowLayanan.includes('jual') || lowLayanan.includes('ajb')) {
                selectDraft.value = 'ajb';
            } else if (lowLayanan.includes('legalisasi')) {
                selectDraft.value = 'legalisasi';
            } else {
                selectDraft.value = 'pt'; // Default to PT so the user gets the 30-page deed by default!
            }

            // Render fields initially based on the auto-selected dropdown value
            renderFields();

            // Automatically compile on load!
            compileDeed();

            // Bind change event to dropdown to dynamically update fields and template
            selectDraft.addEventListener('change', function() {
                renderFields();
                compileDeed();
            });

            // Bind click event to apply button
            document.getElementById('btnApplyTemplate').addEventListener('click', compileDeed);
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
