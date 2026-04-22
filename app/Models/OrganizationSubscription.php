<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrganizationSubscription extends Model
{
    protected $table = 'organization_subscriptions';

    protected $fillable = [
        'organization_id',
        'plan_name',
        'status',
        'started_at',
        'expires_at',
        'next_renewal_at',
        'renewal_cycle_months',
        'is_auto_renew',
        'last_payment_at',
        'managed_by_admin_id',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'next_renewal_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'is_auto_renew' => 'boolean',
        'renewal_cycle_months' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function manager()
    {
        return $this->belongsTo(Admin::class, 'managed_by_admin_id');
    }

    public function getDaysLeftAttribute(): int
    {
        if (!$this->expires_at || now()->greaterThan($this->expires_at)) {
            return 0;
        }

        return (int) now()->diffInDays($this->expires_at);
    }

    public function getIsActiveAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return $this->expires_at instanceof Carbon && now()->lessThanOrEqualTo($this->expires_at);
    }
}
