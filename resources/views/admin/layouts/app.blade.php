<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin E-Benin')</title>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
</head>
@php
    $adminUser = auth('admin')->user();
    $currentSection = match (true) {
        request()->routeIs('admin.dashboard') => 'dashboard',
        request()->routeIs('admin.users.*', 'admin.blogs.*', 'admin.posts.*') => 'tables',
        request()->routeIs('admin.payments.*', 'admin.subscriptions.*') => 'billing',
        request()->routeIs('admin.profile*') => 'profile',
        request()->routeIs('admin.admins.*') => 'admins',
        default => 'dashboard',
    };
@endphp
<body data-theme="{{ $adminUser->preferred_theme ?? 'light' }}">
    <div class="admin-app">
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar__inner">
                <div class="admin-sidebar__top">
                    <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                        <span class="admin-brand__mark">EB</span>
                        <span class="admin-brand__copy">
                            <strong>Back Office E-Benin</strong>
                            <small>Pilotage centralise</small>
                        </span>
                    </a>
                    <button type="button" class="sidebar-close" data-sidebar-close aria-label="Fermer le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>

                <div class="sidebar-group">
                    <div class="sidebar-group__label">Navigation</div>
                    <nav class="sidebar-nav">
                        <a class="sidebar-link {{ $currentSection === 'dashboard' ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="sidebar-link__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 13h7V4H4zm9 7h7v-9h-7zm0-11h7V4h-7zM4 20h7v-5H4z" />
                                </svg>
                            </span>
                            <span>Tableau de bord</span>
                        </a>
                        <a class="sidebar-link {{ $currentSection === 'tables' ? 'is-active' : '' }}" href="{{ route('admin.users.index') }}">
                            <span class="sidebar-link__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 6h16M4 12h16M4 18h16" />
                                    <path d="M7 4v16M17 4v16" />
                                </svg>
                            </span>
                            <span>Tables</span>
                        </a>
                        <a class="sidebar-link {{ $currentSection === 'billing' ? 'is-active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <span class="sidebar-link__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="6" width="18" height="12" rx="2" />
                                    <path d="M3 10h18" />
                                </svg>
                            </span>
                            <span>Facturation</span>
                        </a>
                        <a class="sidebar-link {{ $currentSection === 'profile' ? 'is-active' : '' }}" href="{{ route('admin.profile') }}">
                            <span class="sidebar-link__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                    <circle cx="12" cy="8" r="4" />
                                </svg>
                            </span>
                            <span>Profil</span>
                        </a>
                    </nav>
                </div>

                @if (($adminUser->role ?? null) === 'super_admin')
                    <div class="sidebar-group sidebar-group--secondary">
                        <div class="sidebar-group__label">Administration</div>
                        <nav class="sidebar-nav">
                            <a class="sidebar-link {{ $currentSection === 'admins' ? 'is-active' : '' }}" href="{{ route('admin.admins.index') }}">
                                <span class="sidebar-link__icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7z" />
                                        <path d="M9.5 12l1.8 1.8L15 10.2" />
                                    </svg>
                                </span>
                                <span>Admins</span>
                            </a>
                        </nav>
                    </div>
                @endif

                <div class="sidebar-profile">
                    <div class="sidebar-profile__avatar">{{ strtoupper(substr($adminUser->name ?? 'AD', 0, 2)) }}</div>
                    <div class="sidebar-profile__copy">
                        <strong>{{ $adminUser->name ?? 'Compte admin' }}</strong>
                        <span>{{ str_replace('_', ' ', $adminUser->role ?? 'admin') }}</span>
                        <small>{{ $adminUser->email ?? '' }}</small>
                    </div>
                </div>

                <div class="sidebar-support">
                    <div class="sidebar-support__eyebrow">Cockpit E-Benin</div>
                    <strong>Besoin d'aide ?</strong>
                    <p>Consulte la documentation interne et les guides de prise en main.</p>
                    <a href="{{ route('admin.profile') }}" class="sidebar-support__button">Documentation</a>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-support__button sidebar-support__button--alt">Passer au cockpit</a>
                </div>
            </div>
        </aside>
        <button type="button" class="sidebar-backdrop" data-sidebar-close aria-label="Fermer le menu"></button>

        <main class="admin-main">
            <div class="topbar-shell">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir le menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

                <div class="topbar-heading">
                    <div class="topbar-heading__eyebrow">@yield('page_eyebrow', 'Administration')</div>
                    <h1>@yield('page_title', 'Back Office')</h1>
                    <p>@yield('page_subtitle', 'Pilotage centralise de la plateforme E-Benin')</p>
                </div>

                <div class="topbar-tools">
                    <label class="topbar-search" aria-label="Recherche">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M20 20l-3.5-3.5" />
                        </svg>
                        <input type="search" placeholder="@yield('search_placeholder', 'Rechercher dans le cockpit')" autocomplete="off">
                    </label>

                    <a href="{{ route('admin.profile') }}" class="topbar-chip">
                        <span class="topbar-chip__avatar">{{ strtoupper(substr($adminUser->name ?? 'A', 0, 1)) }}</span>
                        <span>{{ $adminUser->name ?? 'Admin' }}</span>
                    </a>

                    <button type="button" class="theme-toggle" id="themeToggle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="4.5" />
                            <path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8" />
                        </svg>
                        <span>Theme</span>
                    </button>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Se deconnecter</button>
                    </form>
                </div>
            </div>

            @hasSection('page_tabs')
                <div class="page-tabs-shell">
                    @yield('page_tabs')
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        (function () {
            var body = document.body;
            var key = 'ebenin-admin-theme';
            var sidebarKey = 'ebenin-admin-sidebar';
            var btn = document.getElementById('themeToggle');
            var sidebarToggle = document.getElementById('sidebarToggle');
            var closeButtons = document.querySelectorAll('[data-sidebar-close]');
            var savedTheme = localStorage.getItem(key);
            var savedSidebar = localStorage.getItem(sidebarKey);

            if (savedTheme) {
                body.setAttribute('data-theme', savedTheme);
            }

            if (savedSidebar === 'open') {
                body.classList.add('sidebar-open');
            }

            function syncInputs(theme) {
                document.querySelectorAll('[data-theme-input]').forEach(function (input) {
                    input.value = theme;
                });
            }

            function setSidebar(open) {
                body.classList.toggle('sidebar-open', open);
                localStorage.setItem(sidebarKey, open ? 'open' : 'closed');
            }

            syncInputs(body.getAttribute('data-theme') || 'light');

            if (btn) {
                btn.addEventListener('click', function () {
                    var nextTheme = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    body.setAttribute('data-theme', nextTheme);
                    localStorage.setItem(key, nextTheme);
                    syncInputs(nextTheme);
                });
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    setSidebar(!body.classList.contains('sidebar-open'));
                });
            }

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    setSidebar(false);
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1080) {
                    body.classList.remove('sidebar-open');
                }
            });
        }());
    </script>
</body>
</html>
