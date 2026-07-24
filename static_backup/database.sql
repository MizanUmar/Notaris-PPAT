-- SQL Database Script untuk Sistem Informasi Manajemen Notaris & PPAT
-- Eka Sulistya, S.H., M.Kn.
-- Dibuat untuk: Putri Alya Fadhilah (NIM: 3202316139)
-- DBMS: MySQL / MariaDB

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `buku_tamu`;
DROP TABLE IF EXISTS `informasi_layanan`;
DROP TABLE IF EXISTS `profil_kantor`;
DROP TABLE IF EXISTS `surat`;
DROP TABLE IF EXISTS `akta`;
DROP TABLE IF EXISTS `dokumen_client`;
DROP TABLE IF EXISTS `permintaan_layanan`;
DROP TABLE IF EXISTS `persyaratan_dokumen`;
DROP TABLE IF EXISTS `layanan`;
DROP TABLE IF EXISTS `client`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. TABEL USERS (Login Multi-role)
CREATE TABLE `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(150) NOT NULL,
  `role` ENUM('admin', 'notaris', 'client') NOT NULL DEFAULT 'client',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. TABEL CLIENT (Biodata Client)
CREATE TABLE `client` (
  `id_client` INT(11) NOT NULL AUTO_INCREMENT,
  `id_user` INT(11) NOT NULL,
  `nik` VARCHAR(20) NOT NULL UNIQUE,
  `no_hp` VARCHAR(15) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `alamat` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_client`),
  CONSTRAINT `fk_client_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TABEL LAYANAN (Daftar Layanan Notaris & PPAT)
CREATE TABLE `layanan` (
  `id_layanan` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_layanan` VARCHAR(100) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `estimasi_waktu` VARCHAR(100) NOT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TABEL PERSYARATAN_DOKUMEN (Dokumen yang Wajib Disiapkan per Layanan)
