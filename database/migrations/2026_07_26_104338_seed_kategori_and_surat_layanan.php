<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tandai layanan yang sudah ada sebagai kategori 'akta'
        DB::table('layanan')->whereIn('nama_layanan', [
            'Akta Hibah',
            'Akta Jual Beli (AJB)',
            'Legalisasi Dokumen',
            'Pendirian PT / CV',
        ])->update(['kategori' => 'akta']);

        // Tambahkan layanan baru bertipe surat
        DB::table('layanan')->insert([
            [
                'nama_layanan'   => 'Surat Kuasa',
                'kategori'       => 'surat',
                'deskripsi'      => 'Pembuatan surat kuasa untuk pengurusan dokumen di instansi terkait.',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'status_aktif'   => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_layanan'   => 'Surat Pernyataan Penguasaan Fisik Tanah Dan Tidak Sengketa',
                'kategori'       => 'surat',
                'deskripsi'      => 'Surat pernyataan penguasaan fisik atas tanah yang tidak dalam sengketa.',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'status_aktif'   => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_layanan'   => 'Surat Pernyataan Pemasangan Tanda-Tanda Batas',
                'kategori'       => 'surat',
                'deskripsi'      => 'Surat pernyataan pemasangan tanda batas tanah untuk keperluan pengukuran.',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'status_aktif'   => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nama_layanan'   => 'Surat Keterangan Umum',
                'kategori'       => 'surat',
                'deskripsi'      => 'Surat keterangan umum sesuai kebutuhan klien.',
                'estimasi_waktu' => '1-2 Hari Kerja',
                'status_aktif'   => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('layanan')->whereIn('nama_layanan', [
            'Surat Kuasa',
            'Surat Pernyataan Penguasaan Fisik Tanah Dan Tidak Sengketa',
            'Surat Pernyataan Pemasangan Tanda-Tanda Batas',
            'Surat Keterangan Umum',
        ])->delete();
    }
};
