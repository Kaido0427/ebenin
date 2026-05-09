<!doctype html>
@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $isMainDomain = in_array($host, ['e-benin.com', 'e-benin.bj'], true);
    $siteRoot = 'https://' . $baseDomain;
    $defaultTitle = trim($__env->yieldContent('title')) ?: 'E-Benin';
    $defaultDescription =
        trim($__env->yieldContent('meta_description')) ?:
        "E-Benin, le réseau béninois d'actualités, de reportages et de blogs.";
    $canonicalUrl = trim($__env->yieldContent('canonical')) ?: url()->current();
    $ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/ebenins.png');
    $pageClass = trim($__env->yieldContent('body_class'));
@endphp
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $defaultTitle }}</title>
    <meta name="description" content="{{ $defaultDescription }}">
    <meta property="og:title" content="{{ trim($__env->yieldContent('og_title')) ?: $defaultTitle }}">
    <meta property="og:description" content="{{ trim($__env->yieldContent('og_description')) ?: $defaultDescription }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="@yield('og_site_name', 'E-Benin')">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ trim($__env->yieldContent('twitter_title')) ?: $defaultTitle }}">
    <meta name="twitter:description" content="{{ trim($__env->yieldContent('twitter_description')) ?: $defaultDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#003f7f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="E-Benin">
    <link rel="apple-touch-icon" href="{{ asset('images/ebenins.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}?v={{ filemtime(public_path('css/refonte-public.css')) }}">
    <style>
        .pass-wrap { position: relative; display: block; }
        .pass-wrap input { padding-right: 44px !important; }
        .pass-eye {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; padding: 4px; color: #888;
            cursor: pointer; display: flex; align-items: center; line-height: 1;
            z-index: 10;
        }
        .pass-eye:hover { color: #003f7f; }
        .pass-eye svg { width: 18px; height: 18px; display: block; }
    </style>
    @stack('head')
</head>
<body class="{{ $pageClass }}">
    @include('public.partials.header')

    @if (session('success') || session('error'))
        <div class="flash-banner flash-banner--{{ session('success') ? 'success' : 'error' }}">
            <div class="container">
                {{ session('success') ?: session('error') }}
            </div>
        </div>
    @endif

    @yield('content')

    @include('public.partials.footer')

    @if (($showAuthModal ?? true) && $isMainDomain)
        @include('public.partials.auth-modal')
    @endif

    {{-- ── PWA Bottom Navigation (mobile) ── --}}
    @php
        $bnPath = request()->path();
        $bnActive = fn(string $pat) => fnmatch($pat, $bnPath) || $bnPath === $pat;
        $webUser = Auth::guard('web')->user();
    @endphp
    <nav class="bottom-nav" id="bottomNav" aria-label="Navigation mobile">

        {{-- Accueil --}}
        <a href="{{ $siteRoot }}" class="bottom-nav__item {{ $bnPath === '/' ? 'active' : '' }}" aria-label="Accueil">
            <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                <path class="bnav-fill" d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/>
                <polyline points="9 21 9 13 15 13 15 21"/>
            </svg>
            <span class="bottom-nav__label">Accueil</span>
        </a>

        {{-- Articles --}}
        <a href="{{ $siteRoot }}/#actualites" class="bottom-nav__item {{ Str::startsWith($bnPath, 'search') ? 'active' : '' }}" aria-label="Articles">
            <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <line x1="7" y1="8" x2="17" y2="8"/>
                <line x1="7" y1="12" x2="17" y2="12"/>
                <line x1="7" y1="16" x2="13" y2="16"/>
            </svg>
            <span class="bottom-nav__label">Articles</span>
        </a>

        {{-- Annonces --}}
        <a href="{{ $siteRoot }}/annonces" class="bottom-nav__item {{ Str::startsWith($bnPath, 'annonces') ? 'active' : '' }}" aria-label="Annonces">
            <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            <span class="bottom-nav__label">Annonces</span>
        </a>

        {{-- Nécrologies --}}
        <a href="{{ $siteRoot }}/necrologies" class="bottom-nav__item {{ Str::startsWith($bnPath, 'necrologies') ? 'active' : '' }}" aria-label="Nécrologies">
            <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                <line x1="12" y1="2" x2="12" y2="6"/>
                <path d="M9 6h6a3 3 0 0 1 3 3v2a6 6 0 0 1-6 6 6 6 0 0 1-6-6V9a3 3 0 0 1 3-3z"/>
                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
            </svg>
            <span class="bottom-nav__label">Nécrologies</span>
        </a>

        {{-- Connexion / Compte --}}
        @if ($webUser)
            <a href="{{ url('/dashboard') }}" class="bottom-nav__item {{ Str::startsWith($bnPath, 'dashboard') ? 'active' : '' }}" aria-label="Mon compte">
                <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="bottom-nav__label">Compte</span>
            </a>
        @elseif (($showAuthModal ?? true) && $isMainDomain)
            <button type="button" class="bottom-nav__item" data-auth-open="login" aria-label="Se connecter">
                <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="bottom-nav__label">Connexion</span>
            </button>
        @else
            <a href="{{ $siteRoot }}/bloger/login" class="bottom-nav__item" aria-label="Se connecter">
                <svg class="bottom-nav__icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="bottom-nav__label">Connexion</span>
            </a>
        @endif

    </nav>

    <script>
        (function() {
            const mobileNav = document.getElementById('mobileNav');
            const authModal = document.getElementById('auth-login-modal');

            window.toggleMenu = function(forceState) {
                if (!mobileNav) return;
                const shouldOpen = typeof forceState === 'boolean' ? forceState : !mobileNav.classList.contains('open');
                mobileNav.classList.toggle('open', shouldOpen);
                document.body.classList.toggle('menu-open', shouldOpen);
            };

            window.toggleAuthModal = function(forceState) {
                if (!authModal) return;
                const shouldOpen = typeof forceState === 'boolean' ? forceState : !authModal.classList.contains('open');
                authModal.classList.toggle('open', shouldOpen);
                document.body.classList.toggle('modal-open', shouldOpen);
            };

            document.querySelectorAll('[data-mobile-close]').forEach((button) => {
                button.addEventListener('click', () => window.toggleMenu(false));
            });

            document.querySelectorAll('[data-auth-open="login"]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    window.toggleAuthModal(true);
                });
            });

            document.querySelectorAll('[data-auth-close]').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    window.toggleAuthModal(false);
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    window.toggleMenu(false);
                    window.toggleAuthModal(false);
                }
            });

            const query = new URLSearchParams(window.location.search);
            if (query.get('auth') === 'login') {
                window.toggleAuthModal(true);
            }

            @if (($showAuthModal ?? true) && $isMainDomain && request()->path() === '/' && ($errors->has('email') || $errors->has('password')))
                window.toggleAuthModal(true);
            @endif

            document.querySelectorAll('.pass-eye').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = document.getElementById(this.dataset.target);
                    if (!input) return;
                    var isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    this.querySelector('.eye-show').style.display = isHidden ? 'none' : '';
                    this.querySelector('.eye-hide').style.display = isHidden ? '' : 'none';
                });
            });
        })();
    </script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(function() {});
        }
    </script>
</body>
</html>