CREATE TABLE `persyaratan_dokumen` (
  `id_persyaratan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_layanan` INT(11) NOT NULL,
  `nama_dokumen` VARCHAR(100) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_persyaratan`),
  CONSTRAINT `fk_persyaratan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. TABEL PERMINTAAN_LAYANAN (Transaksi Pengajuan Layanan oleh Client)
CREATE TABLE `permintaan_layanan` (
  `id_permintaan` INT(11) NOT NULL AUTO_INCREMENT,
  `id_client` INT(11) NOT NULL,
  `id_layanan` INT(11) NOT NULL,
  `tanggal_permintaan` DATE NOT NULL,
  `status` ENUM('Menunggu', 'Diproses', 'Selesai', 'Ditolak') NOT NULL DEFAULT 'Menunggu',
  `keterangan` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permintaan`),
  CONSTRAINT `fk_permintaan_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_permintaan_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. TABEL DOKUMEN_CLIENT (Berkas Lampiran yang Diunggah Client)
CREATE TABLE `dokumen_client` (
  `id_dokumen` INT(11) NOT NULL AUTO_INCREMENT,
  `id_permintaan` INT(11) NOT NULL,
  `nama_file` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `tanggal_upload` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  CONSTRAINT `fk_dokumen_permintaan` FOREIGN KEY (`id_permintaan`) REFERENCES `permintaan_layanan` (`id_permintaan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. TABEL AKTA (Arsip Akta Resmi Hasil Permintaan Layanan)
CREATE TABLE `akta` (
  `id_akta` INT(11) NOT NULL AUTO_INCREMENT,
  `id_permintaan` INT(11) NOT NULL,
  `nomor_akta` VARCHAR(100) NOT NULL,
  `nama_akta` VARCHAR(100) NOT NULL,
  `tanggal_akta` DATE NOT NULL,
  `file_akta` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_akta`),
  CONSTRAINT `fk_akta_permintaan` FOREIGN KEY (`id_permintaan`) REFERENCES `permintaan_layanan` (`id_permintaan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. TABEL SURAT (Arsip Surat Resmi)
CREATE TABLE `surat` (
  `id_surat` INT(11) NOT NULL AUTO_INCREMENT,
  `id_permintaan` INT(11) DEFAULT NULL,
  `nomor_surat` VARCHAR(100) NOT NULL,
  `jenis_surat` VARCHAR(100) NOT NULL,
  `tanggal_surat` DATE NOT NULL,
  `file_surat` VARCHAR(255) NOT NULL,
  `keterangan` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_surat`),
  CONSTRAINT `fk_surat_permintaan` FOREIGN KEY (`id_permintaan`) REFERENCES `permintaan_layanan` (`id_permintaan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. TABEL PROFIL_KANTOR (Informasi Profil Standalone)
CREATE TABLE `profil_kantor` (
  `id_profil` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kantor` VARCHAR(150) NOT NULL,
  `alamat` TEXT NOT NULL,
  `no_telepon` VARCHAR(15) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. TABEL INFORMASI_LAYANAN (Pengumuman Terkait Layanan)
CREATE TABLE `informasi_layanan` (
  `id_informasi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_layanan` INT(11) NOT NULL,
  `judul` VARCHAR(150) NOT NULL,
  `isi_informasi` TEXT NOT NULL,
  `tanggal` DATE NOT NULL,
  PRIMARY KEY (`id_informasi`),
  CONSTRAINT `fk_informasi_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. TABEL BUKU_TAMU (Pencatatan Kunjungan Fisik / QR Code)
CREATE TABLE `buku_tamu` (
  `id_tamu` INT(11) NOT NULL AUTO_INCREMENT,
  `id_client` INT(11) DEFAULT NULL,
  `nama_tamu` VARCHAR(150) NOT NULL,
  `instansi` VARCHAR(150) NOT NULL,
  `nomor_hp` VARCHAR(20) NOT NULL,
  `keperluan` TEXT NOT NULL,
  `tanggal_kunjungan` DATETIME NOT NULL,
  `qr_code` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tamu`),
  CONSTRAINT `fk_bukutamu_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ================= SEEDING DATA SIMULASI / DUMMY =================

-- Dummy Users (Password default: '123' tersimpan polos atau ter-hash, untuk dummy menggunakan plain text)
INSERT INTO `users` (`id_user`, `username`, `password`, `nama`, `role`) VALUES
(1, 'notaris', '123', 'Eka Sulistya, S.H., M.Kn.', 'notaris'),
(2, 'alya', '123', 'Putri Alya Fadhilah', 'client'),
(3, 'budi', '123', 'Budi Setiawan', 'client');

-- Dummy Clients
INSERT INTO `client` (`id_client`, `id_user`, `nik`, `no_hp`, `email`, `alamat`, `created_at`) VALUES
(1, 2, '3202316139', '08123456789', 'alya@gmail.com', 'Jl. Pangeran Natakusuma No. 45, Pontianak', '2026-06-10 10:00:00'),
(2, 3, '3202316140', '081299998888', 'budi@yahoo.com', 'Jl. Ahmad Yani No. 12, Pontianak', '2026-07-01 11:30:00');

-- Dummy Layanan
INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `deskripsi`, `estimasi_waktu`, `status_aktif`) VALUES
(1, 'Akta Jual Beli (AJB)', 'Akta autentik yang membuktikan peralihan hak atas tanah dan bangunan karena jual beli.', '7-14 Hari Kerja', 1),
(2, 'Pendirian PT / CV', 'Akta pendirian badan hukum usaha berstruktur persekutuan komanditer atau perseroan terbatas.', '5-10 Hari Kerja', 1),
(3, 'Akta Hibah', 'Akta pemberian hak atas tanah atau bangunan secara cuma-cuma kepada ahli waris/penerima.', '5-10 Hari Kerja', 1),
(4, 'Legalisasi Dokumen', 'Pencocokan dokumen fotokopi dengan dokumen asli serta pengesahan tanda tangan.', '1 Hari Kerja', 1),
(5, 'Surat Kuasa', 'Surat formal pemberian kewenangan tindakan tertentu kepada penerima kuasa.', '2-4 Hari Kerja', 1),
(6, 'Surat Pernyataan', 'Surat deklarasi resmi di atas meterai mengenai suatu fakta hukum.', '1-2 Hari Kerja', 1);

-- Dummy Persyaratan Dokumen
INSERT INTO `persyaratan_dokumen` (`id_persyaratan`, `id_layanan`, `nama_dokumen`, `keterangan`) VALUES
-- AJB
(1, 1, 'Fotokopi KTP Suami Istri (Penjual & Pembeli)', 'KTP harus aktif'),
(2, 1, 'Fotokopi Kartu Keluarga (KK) Penjual & Pembeli', NULL),
(3, 1, 'Sertifikat Tanah Asli', 'Akan diverifikasi ke BPN'),
(4, 1, 'PBB 5 Tahun Terakhir', 'Disertai bukti pembayaran lunas'),
(5, 1, 'Fotokopi NPWP Penjual & Pembeli', NULL),
-- PT/CV
(6, 2, 'KTP Pendiri (Minimal 2 Orang)', NULL),
(7, 2, 'Fotokopi NPWP Pendiri', NULL),
(8, 2, 'Rencana Nama Usaha', 'Siapkan 3 pilihan nama PT'),
(9, 2, 'Struktur Modal & Saham', 'Modal dasar dan modal disetor'),
-- Hibah
(10, 3, 'Sertifikat Tanah Asli', NULL),
(11, 3, 'KTP & KK Pemberi & Penerima Hibah', NULL),
(12, 3, 'Surat Persetujuan Ahli Waris', 'Wajib ditandatangani seluruh anak kandung');

-- Dummy Permintaan Layanan
INSERT INTO `permintaan_layanan` (`id_permintaan`, `id_client`, `id_layanan`, `tanggal_permintaan`, `status`, `keterangan`, `created_at`) VALUES
(101, 1, 2, '2026-07-12', 'Diproses', 'Draf pendirian PT sedang disusun oleh staf Notaris. Menunggu jadwal tanda tangan.', '2026-07-12 09:15:00'),
(102, 2, 1, '2026-07-05', 'Selesai', 'Akta Jual Beli telah ditandatangani dan diserahkan kepada pembeli.', '2026-07-05 14:00:00'),
(103, 1, 4, '2026-07-15', 'Menunggu', 'Menunggu pemeriksaan kelengkapan berkas asli oleh Notaris.', '2026-07-15 08:30:00');

-- Dummy Dokumen Client
INSERT INTO `dokumen_client` (`id_dokumen`, `id_permintaan`, `nama_file`, `file_path`, `tanggal_upload`) VALUES
(1, 101, 'KTP_Alya.pdf', 'uploads/client_1/KTP_Alya.pdf', '2026-07-12 09:16:00'),
(2, 101, 'NPWP_Alya.pdf', 'uploads/client_1/NPWP_Alya.pdf', '2026-07-12 09:16:00'),
(3, 102, 'KTP_Budi.pdf', 'uploads/client_2/KTP_Budi.pdf', '2026-07-05 14:02:00'),
(4, 102, 'Sertifikat_SHM.pdf', 'uploads/client_2/Sertifikat_SHM.pdf', '2026-07-05 14:03:00');

-- Dummy Akta
INSERT INTO `akta` (`id_akta`, `id_permintaan`, `nomor_akta`, `nama_akta`, `tanggal_akta`, `file_akta`) VALUES
(501, 102, '45/Notaris/2026', 'Akta Jual Beli Tanah Budi & Roni', '2026-07-10', 'AJB_Budi_Roni_Signed.pdf');

-- Dummy Surat
INSERT INTO `surat` (`id_surat`, `id_permintaan`, `nomor_surat`, `jenis_surat`, `tanggal_surat`, `file_surat`, `keterangan`) VALUES
(601, 101, '189/SK-ES/VII/2026', 'Surat Keterangan Pengurusan PT', '2026-07-13', 'Surat_Ket_PT_Alya.pdf', 'Surat keterangan sementara untuk pembukaan rekening bank.');

-- Dummy Profil Kantor
INSERT INTO `profil_kantor` (`id_profil`, `nama_kantor`, `alamat`, `no_telepon`, `email`, `logo`) VALUES
(1, 'Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn.', 'Jalan Pangeran Natakusuma, Kec. Pontianak Kota, Kota Pontianak, Kalimantan Barat', '+62 812-3456-7890', 'notaris.ekasulistya@gmail.com', 'logo.png');

-- Dummy Buku Tamu Kunjungan
INSERT INTO `buku_tamu` (`id_tamu`, `id_client`, `nama_tamu`, `instansi`, `nomor_hp`, `keperluan`, `tanggal_kunjungan`, `qr_code`, `created_at`) VALUES
(1, 2, 'Budi Setiawan', 'PT. Sentosa Baru', '081299998888', 'Menyerahkan sertifikat tanah asli untuk AJB', '2026-07-05 09:30:00', 'QR_BUDI_1', '2026-07-05 09:30:00'),
(2, 2, 'Budi Setiawan', 'PT. Sentosa Baru', '081299998888', 'Penandatanganan Minuta Akta Jual Beli', '2026-07-10 11:00:00', 'QR_BUDI_2', '2026-07-10 11:00:00'),
(3, NULL, 'Hendra Wijaya', 'Pribadi', '085388112233', 'Konsultasi waris dan pembagian hak tanah', '2026-07-14 13:15:00', 'QR_HENDRA_3', '2026-07-14 13:15:00'),
(4, 1, 'Putri Alya Fadhilah', 'Politeknik Negeri Pontianak', '08123456789', 'Konsultasi akta pendirian CV usaha mahasiswa', '2026-07-16 10:00:00', 'QR_ALYA_4', '2026-07-16 10:00:00');
