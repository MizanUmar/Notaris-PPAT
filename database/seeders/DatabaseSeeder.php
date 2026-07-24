<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Layanan;
use App\Models\PersyaratanDokumen;
use App\Models\ProfilKantor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1. Seed Users
            $admin = User::create([
                'username' => 'admin',
                'nama' => 'Administrator',
                'password' => 'admin123', // auto-hashed by casts in User model
                'role' => 'admin',
                'email' => 'admin@eka-notaris.com',
            ]);

            $notaris = User::create([
                'username' => 'notaris',
                'nama' => 'Eka Sulistya, S.H., M.Kn.',
                'password' => 'notaris123',
                'role' => 'notaris',
                'email' => 'eka.sulistya@eka-notaris.com',
            ]);

            $clientUser = User::create([
                'username' => 'client',
                'nama' => 'Putri Alya Fadhilah',
                'password' => 'client123',
                'role' => 'client',
                'email' => 'putrialya@example.com',
            ]);

            // 2. Seed Client Profile
            Client::create([
                'user_id' => $clientUser->id,
                'nik' => '3202316139',
                'no_hp' => '081234567890',
                'email' => 'putrialya@example.com',
                'alamat' => 'Jalan Pangeran Natakusuma, Pontianak',
            ]);

            // 3. Seed Profil Kantor
            ProfilKantor::create([
                'nama_kantor' => 'Kantor Notaris & PPAT Eka Sulistya, S.H., M.Kn.',
                'alamat' => 'Jalan Pangeran Natakusuma, Kota Pontianak, Kalimantan Barat. Jam Operasional: Senin - Jumat (08:00 - 16:00 WIB)',
                'no_telepon' => '0812-3456-7890',
                'email' => 'info@eka-notaris.com',
                'logo' => null,
            ]);

            // 4. Seed Layanan & Persyaratan
            // AJB
            $layanan1 = Layanan::create([
                'nama_layanan' => 'Akta Jual Beli (AJB)',
                'deskripsi' => 'Pembuatan akta jual beli tanah dan/atau bangunan untuk peralihan hak atas tanah PPAT.',
                'estimasi_waktu' => '3 - 5 Hari Kerja',
                'status_aktif' => true,
            ]);

            $reqs1 = [
                'KTP Suami/Istri (Penjual & Pembeli)',
                'Kartu Keluarga (Penjual & Pembeli)',
                'Sertifikat Tanah Asli (untuk pengecekan)',
                'Pajak Bumi dan Bangunan (PBB) Tahun Terakhir (Lunas)',
                'Surat Nikah/Cerai (Jika Ada)',
                'NPWP Penjual & Pembeli',
            ];
            foreach ($reqs1 as $req) {
                PersyaratanDokumen::create([
                    'layanan_id' => $layanan1->id,
                    'nama_dokumen' => $req,
                    'keterangan' => 'Fotokopi / Scan Asli',
                ]);
            }

            // PT/CV
            $layanan2 = Layanan::create([
                'nama_layanan' => 'Pendirian PT / CV',
                'deskripsi' => 'Pengurusan akta pendirian badan usaha PT maupun CV beserta SK Kemenkumham.',
                'estimasi_waktu' => '7 - 10 Hari Kerja',
                'status_aktif' => true,
            ]);

            $reqs2 = [
                'KTP Para Pendiri / Pemegang Saham',
                'NPWP Para Pendiri',
                'Rencana Nama Perusahaan (Minimal 3 opsi)',
                'Rencana Bidang Usaha (KBLI)',
                'Bukti Kepemilikan/Sewa Alamat Kantor',
            ];
            foreach ($reqs2 as $req) {
                PersyaratanDokumen::create([
                    'layanan_id' => $layanan2->id,
                    'nama_dokumen' => $req,
                    'keterangan' => 'Fotokopi / Scan Asli',
                ]);
            }

            // Hibah
            $layanan3 = Layanan::create([
                'nama_layanan' => 'Akta Hibah',
                'deskripsi' => 'Pembuatan akta hibah tanah atau bangunan dari pemberi hibah kepada penerima hibah.',
                'estimasi_waktu' => '3 - 5 Hari Kerja',
                'status_aktif' => true,
            ]);

            $reqs3 = [
                'KTP & KK Pemberi Hibah (Orang Tua/Keluarga)',
                'KTP & KK Penerima Hibah (Anak/Penerima)',
                'Sertifikat Tanah Asli',
                'PBB Tahun Terakhir (Lunas)',
                'Surat Persetujuan Ahli Waris Lain (jika diperlukan)',
            ];
            foreach ($reqs3 as $req) {
                PersyaratanDokumen::create([
                    'layanan_id' => $layanan3->id,
                    'nama_dokumen' => $req,
                    'keterangan' => 'Fotokopi / Scan Asli',
                ]);
            }

            // Legalisasi
            $layanan4 = Layanan::create([
                'nama_layanan' => 'Legalisasi Dokumen',
                'deskripsi' => 'Mengesahkan tanda tangan para pihak pada surat di bawah tangan yang ditandatangani di hadapan Notaris.',
                'estimasi_waktu' => '1 Hari Kerja',
                'status_aktif' => true,
            ]);

            $reqs4 = [
                'Dokumen Asli yang akan dilegalisasi',
                'KTP Pemilik Dokumen / Para Pihak yang bertanda tangan',
            ];
            foreach ($reqs4 as $req) {
                PersyaratanDokumen::create([
                    'layanan_id' => $layanan4->id,
                    'nama_dokumen' => $req,
                    'keterangan' => 'Dokumen Asli dibawa saat penandatanganan',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
