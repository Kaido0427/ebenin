<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnoncePublicController extends Controller
{
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

        return view('public.annonces.index', compact('annonces', 'categories', 'category'));
    }

    public function show(Annonce $annonce)
    {
        abort_if($annonce->status !== 'active', 404);
        return view('public.annonces.show', compact('annonce'));
    }
}
