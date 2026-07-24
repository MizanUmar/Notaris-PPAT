<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKantor extends Model
{
    use HasFactory;

    protected $table = 'profil_kantor';

    protected $fillable = [
        'nama_kantor',
        'alamat',
        'no_telepon',
        'email',
        'logo',
    ];
}
