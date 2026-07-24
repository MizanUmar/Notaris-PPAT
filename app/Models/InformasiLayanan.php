<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiLayanan extends Model
{
    use HasFactory;

    protected $table = 'informasi_layanan';

    protected $fillable = [
        'layanan_id',
        'judul',
        'isi_informasi',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
}
