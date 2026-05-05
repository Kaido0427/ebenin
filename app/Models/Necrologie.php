<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Necrologie extends Model
{
    protected $fillable = [
        'advertiser_id', 'nom_defunt', 'date_naissance',
        'date_deces', 'message', 'photo', 'video', 'status',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_deces'     => 'date',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }
}
