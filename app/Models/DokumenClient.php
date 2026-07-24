<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenClient extends Model
{
    use HasFactory;

    protected $table = 'dokumen_client';

    protected $fillable = [
        'permintaan_id',
        'nama_file',
        'file_path',
        'tanggal_upload',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_id');
    }
}
