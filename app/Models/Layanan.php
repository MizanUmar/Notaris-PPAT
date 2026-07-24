<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'estimasi_waktu',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function persyaratan()
    {
        return $this->hasMany(PersyaratanDokumen::class, 'layanan_id');
    }

    public function informasi()
    {
        return $this->hasMany(InformasiLayanan::class, 'layanan_id');
    }

    public function permintaan()
    {
        return $this->hasMany(PermintaanLayanan::class, 'layanan_id');
    }
}
