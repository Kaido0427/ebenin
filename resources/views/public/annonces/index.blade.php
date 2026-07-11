@extends('public.layouts.app')

@section('title', 'Petites annonces au Bénin | E-Bénin')
@section('meta_description', "Petites annonces au Bénin : emploi, immobilier, véhicules, services et bien plus sur E-Bénin.")

@push('head')
<link rel="stylesheet" href="{{ asset('css/annonces-refonte.css') }}?v=2">
@endpush

@php
$icons  = \App\Models\Annonce::ICONS;
$cats   = \App\Models\Annonce::CATEGORIES;

// Icônes Font Awesome 6 par catégorie (remplace les emojis)
$faIcons = [
    'vehicules'            => 'fa-car',
    'motos'                => 'fa-motorcycle',
    'pieces_auto'          => 'fa-gears',
    'transport_logistique' => 'fa-truck',
    'immobilier'           => 'fa-house',
    'location'             => 'fa-key',
    'terrain'              => 'fa-map',
    'emploi'               => 'fa-briefcase',
    'formation'            => 'fa-book-open',
    'stage'                => 'fa-graduation-cap',
    'services_batiment'    => 'fa-hard-hat',
    'services_numerique'   => 'fa-laptop-code',
    'services_menage'      => 'fa-broom',
    'services_securite'    => 'fa-shield-halved',
    'services_evenements'  => 'fa-champagne-glasses',
    'services_photo'       => 'fa-camera',
    'services_beaute'      => 'fa-scissors',
    'sante'                => 'fa-heart-pulse',
    'services_juridique'   => 'fa-scale-balanced',
    'services_couture'     => 'fa-shirt',
    'services_mecanique'   => 'fa-wrench',
    'alimentation'         => 'fa-utensils',
    'commerce'             => 'fa-store',
    'agriculture'          => 'fa-wheat-awn',
    'maison'               => 'fa-couch',
    'electronique'         => 'fa-mobile-screen-button',
    'materiaux'            => 'fa-hammer',
    'mode'                 => 'fa-shirt',
    'loisirs'              => 'fa-futbol',
    'enfants'              => 'fa-baby',
    'animaux'              => 'fa-paw',
    'autres'               => 'fa-box-open',
];

