<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\social;

class Organization extends Model
{ 
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'organization_name',
        'organization_address',
        'organization_phone',
        'organization_logo',
        'organization_email',
        'subdomain',
        'is_active',
        'is_publicly_visible',
        'deactivated_at',
        'deactivation_reason',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_publicly_visible' => 'boolean',
        'deactivated_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'organization_id', 'user_id');
    }

    public function ownerUsers()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function socials()
    {
        return $this->belongsToMany(Social::class, 'organization_socials', 'organization_id', 'social_id')
                    ->withPivot('url');
    }

    public function transactions()
    {
        return $this->hasMany(transaction::class, 'organization_id');
    }

    public function subscription()
    {
        return $this->hasOne(OrganizationSubscription::class, 'organization_id');
    }
}
