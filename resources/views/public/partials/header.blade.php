@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $isMainDomain = in_array($host, ['e-benin.com', 'www.e-benin.com', 'e-benin.bj', 'www.e-benin.bj'], true);
    $isSubdomain  = !$isMainDomain && count(explode('.', $host)) > 2;
    $organization = $isSubdomain ? ($organization ?? null) : null;
    $navItems = collect($navItems ?? [])->filter(fn($item) => filled($item->id ?? null) && filled($item->name ?? null))->values();
    $primaryNav = $navItems->take(6);
    $overflowNav = $navItems->slice(6);
    $tickerPosts = collect($tickerPosts ?? [])->filter()->values();
    $tickerLinkResolver = $tickerLinkResolver ?? null;
    $logoPath = $brandLogoPath ?? ($organization && filled($organization->organization_logo) ? $organization->organization_logo : 'images/ebenins.png');
    $logoUrl = Str::startsWith((string) $logoPath, ['http://', 'https://']) ? $logoPath : asset(ltrim((string) $logoPath, '/'));
    $homeUrl = $homeUrl ?? ($organization ? "https://{$organization->subdomain}.{$baseDomain}/blog" : $siteRoot);
    $policyUrl = 'https://' . $baseDomain . '/politique';
    $registerUrl = $siteRoot . '/bloger/register';
    $loginUrl = $isMainDomain ? '#auth-login-modal' : $siteRoot . '/?auth=login';
    $forgotUrl = $siteRoot . '/forgot-password';
    $searchUrl = $siteRoot . '/search';
    $dashboardUrl = null;

    if (auth()->check() && auth()->user()?->organization?->subdomain) {
        $dashboardUrl = 'https://' . auth()->user()->organization->subdomain . '.' . $baseDomain . '/dashboard';
    }

    $categoryUrl = function ($rubrique) use ($organization, $baseDomain) {
        if (!$rubrique || !filled($rubrique->id ?? null)) {
            return '#';
        }

        return $organization
            ? "https://{$organization->subdomain}.{$baseDomain}/category/{$rubrique->id}"
            : "https://{$baseDomain}/categories/{$rubrique->id}";
    };

    $activeCategoryId = $activeCategoryId ?? null;
    $dateLabel = now()->locale('fr')->translatedFormat('l d F Y');
@endphp

@if ($tickerPosts->isNotEmpty())
    <div class="ticker">
        <div class="ticker__label">Breaking</div>
        <div class="ticker__track">
            @foreach ($tickerPosts->take(8) as $post)
                <a class="ticker__item" href="{{ $tickerLinkResolver ? $tickerLinkResolver($post) : '#' }}">
                    {{ $post->libelle }}
                </a>
            @endforeach
            @foreach ($tickerPosts->take(8) as $post)
                <a class="ticker__item" href="{{ $tickerLinkResolver ? $tickerLinkResolver($post) : '#' }}">
                    {{ $post->libelle }}
                </a>
            @endforeach
        </div>
    </div>
@endif

<div class="topbar">
    <div class="container">
        <div class="topbar__inner">
            <div class="topbar__date">{{ ucfirst($dateLabel) }} · Bénin</div>
            <div class="topbar__links">
                <a href="mailto:contact@savplus.net">Contact</a>
                <a href="{{ $policyUrl }}">Confidentialité</a>
                @if (auth()->check() && $dashboardUrl)
                    <a href="{{ $dashboardUrl }}">Tableau de bord</a>
                @endif
            </div>
        </div>
    </div>
</div>

