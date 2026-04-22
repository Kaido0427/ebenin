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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
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
        })();
    </script>
    @stack('scripts')
</body>
</html>
