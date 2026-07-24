<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChecklistPersyaratan;

class PersyaratanDokumen extends Model
{
    use HasFactory;

    protected $table = 'persyaratan_dokumen';

    protected $fillable = [
        'layanan_id',
        'nama_dokumen',
        'keterangan',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function checklist()
    {
        return $this->hasMany(ChecklistPersyaratan::class, 'persyaratan_id');
    }
}
