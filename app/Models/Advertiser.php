<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Advertiser extends Authenticatable
{
    use Notifiable;

    protected $guard = 'advertiser';

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address',
        'logo', 'company_name', 'is_active', 'is_admin', 'trial_ends_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'is_active'     => 'boolean',
        'is_admin'      => 'boolean',
    ];

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function necrologies()
    {
        return $this->hasMany(Necrologie::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(AdvertiserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(AdvertiserSubscription::class)
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function hasActiveAccess(): bool
    {
        // Essai gratuit encore valide
        if ($this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return true;
        }

        // Abonnement payant actif
        return $this->subscriptions()
            ->where('expires_at', '>', now())
            ->where('status', 'active')
            ->exists();
    }
}
