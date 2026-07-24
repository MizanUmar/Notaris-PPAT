<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [

        'user_id',

        'judul',

        'catatan',

        'tanggal',

        'selesai'

    ];

    protected $casts = [

        'tanggal' => 'date',

        'selesai' => 'boolean'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
