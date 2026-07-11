@extends('public.layouts.app')

@section('title', 'Petites annonces au Bénin | E-Bénin')
@section('meta_description', "Petites annonces au Bénin : emploi, immobilier, véhicules, services et bien plus sur E-Bénin.")

@push('head')
<link rel="stylesheet" href="{{ asset('css/annonces-refonte.css') }}?v=2">
@endpush

@php
$icons  = \App\Models\Annonce::ICONS;
$cats   = \App\Models\Annonce::CATEGORIES;

// Structure méga-menu adaptée e-Bénin
$megaMenu = [
    'immobilier' => [
        'label' => 'Immobilier',
        'icon'  => '🏠',
        'all'   => ['immobilier', 'location', 'terrain'],
        'groups' => [
            'Vente immobilière' => ['immobilier'],
            'Location & Colocation' => ['location'],
            'Terrains & Parcelles' => ['terrain'],
        ],
    ],
    'vehicules_group' => [
        'label' => 'Véhicules',
        'icon'  => '🚗',
        'all'   => ['vehicules', 'motos', 'pieces_auto', 'transport_logistique'],
        'groups' => [
            'Voitures & 4×4'        => ['vehicules'],
            'Motos & Scooters'       => ['motos'],
            'Pièces & Accessoires'   => ['pieces_auto'],
            'Transport & Livraison'  => ['transport_logistique'],
        ],
    ],
    'emploi_group' => [
        'label' => 'Emploi & Formation',
        'icon'  => '💼',
        'all'   => ['emploi', 'formation', 'stage'],
        'groups' => [
            'Offres d\'emploi'         => ['emploi'],
            'Formations & Cours'       => ['formation'],
            'Stage & Bénévolat'        => ['stage'],
        ],
    ],
    'services_group' => [
        'label' => 'Services',
        'icon'  => '🔧',
        'all'   => ['services_batiment','services_numerique','services_menage','services_securite','services_evenements','services_photo','services_beaute','sante','services_juridique','services_couture','services_mecanique'],
        'groups' => [
            'Bâtiment & Travaux'     => ['services_batiment'],
            'Numérique & Web'        => ['services_numerique'],
            'Ménage & Nettoyage'     => ['services_menage'],
            'Sécurité'               => ['services_securite'],
            'Événements & Anim.'     => ['services_evenements'],
            'Photo & Vidéo'          => ['services_photo'],
            'Beauté & Coiffure'      => ['services_beaute'],
            'Santé & Bien-être'      => ['sante'],
            'Juridique & Conseil'    => ['services_juridique'],
            'Couture & Retouches'    => ['services_couture'],
            'Mécanique & Dépannage'  => ['services_mecanique'],
        ],
    ],
    'commerce_group' => [
        'label' => 'Commerce & Agri',
        'icon'  => '🏪',
        'all'   => ['alimentation', 'commerce', 'agriculture'],
        'groups' => [
            'Commerce & Boutique'    => ['commerce'],
            'Alimentation & Traiteur'=> ['alimentation'],
            'Agriculture & Élevage'  => ['agriculture'],
        ],
    ],
    'maison_group' => [
        'label' => 'Maison & Électro',
        'icon'  => '🛋️',
        'all'   => ['maison', 'electronique', 'materiaux'],
        'groups' => [
            'Maison & Mobilier'        => ['maison'],
            'Multimédia & Électronique'=> ['electronique'],
            'Matériaux & Bricolage'    => ['materiaux'],
        ],
    ],
    'mode_group' => [
        'label' => 'Mode & Loisirs',
        'icon'  => '👗',
        'all'   => ['mode', 'loisirs', 'enfants', 'animaux'],
        'groups' => [
            'Mode & Habillement' => ['mode'],
            'Loisirs & Sport'    => ['loisirs'],
            'Enfants & Bébé'     => ['enfants'],
            'Animaux'            => ['animaux'],
        ],
    ],
    'autres_group' => [
        'label' => 'Autres',
        'icon'  => '📦',
        'all'   => ['autres'],
        'groups' => [
            'Divers' => ['autres'],
        ],
    ],
];

$annoncesUrl = fn($cat) => route('annonces.index', ['category' => $cat]);
@endphp

@section('content')
{{-- ── Hero ───────────────────────────────────────────────── --}}
<div class="ann-hero">
    <div class="container">
        <div class="ann-hero__inner">
            <h1 class="ann-hero__title">Petites annonces au Bénin</h1>
            <p class="ann-hero__sub">Achetez, vendez, trouvez un emploi ou un service près de chez vous</p>
            <form class="ann-hero__search" action="{{ route('annonces.index') }}" method="GET">
                @if ($category)
                    <input type="hidden" name="category" value="{{ $category }}">
                @endif
                <input type="text" name="q" placeholder="Que recherchez-vous ?" value="{{ request('q') }}">
                <button type="submit">🔍 Rechercher</button>
            </form>
            <a href="{{ route('advertiser.register') }}" class="ann-hero__cta">
                ＋ Déposer une annonce
            </a>
        </div>
    </div>
</div>

