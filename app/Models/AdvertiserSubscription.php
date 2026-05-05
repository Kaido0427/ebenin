<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertiserSubscription extends Model
{
    protected $fillable = [
        'advertiser_id', 'started_at', 'expires_at',
        'status', 'weeks_paid', 'amount', 'payment_method', 'reference',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }
}
