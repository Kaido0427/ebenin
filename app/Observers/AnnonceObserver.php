<?php

namespace App\Observers;

use App\Models\Annonce;
use App\Services\FacebookService;

class AnnonceObserver
{
    public function __construct(private FacebookService $facebook) {}

    public function created(Annonce $annonce): void
    {
        // Les annonces admin sont créées directement active
        if ($annonce->status === 'active') {
            $this->shareAnnonce($annonce);
        }
    }

    public function updated(Annonce $annonce): void
    {
        // Déclenché quand le paiement valide l'annonce ou quand l'admin active
        if ($annonce->wasChanged('status') && $annonce->status === 'active') {
            $this->shareAnnonce($annonce);
        }
    }

    private function shareAnnonce(Annonce $annonce): void
    {
        $cats     = \App\Models\Annonce::CATEGORIES;
        $icons    = \App\Models\Annonce::ICONS;
        $catLabel = $cats[$annonce->category] ?? $annonce->category;
        $icon     = $icons[$annonce->category] ?? '📋';
        $prix     = $annonce->price ? number_format($annonce->price, 0, ',', ' ') . ' FCFA' : '';

        $url = url("/annonces/{$annonce->id}");

        $message = "{$icon} {$annonce->title}";
        if ($catLabel) {
            $message .= "\n📂 {$catLabel}";
        }
        if ($prix) {
            $message .= " · 💰 {$prix}";
        }
        if ($annonce->location) {
            $message .= "\n📍 {$annonce->location}";
        }
        $message .= "\n\n👉 Voir l'annonce sur e-Bénin : {$url}";

        $this->facebook->postToPage($message, $url);
    }
}