// Méga-menu avec icônes FA
$megaMenu = [
    'immobilier' => [
        'label' => 'Immobilier',
        'fa'    => 'fa-house',
        'all'   => ['immobilier', 'location', 'terrain'],
        'groups' => [
            'Vente immobilière'    => ['immobilier'],
            'Location & Colocation'=> ['location'],
            'Terrains & Parcelles' => ['terrain'],
        ],
    ],
    'vehicules_group' => [
        'label' => 'Véhicules',
        'fa'    => 'fa-car',
        'all'   => ['vehicules', 'motos', 'pieces_auto', 'transport_logistique'],
        'groups' => [
            'Voitures & 4×4'       => ['vehicules'],
            'Motos & Scooters'     => ['motos'],
            'Pièces & Accessoires' => ['pieces_auto'],
            'Transport & Livraison'=> ['transport_logistique'],
        ],
    ],
    'emploi_group' => [
        'label' => 'Emploi & Formation',
        'fa'    => 'fa-briefcase',
        'all'   => ['emploi', 'formation', 'stage'],
        'groups' => [
            'Offres d\'emploi'  => ['emploi'],
            'Formations & Cours'=> ['formation'],
            'Stage & Bénévolat' => ['stage'],
        ],
    ],
    'services_group' => [
        'label' => 'Services',
        'fa'    => 'fa-screwdriver-wrench',
        'all'   => ['services_batiment','services_numerique','services_menage','services_securite','services_evenements','services_photo','services_beaute','sante','services_juridique','services_couture','services_mecanique'],
        'groups' => [
            'Bâtiment & Travaux'   => ['services_batiment'],
            'Numérique & Web'      => ['services_numerique'],
            'Ménage & Nettoyage'   => ['services_menage'],
            'Sécurité'             => ['services_securite'],
            'Événements & Anim.'   => ['services_evenements'],
            'Photo & Vidéo'        => ['services_photo'],
            'Beauté & Coiffure'    => ['services_beaute'],
            'Santé & Bien-être'    => ['sante'],
            'Juridique & Conseil'  => ['services_juridique'],
            'Couture & Retouches'  => ['services_couture'],
            'Mécanique & Dépannage'=> ['services_mecanique'],
        ],
    ],
    'commerce_group' => [
        'label' => 'Commerce & Agri',
        'fa'    => 'fa-store',
        'all'   => ['alimentation', 'commerce', 'agriculture'],
        'groups' => [
            'Commerce & Boutique'    => ['commerce'],
            'Alimentation & Traiteur'=> ['alimentation'],
            'Agriculture & Élevage'  => ['agriculture'],
        ],
    ],
    'maison_group' => [
        'label' => 'Maison & Électro',
        'fa'    => 'fa-couch',
        'all'   => ['maison', 'electronique', 'materiaux'],
        'groups' => [
            'Maison & Mobilier'        => ['maison'],
            'Multimédia & Électronique'=> ['electronique'],
            'Matériaux & Bricolage'    => ['materiaux'],
        ],
    ],
    'mode_group' => [
        'label' => 'Mode & Loisirs',
        'fa'    => 'fa-shirt',
        'all'   => ['mode', 'loisirs', 'enfants', 'animaux'],
        'groups' => [
            'Mode & Habillement'=> ['mode'],
            'Loisirs & Sport'   => ['loisirs'],
            'Enfants & Bébé'    => ['enfants'],
            'Animaux'           => ['animaux'],
        ],
    ],
    'autres_group' => [
        'label' => 'Autres',
        'fa'    => 'fa-box-open',
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
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Rechercher</button>
            </form>
            <a href="{{ route('advertiser.register') }}" class="ann-hero__cta">
                <i class="fa-solid fa-plus"></i> Déposer une annonce
            </a>
        </div>
    </div>
</div>

{{-- ── Méga-menu navigation ──────────────────────────────── --}}
<nav class="ann-nav">
    <div class="container ann-nav__container">
        {{-- Barre scrollable : items uniquement, sans les dropdowns --}}
        <div class="ann-nav__bar">
            <div class="ann-nav__item">
                <a href="{{ route('annonces.index') }}" class="ann-nav__link {{ !$category ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass ann-nav__fa"></i> Toutes
                </a>
            </div>

            @foreach ($megaMenu as $groupKey => $group)
            @php
                $groupActive = $category && in_array($category, $group['all']);
            @endphp
            <div class="ann-nav__item {{ $groupActive ? 'active' : '' }}" data-mega="ann-mega-{{ $groupKey }}">
                <span class="ann-nav__link {{ $groupActive ? 'active' : '' }}">
                    <i class="fa-solid {{ $group['fa'] }} ann-nav__fa"></i>
                    {{ $group['label'] }}
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" style="margin-left:2px;opacity:.5"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </span>
            </div>
            @endforeach
        </div>

        {{-- Dropdowns en dehors de la barre scrollable pour éviter le clipping --}}
        @foreach ($megaMenu as $groupKey => $group)
        <div class="ann-mega" id="ann-mega-{{ $groupKey }}">
            <div class="ann-mega__left">
                <div class="ann-mega__left-title">
                    <i class="fa-solid {{ $group['fa'] }}"></i> {{ $group['label'] }}
                </div>
                <a href="{{ route('annonces.index', ['category' => $group['all'][0]]) }}" class="ann-mega__left-link">
                    <i class="fa-solid fa-list ann-mega__fa"></i> Tout {{ $group['label'] }}
                </a>
                @foreach ($group['all'] as $catKey)
                    @if (isset($cats[$catKey]))
                    <a href="{{ $annoncesUrl($catKey) }}" class="ann-mega__left-link">
                        <i class="fa-solid {{ $faIcons[$catKey] ?? 'fa-tag' }} ann-mega__fa"></i> {{ $cats[$catKey] }}
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
                            <i class="fa-solid {{ $faIcons[$subKey] ?? 'fa-tag' }} ann-mega__fa"></i> {{ $cats[$subKey] }}
                        </a>
                        @endif
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</nav>

{{-- ── Listing annonces ──────────────────────────────────── --}}
<div class="ann-listing">
    <div class="container">
        <div class="ann-listing__header">
            <h2 class="ann-listing__title">
                @if ($category)
                    <i class="fa-solid {{ $faIcons[$category] ?? 'fa-tag' }}" style="color:var(--ann-red);margin-right:6px"></i>{{ $cats[$category] ?? 'Annonces' }}
                @else
                    <i class="fa-solid fa-list" style="color:var(--ann-red);margin-right:6px"></i>Toutes les annonces
                @endif
                @if (!$annonces->isEmpty())
                    <span class="ann-listing__count">{{ $annonces->total() }} annonce(s)</span>
                @endif
            </h2>
            <a href="{{ route('advertiser.register') }}" class="ann-btn-post"><i class="fa-solid fa-plus"></i> Publier une annonce</a>
        </div>

        @if ($annonces->isEmpty())
            <div class="ann-empty">
                <div class="ann-empty__icon">
                    <i class="fa-solid {{ $category ? ($faIcons[$category] ?? 'fa-box-open') : 'fa-box-open' }}"></i>
                </div>
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
                            <div class="ann-card__img-ph">
                                <i class="fa-solid {{ $faIcons[$annonce->category] ?? 'fa-tag' }}"></i>
                            </div>
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

<script>
(function () {
    var navBar    = document.querySelector('.ann-nav__bar');
    var container = document.querySelector('.ann-nav__container');
    if (!navBar || !container) return;

    var currentMega = null;
    var hideTimer   = null;

    function positionMega(mega, item) {
        mega.style.top = navBar.offsetHeight + 'px';
        var cRect    = container.getBoundingClientRect();
        var iRect    = item.getBoundingClientRect();
        var left     = iRect.left - cRect.left;
        var megaW    = mega.offsetWidth || 580;
        if (left + megaW > container.offsetWidth) {
            left = Math.max(0, container.offsetWidth - megaW);
        }
        mega.style.left  = left + 'px';
        mega.style.right = 'auto';
    }

    document.querySelectorAll('.ann-nav__item[data-mega]').forEach(function (item) {
        var mega = document.getElementById(item.getAttribute('data-mega'));
        if (!mega) return;

        function show() {
            clearTimeout(hideTimer);
            if (currentMega && currentMega !== mega) {
                currentMega.style.display = 'none';
            }
            currentMega = mega;
            mega.style.display = 'flex';
            positionMega(mega, item);
        }

        function startHide() {
            hideTimer = setTimeout(function () {
                if (currentMega) { currentMega.style.display = 'none'; currentMega = null; }
            }, 180);
        }

        item.addEventListener('mouseenter', show);
        item.addEventListener('mouseleave', startHide);
        mega.addEventListener('mouseenter', function () { clearTimeout(hideTimer); });
        mega.addEventListener('mouseleave', startHide);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.ann-nav')) {
            if (currentMega) { currentMega.style.display = 'none'; currentMega = null; }
        }
    });
})();
</script>
@endsection