<header class="header">
    <div class="container">
        <div class="header__inner">
            <a href="{{ $homeUrl }}" class="logo">
                <img src="{{ $logoUrl }}" alt="{{ $organization->organization_name ?? 'E-Benin' }}" class="logo__img">
            </a>

            <nav class="nav">
                <ul class="nav__list">
                    <li class="nav__item {{ request()->url() === $homeUrl ? 'active' : '' }}">
                        <a class="nav__link" href="{{ $homeUrl }}">Accueil</a>
                    </li>
                    <li class="nav__item {{ $activeCategoryId ? 'active' : '' }}">
                        <a href="#" class="nav__link">
                            Articles
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:12px;height:12px;margin-left:3px">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </a>
                        <div class="nav__dropdown">
                            @foreach ($navItems as $rubrique)
                                <a href="{{ $categoryUrl($rubrique) }}">{{ $rubrique->name }}</a>
                            @endforeach
                        </div>
                    </li>
                    <li class="nav__item {{ request()->is('annonces*') ? 'active' : '' }}">
                        <a class="nav__link" href="{{ $siteRoot }}/annonces">Annonces</a>
                    </li>
                    <li class="nav__item {{ request()->is('necrologies*') ? 'active' : '' }}">
                        <a class="nav__link" href="{{ $siteRoot }}/necrologies">Nécrologies</a>
                    </li>
                </ul>
            </nav>

            <div class="header__actions">
                <a href="{{ $searchUrl }}" class="btn btn--icon" aria-label="Recherche">🔍</a>
                @auth
                    @if ($dashboardUrl)
                        <a href="{{ $dashboardUrl }}" class="btn btn--outline">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logOut') }}">
                        @csrf
                        <button type="submit" class="btn btn--primary">Déconnexion</button>
                    </form>
                @else
                    <div class="nav__item header__auth-item" style="position:relative;">
                        <a href="#" class="btn btn--outline">
                            Connexion
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;margin-left:3px">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </a>
                        <div class="nav__dropdown nav__dropdown--right">
                            @if ($isMainDomain)
                                <a href="{{ $loginUrl }}" data-auth-open="login">Espace blogueur</a>
                            @else
                                <a href="{{ $loginUrl }}">Espace blogueur</a>
                            @endif
                            <a href="{{ $siteRoot }}/advertiser/login">Espace annonceur</a>
                        </div>
                    </div>
                    <div class="nav__item header__auth-item" style="position:relative;">
                        <a href="#" class="btn btn--primary">
                            S'inscrire
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:11px;height:11px;margin-left:3px">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </a>
                        <div class="nav__dropdown nav__dropdown--right">
                            <a href="{{ $registerUrl }}">Créer un blog</a>
                            <a href="{{ $siteRoot }}/advertiser/register">Publier une annonce</a>
                        </div>
                    </div>
                @endauth
                <button class="hamburger" type="button" onclick="toggleMenu(true)" aria-label="Ouvrir le menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav__backdrop" data-mobile-close></div>
    <div class="mobile-nav__panel">

        {{-- ── Header bleu ── --}}
        <div class="mobile-nav__head">
            <img src="{{ $logoUrl }}" alt="{{ $organization->organization_name ?? 'E-Benin' }}" class="mobile-nav__logo">
            <button class="mobile-nav__close" type="button" data-mobile-close aria-label="Fermer">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        {{-- ── Corps scrollable ── --}}
        <div class="mobile-nav__body">

            {{-- Navigation principale en grille 3 colonnes --}}
            <div>
                <div class="mobile-nav__section-title">Navigation</div>
                <div class="mobile-nav__app-grid">

                    <a href="{{ $homeUrl }}" class="mobile-nav__app-item" data-mobile-close>
                        <div class="mobile-nav__app-icon" style="background:#003f7f">
                            <svg viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><polyline points="9 21 9 13 15 13 15 21"/></svg>
                        </div>
                        <span class="mobile-nav__app-label">Accueil</span>
                    </a>

                    <a href="{{ $searchUrl }}" class="mobile-nav__app-item" data-mobile-close>
                        <div class="mobile-nav__app-icon" style="background:#0284c7">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                        <span class="mobile-nav__app-label">Recherche</span>
                    </a>

                    <a href="{{ $siteRoot }}/annonces" class="mobile-nav__app-item" data-mobile-close>
                        <div class="mobile-nav__app-icon" style="background:#e8191e">
                            <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        </div>
                        <span class="mobile-nav__app-label">Annonces</span>
                    </a>

                    <a href="{{ $siteRoot }}/necrologies" class="mobile-nav__app-item" data-mobile-close>
                        <div class="mobile-nav__app-icon" style="background:#374151">
                            <svg viewBox="0 0 24 24"><line x1="12" y1="2" x2="12" y2="6"/><path d="M9 6h6a3 3 0 0 1 3 3v2a6 6 0 0 1-6 6 6 6 0 0 1-6-6V9a3 3 0 0 1 3-3z"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></svg>
                        </div>
                        <span class="mobile-nav__app-label">Nécrologies</span>
                    </a>

                    <a href="mailto:contact@savplus.net" class="mobile-nav__app-item">
                        <div class="mobile-nav__app-icon" style="background:#1a8a40">
                            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <span class="mobile-nav__app-label">Contact</span>
                    </a>

                    @auth
                        @if ($dashboardUrl)
                        <a href="{{ $dashboardUrl }}" class="mobile-nav__app-item" data-mobile-close>
                            <div class="mobile-nav__app-icon" style="background:#f0a500">
                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            </div>
                            <span class="mobile-nav__app-label">Dashboard</span>
                        </a>
                        @endif
                    @else
                        @if ($isMainDomain)
                        <button type="button" class="mobile-nav__app-item" data-auth-open="login">
                            <div class="mobile-nav__app-icon" style="background:#f0a500">
                                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="mobile-nav__app-label">Connexion</span>
                        </button>
                        @else
                        <a href="{{ $loginUrl }}" class="mobile-nav__app-item" data-mobile-close>
                            <div class="mobile-nav__app-icon" style="background:#f0a500">
                                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="mobile-nav__app-label">Connexion</span>
                        </a>
                        @endif
                    @endauth

                </div>
            </div>

            {{-- Catégories d'articles en grille --}}
            @if ($navItems->isNotEmpty())
            @php
                $mnColors = ['#e8191e','#003f7f','#1a8a40','#f0a500','#8b5cf6','#06b6d4','#f97316','#ec4899','#14b8a6','#6366f1','#84cc16','#f43f5e'];
            @endphp
            <div>
                <div class="mobile-nav__section-title">Catégories d'articles</div>
                <div class="mobile-nav__cat-grid">
                    @foreach ($navItems->take(12) as $rubrique)
                    <a href="{{ $categoryUrl($rubrique) }}" class="mobile-nav__cat-item" data-mobile-close>
                        <div class="mobile-nav__cat-icon" style="background:{{ $mnColors[$loop->index % count($mnColors)] }}">
                            {{ Str::upper(Str::substr($rubrique->name, 0, 2)) }}
                        </div>
                        <span class="mobile-nav__cat-label">{{ $rubrique->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>{{-- end body --}}

        {{-- ── CTA collés en bas ── --}}
        <div class="mobile-nav__cta">
            @auth
                <form method="POST" action="{{ route('logOut') }}">
                    @csrf
                    <button type="submit" class="mobile-nav__cta-btn mobile-nav__cta-btn--outline">Déconnexion</button>
                </form>
            @else
                <a href="{{ $registerUrl }}" class="mobile-nav__cta-btn mobile-nav__cta-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Créer un blog
                </a>
                <a href="{{ $siteRoot }}/advertiser/register" class="mobile-nav__cta-btn mobile-nav__cta-btn--outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Publier une annonce
                </a>
            @endauth
        </div>

    </div>
</div>
