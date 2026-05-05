<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $fillable = [
        'advertiser_id', 'title', 'description', 'category',
        'price', 'location', 'contact_phone', 'contact_email',
        'images', 'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'emploi'          => 'Emploi',
            'immobilier'      => 'Immobilier',
            'vente_services'  => 'Vente / Services',
            'evenements'      => 'Évènements',
            default           => $this->category,
        };
    }
}
