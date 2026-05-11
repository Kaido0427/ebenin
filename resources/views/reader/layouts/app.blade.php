<!doctype html>
@php
    $host       = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot   = 'https://' . $baseDomain;
    $readerUser = Auth::guard('reader')->user()
               ?? Auth::guard('web')->user()
               ?? Auth::guard('advertiser')->user();
    $path       = request()->path();
@endphp
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#001a3e">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="E-Benin App">
    <title>@yield('title', 'E-Benin') — App</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/ebenins.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/reader-app.css') }}?v={{ filemtime(public_path('css/reader-app.css')) }}">
    @stack('head')
</head>
<body class="@yield('body_class', '')">

{{-- ── Header ── --}}
<header class="ra-header">
    <a href="/reader" class="ra-header__logo">
        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" class="ra-header__logo-img">
    </a>
    <div class="ra-header__actions">
        @stack('header_actions')
        <a href="/reader?q=" class="ra-header__btn" aria-label="Recherche"
           onclick="event.preventDefault();document.getElementById('ra-search-wrap').classList.toggle('open')">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </a>
    </div>
</header>

{{-- ── Category tabs slot (filled by home view) ── --}}
@stack('tabs')

{{-- ── Flash ── --}}
@if(session('success'))
    <div class="ra-alert ra-alert--success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="ra-alert ra-alert--error">{{ session('error') }}</div>
@endif

{{-- ── Content ── --}}
@yield('content')

{{-- ── Bottom Nav ── --}}
<nav class="ra-nav" aria-label="Navigation">

    <a href="/reader"
       class="ra-nav__item {{ $path === 'reader' || $path === 'reader/' ? 'active' : '' }}"
       aria-label="Accueil">
        <svg class="ra-nav__icon ra-nav__icon--fill" viewBox="0 0 24 24">
            <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/>
            <polyline points="9 21 9 13 15 13 15 21"/>
        </svg>
        <span>Accueil</span>
    </a>

    <a href="/reader/categories"
       class="ra-nav__item {{ $path === 'reader/categories' ? 'active' : '' }}"
       aria-label="Articles">
        <svg class="ra-nav__icon" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <line x1="7" y1="8" x2="17" y2="8"/>
            <line x1="7" y1="12" x2="17" y2="12"/>
            <line x1="7" y1="16" x2="13" y2="16"/>
        </svg>
        <span>Articles</span>
    </a>

    <a href="/reader/annonces"
       class="ra-nav__item {{ Str::startsWith($path, 'reader/annonces') ? 'active' : '' }}"
       aria-label="Annonces">
        <svg class="ra-nav__icon" viewBox="0 0 24 24">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        <span>Annonces</span>
    </a>

    <a href="/reader/necrologies"
       class="ra-nav__item {{ Str::startsWith($path, 'reader/necrologies') ? 'active' : '' }}"
       aria-label="Nécrologies">
        <svg class="ra-nav__icon" viewBox="0 0 24 24">
            {{-- bougie --}}
            <rect x="9" y="10" width="6" height="10" rx="1"/>
            <path d="M9 20 Q12 22 15 20"/>
            <line x1="12" y1="10" x2="12" y2="6"/>
            <path d="M12 6 Q14 4 12 2 Q10 4 12 6" fill="currentColor" stroke="none"/>
        </svg>
        <span>Nécrologies</span>
    </a>

    <a href="/reader/profil"
       class="ra-nav__item {{ $path === 'reader/profil' ? 'active' : '' }}"
       aria-label="Profil">
        <svg class="ra-nav__icon" viewBox="0 0 24 24">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
        <span>Profil</span>
    </a>

</nav>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(function(){});
    }
</script>
@stack('scripts')
</body>
</html>
