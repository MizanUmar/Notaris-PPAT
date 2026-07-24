<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistPersyaratan extends Model
{
    use HasFactory;

    protected $table = 'checklist_persyaratan';

    protected $fillable = [
        'permintaan_id',
        'persyaratan_id',
        'status',
    ];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_id');
    }

    public function persyaratan()
    {
        return $this->belongsTo(PersyaratanDokumen::class, 'persyaratan_id');
    }
}