@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $isMainDomain = in_array($host, ['e-benin.com', 'e-benin.bj'], true);
    $organization = $organization ?? null;
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
                    @foreach ($primaryNav as $rubrique)
                        <li class="nav__item {{ (string) $activeCategoryId === (string) $rubrique->id ? 'active' : '' }}">
                            <a class="nav__link" href="{{ $categoryUrl($rubrique) }}">{{ $rubrique->name }}</a>
                        </li>
                    @endforeach
                    @if ($overflowNav->isNotEmpty())
                        <li class="nav__item">
                            <a href="#" class="nav__link">
                                Plus
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </a>
                            <div class="nav__dropdown">
                                @foreach ($overflowNav as $rubrique)
                                    <a href="{{ $categoryUrl($rubrique) }}">{{ $rubrique->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
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
                    @if ($isMainDomain)
                        <a href="{{ $loginUrl }}" class="btn btn--outline" data-auth-open="login">Connexion</a>
                    @else
                        <a href="{{ $loginUrl }}" class="btn btn--outline">Connexion</a>
                    @endif
                    <a href="{{ $registerUrl }}" class="btn btn--primary">S'inscrire</a>
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
        <div class="mobile-nav__head">
            <div class="mobile-nav__logo-wrap">
                <img src="{{ $logoUrl }}" alt="{{ $organization->organization_name ?? 'E-Benin' }}" class="logo__img">
            </div>
            <button class="mobile-nav__close" type="button" data-mobile-close aria-label="Fermer">✕</button>
        </div>
        <div class="mobile-nav__links">
            <a href="{{ $homeUrl }}">Accueil</a>
            @foreach ($navItems->take(10) as $rubrique)
                <a href="{{ $categoryUrl($rubrique) }}">{{ $rubrique->name }}</a>
            @endforeach
            <a href="{{ $policyUrl }}">Confidentialité</a>
            <a href="mailto:contact@savplus.net">Contact</a>
            @auth
                @if ($dashboardUrl)
                    <a href="{{ $dashboardUrl }}">Tableau de bord</a>
                @endif
                <form method="POST" action="{{ route('logOut') }}" class="mobile-nav__form">
                    @csrf
                    <button type="submit" class="btn btn--primary">Déconnexion</button>
                </form>
            @else
                @if ($isMainDomain)
                    <a href="{{ $loginUrl }}" data-auth-open="login">Connexion</a>
                @else
                    <a href="{{ $loginUrl }}">Connexion</a>
                @endif
                <a href="{{ $registerUrl }}">Créer un blog</a>
                <a href="{{ $forgotUrl }}">Mot de passe oublié</a>
            @endauth
        </div>
    </div>
</div>
