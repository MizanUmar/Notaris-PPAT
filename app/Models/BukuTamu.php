<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    use HasFactory;

    protected $table = 'buku_tamu';

    protected $fillable = [
        'user_id',
        'nama_tamu',
        'instansi',
        'nomor_hp',
        'keperluan',
        'tanggal_kunjungan',
        'qr_code',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
