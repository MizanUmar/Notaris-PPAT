<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akta extends Model
{
    use HasFactory;

    protected $table = 'akta';

    protected $fillable = [
        'permintaan_id',
        'nomor_akta',
        'nama_akta',
        'isi_akta',
        'tanggal_akta',
        'file_akta',
    ];

    protected $casts = [
        'tanggal_akta' => 'date',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_id');
    }
}
