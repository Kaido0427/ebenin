<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\organization as  Organization;
use App\Models\post as Post;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table="users";

    protected $fillable = [
        'name',
        'email',
        'password',
        'isResponsable',
        'isAdmin',
        'phone',
        'address',
        'organization_id',
        'subscription_quantity',
        'subscription_started_at',   // ← nouveau
        'is_active',
        'deactivated_at',
        'deactivation_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'       => 'datetime',
        'password'                => 'hashed',
        'subscription_started_at' => 'datetime',  // ← cast Carbon automatique
        'subscription_quantity'   => 'integer',   // ← float → integer (des demi-mois ça n'existe pas)
        'is_active'               => 'boolean',
        'deactivated_at'          => 'datetime',
    ];

    // ─────────────────────────────────────────────
    //  Accesseurs abonnement
    // ─────────────────────────────────────────────

    /**
     * Date d'expiration de l'abonnement.
     * Retourne une instance Carbon ou null si jamais abonné.
     */
    public function getSubscriptionExpiryAttribute(): ?Carbon
    {
        if (!$this->subscription_started_at || !$this->subscription_quantity) {
            return null;
        }

        return $this->subscription_started_at->copy()->addMonths($this->subscription_quantity);
    }

    /**
     * L'abonnement est-il actif en ce moment ?
     */
    public function getIsSubscriptionActiveAttribute(): bool
    {
        $expiry = $this->subscription_expiry;

        if (!$expiry) {
            return false;
        }

        return now()->lessThanOrEqualTo($expiry);
    }

    /**
     * Nombre de jours restants (0 si expiré).
     */
    public function getSubscriptionDaysLeftAttribute(): int
    {
        $expiry = $this->subscription_expiry;

        if (!$expiry || now()->greaterThan($expiry)) {
            return 0;
        }

        return (int) now()->diffInDays($expiry);
    }

    // ─────────────────────────────────────────────
    //  Relations
    // ─────────────────────────────────────────────

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
