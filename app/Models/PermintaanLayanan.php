<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChecklistPersyaratan;
use App\Models\Akta;

class PermintaanLayanan extends Model
{
    use HasFactory;

    protected $table = 'permintaan_layanan';

    protected $fillable = [
        'client_id',
        'layanan_id',
        'tanggal_permintaan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_permintaan' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function dokumenClient()
    {
        return $this->hasMany(DokumenClient::class, 'permintaan_id');
    }

    public function akta()
    {
        return $this->hasOne(Akta::class, 'permintaan_id');
    }

    public function surat()
    {
        return $this->hasMany(Surat::class, 'permintaan_id');
    }

    public function checklistPersyaratan()
    {
        return $this->hasMany(ChecklistPersyaratan::class, 'permintaan_id');
    }

    public function isDokumenLengkap()
    {
        $totalRequired = $this->layanan ? $this->layanan->persyaratan->count() : 0;
        if ($totalRequired === 0) {
            return true;
        }

        $checkedCount = $this->checklistPersyaratan()
            ->whereIn('persyaratan_id', $this->layanan->persyaratan->pluck('id'))
            ->where('status', true)
            ->count();

        return $checkedCount >= $totalRequired;
    }

    public function getJumlahBerkasTercentangAttribute()
    {
        if (!$this->layanan) return 0;
        return $this->checklistPersyaratan()
            ->whereIn('persyaratan_id', $this->layanan->persyaratan->pluck('id'))
            ->where('status', true)
            ->count();
    }

    public function getJumlahBerkasWajibAttribute()
    {
        if (!$this->layanan) return 0;
        return $this->layanan->persyaratan->count();
    }
}
