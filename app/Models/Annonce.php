<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    const CATEGORIES = [
        // Transport & Auto
        'vehicules'           => 'Voitures & 4×4',
        'motos'               => 'Motos & Scooters',
        'pieces_auto'         => 'Pièces & Accessoires auto',
        'transport_logistique'=> 'Transport & Livraison',
        // Immobilier
        'immobilier'          => 'Vente immobilière',
        'location'            => 'Location / Colocation',
        'terrain'             => 'Terrains & Parcelles',
        // Emploi & Formation
        'emploi'              => 'Emploi & Recrutement',
        'formation'           => 'Formation & Cours',
        'stage'               => 'Stage & Bénévolat',
        // Services professionnels
        'services_batiment'   => 'Bâtiment & Travaux',
        'services_numerique'  => 'Informatique & Web',
        'services_menage'     => 'Ménage & Nettoyage',
        'services_securite'   => 'Sécurité & Gardiennage',
        'services_evenements' => 'Événements & Animation',
        'services_photo'      => 'Photo & Vidéo',
        'services_beaute'     => 'Beauté & Coiffure',
        'sante'               => 'Santé & Bien-être',
        'services_juridique'  => 'Juridique & Conseil',
        'services_couture'    => 'Couture & Retouches',
        'services_mecanique'  => 'Mécanique & Dépannage',
        // Commerce & Alimentation
        'alimentation'        => 'Alimentation & Traiteur',
        'commerce'            => 'Commerce & Boutique',
        'agriculture'         => 'Agriculture & Élevage',
        // Maison & Électronique
        'maison'              => 'Maison & Mobilier',
        'electronique'        => 'Multimédia & Électronique',
        'materiaux'           => 'Matériaux & Bricolage',
        // Mode & Loisirs
        'mode'                => 'Mode & Habillement',
        'loisirs'             => 'Loisirs & Sport',
        'enfants'             => 'Enfants & Bébé',
        'animaux'             => 'Animaux & Mascottes',
        // Divers
        'autres'              => 'Autres',
    ];

    const ICONS = [
        'vehicules'           => '🚗',
        'motos'               => '🏍️',
        'pieces_auto'         => '🔩',
        'transport_logistique'=> '🚚',
        'immobilier'          => '🏠',
        'location'            => '🔑',
        'terrain'             => '📐',
        'emploi'              => '💼',
        'formation'           => '📚',
        'stage'               => '🎓',
        'services_batiment'   => '🏗️',
        'services_numerique'  => '💻',
        'services_menage'     => '🧹',
        'services_securite'   => '🛡️',
        'services_evenements' => '🎉',
        'services_photo'      => '📷',
        'services_beaute'     => '💆',
        'sante'               => '⚕️',
        'services_juridique'  => '⚖️',
        'services_couture'    => '🧵',
        'services_mecanique'  => '🔧',
        'alimentation'        => '🍽️',
        'commerce'            => '🏪',
        'agriculture'         => '🌾',
        'maison'              => '🛋️',
        'electronique'        => '📱',
        'materiaux'           => '🏗️',
        'mode'                => '👗',
        'loisirs'             => '⚽',
        'enfants'             => '👶',
        'animaux'             => '🐾',
        'autres'              => '📋',
    ];

    const CATEGORY_GROUPS = [
        'Transport & Auto'        => ['vehicules', 'motos', 'pieces_auto', 'transport_logistique'],
        'Immobilier'              => ['immobilier', 'location', 'terrain'],
        'Emploi & Formation'      => ['emploi', 'formation', 'stage'],
        'Services'                => ['services_batiment', 'services_numerique', 'services_menage', 'services_securite', 'services_evenements', 'services_photo', 'services_beaute', 'sante', 'services_juridique', 'services_couture', 'services_mecanique'],
        'Commerce & Alimentation' => ['alimentation', 'commerce', 'agriculture'],
        'Maison & Tech'           => ['maison', 'electronique', 'materiaux'],
        'Mode & Loisirs'          => ['mode', 'loisirs', 'enfants', 'animaux'],
        'Divers'                  => ['autres'],
    ];

    const PRICE_PER_ANNONCE = 10000;

    protected $fillable = [
        'advertiser_id', 'title', 'description', 'category',
        'price', 'location', 'contact_phone', 'contact_email',
        'images', 'status', 'payment_status', 'payment_ref', 'expires_at',
    ];

    protected $casts = [
        'images'     => 'array',
        'expires_at' => 'date',
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
