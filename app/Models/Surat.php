<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';

    protected $fillable = [
        'permintaan_id',
        'nomor_surat',
        'jenis_surat',
        'tanggal_surat',
        'file_surat',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_id');
    }
}