{{-- ── Méga-menu navigation ──────────────────────────────── --}}
<nav class="ann-nav">
    <div class="container">
        <div class="ann-nav__bar">
            {{-- Toutes --}}
            <div class="ann-nav__item">
                <a href="{{ route('annonces.index') }}" class="ann-nav__link {{ !$category ? 'active' : '' }}">
                    <span class="ann-nav__icon">🔍</span> Toutes
                </a>
            </div>

            @foreach ($megaMenu as $groupKey => $group)
            @php
                $groupActive = $category && in_array($category, $group['all']);
            @endphp
            <div class="ann-nav__item {{ $groupActive ? 'active' : '' }}">
                <span class="ann-nav__link">
                    <span class="ann-nav__icon">{{ $group['icon'] }}</span>
                    {{ $group['label'] }}
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" style="margin-left:2px;opacity:.5"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </span>
                <div class="ann-mega">
                    <div class="ann-mega__left">
                        <div class="ann-mega__left-title">
                            {{ $group['icon'] }} {{ $group['label'] }}
                        </div>
                        <a href="{{ route('annonces.index', ['category' => $group['all'][0]]) }}" class="ann-mega__left-link">
                            Tout {{ $group['label'] }}
                        </a>
                        @foreach ($group['all'] as $catKey)
                            @if (isset($cats[$catKey]))
                            <a href="{{ $annoncesUrl($catKey) }}" class="ann-mega__left-link">
                                {{ $icons[$catKey] ?? '' }} {{ $cats[$catKey] }}
                            </a>
                            @endif
                        @endforeach
                    </div>
                    <div class="ann-mega__cols">
                        @foreach ($group['groups'] as $groupTitle => $subKeys)
                        <div class="ann-mega__col">
                            <p class="ann-mega__group-title">{{ $groupTitle }}</p>
                            @foreach ($subKeys as $subKey)
                                @if (isset($cats[$subKey]))
                                <a href="{{ $annoncesUrl($subKey) }}" class="ann-mega__sub-link {{ $category === $subKey ? 'active' : '' }}"
                                   style="{{ $category === $subKey ? 'color:var(--ann-red);font-weight:700;' : '' }}">
                                    {{ $icons[$subKey] ?? '' }} {{ $cats[$subKey] }}
                                </a>
                                @endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</nav>

{{-- ── Catégories icônes ─────────────────────────────────── --}}
<div class="ann-cats">
    <div class="container">
        <p class="ann-cats__title">Parcourir par catégorie</p>
        <div class="ann-cats__grid">
            <a href="{{ route('annonces.index') }}" class="ann-cat-btn {{ !$category ? 'active' : '' }}">
                <span class="ann-cat-btn__icon">🔍</span>
                <span>Toutes</span>
            </a>
            @foreach ($cats as $key => $label)
            <a href="{{ $annoncesUrl($key) }}" class="ann-cat-btn {{ $category === $key ? 'active' : '' }}">
                <span class="ann-cat-btn__icon">{{ $icons[$key] ?? '📋' }}</span>
                <span>{{ $label }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ── Listing annonces ──────────────────────────────────── --}}
<div class="ann-listing">
    <div class="container">
        <div class="ann-listing__header">
            <h2 class="ann-listing__title">
                @if ($category)
                    {{ $icons[$category] ?? '' }} {{ $cats[$category] ?? 'Annonces' }}
                @else
                    Toutes les annonces
                @endif
                @if (!$annonces->isEmpty())
                    <span class="ann-listing__count">{{ $annonces->total() }} annonce(s)</span>
                @endif
            </h2>
            <a href="{{ route('advertiser.register') }}" class="ann-btn-post">＋ Publier une annonce</a>
        </div>

        @if ($annonces->isEmpty())
            <div class="ann-empty">
                <div class="ann-empty__icon">{{ $category ? ($icons[$category] ?? '📋') : '📋' }}</div>
                <p class="ann-empty__title">Aucune annonce disponible</p>
                <p class="ann-empty__text">Soyez le premier à publier{{ $category ? ' dans cette catégorie' : '' }} !</p>
                <a href="{{ route('advertiser.register') }}" class="ann-btn-post">Publier la première annonce</a>
            </div>
        @else
            <div class="ann-grid">
                @foreach ($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce) }}" class="ann-card">
                    <div class="ann-card__img-wrap">
                        @if ($annonce->images && count($annonce->images) > 0)
                            <img class="ann-card__img" src="{{ asset($annonce->images[0]) }}" alt="{{ $annonce->title }}" loading="lazy">
                        @else
                            <div class="ann-card__img-ph">{{ $icons[$annonce->category] ?? '📋' }}</div>
                        @endif
                        <span class="ann-card__badge">{{ $annonce->category_label }}</span>
                    </div>
                    <div class="ann-card__body">
                        <div class="ann-card__title">{{ $annonce->title }}</div>
                        @if ($annonce->description)
                            <div class="ann-card__desc">{{ Str::limit(strip_tags($annonce->description), 100) }}</div>
                        @endif
                    </div>
                    <div class="ann-card__footer">
                        <div class="ann-card__price">
                            @if ($annonce->price)
                                {{ number_format($annonce->price, 0, ',', ' ') }} FCFA
                            @else
                                <span style="color:var(--ann-muted);font-size:.82rem;font-weight:500;">Prix à débattre</span>
                            @endif
                        </div>
                        <div class="ann-card__meta">
                            <span class="ann-card__location">
                                @if ($annonce->location)📍 {{ Str::limit($annonce->location, 20) }}@endif
                            </span>
                            <span class="ann-card__time">{{ $annonce->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="ann-pagination">
                {{ $annonces->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
