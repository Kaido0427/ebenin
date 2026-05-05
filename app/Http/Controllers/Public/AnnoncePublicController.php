<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\rubrique;

class AnnoncePublicController extends Controller
{
    private function sharedViewData(): array
    {
        return [
            'navItems'      => rubrique::all(),
            'tickerPosts'   => collect([]),
            'showAuthModal' => false,
        ];
    }

    public function index()
    {
        $category = request('category');

        $annonces = Annonce::with('advertiser')
            ->where('status', 'active')
            ->when($category, fn($q) => $q->where('category', $category))
            ->latest()
            ->paginate(20);

        $categories = [
            'emploi'         => 'Emploi',
            'immobilier'     => 'Immobilier',
            'vente_services' => 'Vente / Services',
            'evenements'     => 'Évènements',
        ];

        return view('public.annonces.index', array_merge(
            compact('annonces', 'categories', 'category'),
            $this->sharedViewData()
        ));
    }

    public function show(Annonce $annonce)
    {
        abort_if($annonce->status !== 'active', 404);
        return view('public.annonces.show', array_merge(
            compact('annonce'),
            $this->sharedViewData()
        ));
    }
}
