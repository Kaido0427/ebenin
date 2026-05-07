<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    const CATEGORIES = [
        'vehicules'    => 'Véhicules',
        'immobilier'   => 'Immobilier',
        'emploi'       => 'Emploi',
        'services'     => 'Services',
        'electronique' => 'Multimédia / Électronique',
        'maison'       => 'Maison / Mobilier',
        'mode'         => 'Mode / Habillement',
        'loisirs'      => 'Loisirs / Sport',
        'alimentation' => 'Alimentation',
        'animaux'      => 'Animaux',
        'enfants'      => 'Enfants / Bébé',
        'materiaux'    => 'Matériaux / Bricolage',
        'agriculture'  => 'Agriculture / Élevage',
        'evenements'   => 'Évènements',
        'autres'       => 'Autres',
    ];

    const PRICE_PER_ANNONCE = 10000;

    protected $fillable = [
        'advertiser_id', 'title', 'description', 'category',
        'price', 'location', 'contact_phone', 'contact_email',
        'images', 'status', 'payment_status', 'payment_ref',
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
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
