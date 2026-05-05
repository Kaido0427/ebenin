<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Annonceur') | E-Benin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    @stack('head')
    <style>
        /* ── Advertiser shell ── */
        .adv-shell { min-height: 100vh; display: flex; flex-direction: column; }

        .adv-topbar {
            background: var(--dark);
            color: rgba(255,255,255,.7);
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            font-size: .78rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .adv-topbar a { color: rgba(255,255,255,.6); transition: color var(--transition); }
        .adv-topbar a:hover { color: var(--gold); }
        .adv-topbar__badge {
            background: var(--accent);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .adv-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .adv-header__logo img { height: 36px; }
        .adv-header__nav { display: flex; align-items: center; gap: 6px; }
        .adv-header__nav a {
            padding: 6px 14px;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 500;
            color: var(--mid);
            transition: all var(--transition);
        }
        .adv-header__nav a:hover { background: var(--bg); color: var(--primary); }
        .adv-header__nav a.active { background: var(--primary); color: #fff; }
        .adv-header__user {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: .85rem;
            color: var(--muted);
        }
        .adv-header__user strong { color: var(--dark); }

        /* ── Trial banner ── */
        .trial-banner {
            background: #fff8e1;
            border-bottom: 1px solid #ffe082;
            padding: 8px 24px;
            font-size: .82rem;
            color: #5d4037;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .trial-banner.expired {
            background: #fdecea;
            border-color: #ef9a9a;
            color: #b71c1c;
        }

        /* ── Layout ── */
        .adv-layout { display: flex; flex: 1; min-height: 0; }

        .adv-sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 24px 12px;
        }
        .adv-sidebar__section { margin-bottom: 28px; }
        .adv-sidebar__section h3 {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            padding: 0 10px;
            margin-bottom: 6px;
        }
        .adv-sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius);
            color: var(--mid);
            font-size: .88rem;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all var(--transition);
        }
        .adv-sidebar a:hover { background: var(--bg); color: var(--primary); }
        .adv-sidebar a.active { background: rgba(0,63,127,.08); color: var(--primary); font-weight: 600; }
        .adv-sidebar a .icon { font-size: .95rem; width: 18px; text-align: center; opacity: .7; }

        .adv-main { flex: 1; padding: 32px; background: var(--bg); overflow-x: hidden; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: .88rem;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert--success { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .alert--error { background: #fdecea; color: #b71c1c; border: 1px solid #ef9a9a; }
        .alert--warning { background: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
        .alert p { margin: 0; }
    </style>
</head>
<body class="adv-shell">

<div class="adv-topbar">
    <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
        ← Retour à E-Benin
    </a>
    <span class="adv-topbar__badge">Espace Annonceur</span>
</div>

<header class="adv-header">
    <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}" class="adv-header__logo">
        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
    </a>

    @auth('advertiser')
    <nav class="adv-header__nav">
        <a href="{{ route('advertiser.dashboard') }}" class="{{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('advertiser.annonces.create') }}" class="{{ request()->routeIs('advertiser.annonces.create') ? 'active' : '' }}">+ Annonce</a>
        <a href="{{ route('advertiser.necrologies.create') }}" class="{{ request()->routeIs('advertiser.necrologies.create') ? 'active' : '' }}">+ Nécrologie</a>
    </nav>
    <div class="adv-header__user">
        <strong>{{ Auth::guard('advertiser')->user()->name }}</strong>
        <form method="POST" action="{{ route('advertiser.logout') }}">
            @csrf
            <button type="submit" class="btn btn--outline" style="padding:6px 12px;font-size:.78rem;">Déconnexion</button>
        </form>
    </div>
    @endauth
</header>

@auth('advertiser')
@php
    $adv = Auth::guard('advertiser')->user();
    $trialActive = $adv->trial_ends_at && $adv->trial_ends_at->isFuture();
    $daysLeft = $adv->trial_ends_at ? (int) now()->diffInDays($adv->trial_ends_at, false) : 0;
@endphp
@if ($trialActive && $daysLeft <= 3)
<div class="trial-banner">
    ⏳ Période d'essai : <strong>{{ $daysLeft }} jour(s) restant(s)</strong> — Abonnez-vous avant expiration pour continuer.
    <a href="{{ route('advertiser.subscribe') }}" style="margin-left:auto;font-weight:700;color:var(--primary);">S'abonner →</a>
</div>
@endif
@endauth

@yield('content')

@stack('scripts')
</body>
</html>
