<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard annonceur | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        * { box-sizing: border-box; }
        body { background: #f4f6fb; font-family: 'Inter', sans-serif; margin: 0; }

        /* Topbar */
        .adv-topbar { background: #003f7f; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .adv-topbar__logo img { height: 32px; filter: brightness(0) invert(1); }
        .adv-topbar__right { display: flex; align-items: center; gap: 16px; font-size: .9rem; }
        .adv-topbar__right a { color: #a8c7f0; text-decoration: none; }
        .adv-topbar__right a:hover { color: #fff; }

        /* Trial bar */
        .trial-bar { background: #fff3cd; color: #856404; text-align: center; padding: 8px; font-size: .85rem; }
        .trial-bar.expired { background: #fdecea; color: #b71c1c; }

        /* Layout */
        .adv-layout { display: grid; grid-template-columns: 220px 1fr; min-height: calc(100vh - 60px); }
        .adv-sidebar { background: #fff; border-right: 1px solid #e8ecf1; padding: 24px 0; }
        .adv-sidebar__section { padding: 0 16px; margin-bottom: 24px; }
        .adv-sidebar__section h3 { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: #999; margin-bottom: 8px; }
        .adv-sidebar a { display: flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: 8px; color: #333; text-decoration: none; font-size: .9rem; margin-bottom: 2px; }
        .adv-sidebar a:hover, .adv-sidebar a.active { background: #eef3fb; color: #003f7f; font-weight: 600; }
        .adv-sidebar a span.icon { font-size: 1rem; width: 20px; text-align: center; }

        /* Content */
        .adv-content { padding: 32px; }
        .adv-content h1 { font-size: 1.5rem; font-weight: 700; color: #0d1b2a; margin-bottom: 24px; }

        /* Stats */
        .adv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 32px; }
        .adv-stat { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
        .adv-stat__val { font-size: 2rem; font-weight: 700; color: #003f7f; }
        .adv-stat__label { font-size: .85rem; color: #666; margin-top: 4px; }

        /* Sections */
        .adv-section { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 24px; }
        .adv-section__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
        .adv-section__header h2 { font-size: 1.1rem; font-weight: 700; color: #0d1b2a; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: .88rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #003f7f; color: #fff; }
        .btn-primary:hover { background: #002d5c; }
        .btn-danger { background: #fdecea; color: #b71c1c; }
        .btn-danger:hover { background: #f5c6cb; }
        .btn-outline { background: #fff; border: 1px solid #dde1e9; color: #333; }
        .btn-outline:hover { border-color: #003f7f; color: #003f7f; }

        /* Table */
        .adv-table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        .adv-table th { text-align: left; padding: 10px 12px; font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: #999; border-bottom: 1px solid #f0f0f0; }
        .adv-table td { padding: 12px; border-bottom: 1px solid #f8f8f8; color: #333; vertical-align: middle; }
        .adv-table tr:last-child td { border-bottom: none; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-active { background: #e8f5e9; color: #2e7d32; }
        .badge-draft { background: #f5f5f5; color: #757575; }
        .badge-emploi { background: #e3f2fd; color: #1565c0; }
        .badge-immobilier { background: #fce4ec; color: #880e4f; }
        .badge-vente_services { background: #f3e5f5; color: #6a1b9a; }
        .badge-evenements { background: #fff8e1; color: #f57f17; }

        .empty-state { text-align: center; padding: 40px; color: #999; }
        .empty-state .icon { font-size: 2.5rem; margin-bottom: 12px; }

        .success-banner { background: #e8f5e9; color: #2e7d32; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .9rem; }
        .warning-banner { background: #fff3cd; color: #856404; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .9rem; }
    </style>
</head>
<body>

<div class="adv-topbar">
    <div class="adv-topbar__logo">
        <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
            <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
        </a>
    </div>
    <div class="adv-topbar__right">
        <span>{{ $advertiser->name }}</span>
        <form method="POST" action="{{ route('advertiser.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#a8c7f0;cursor:pointer;font-size:.9rem;">Déconnexion</button>
        </form>
    </div>
</div>

@php
    $trialEndsAt = $advertiser->trial_ends_at;
    $isTrialActive = $trialEndsAt && $trialEndsAt->isFuture();
    $daysLeft = $trialEndsAt ? now()->diffInDays($trialEndsAt, false) : 0;
@endphp

@if ($isTrialActive)
    <div class="trial-bar">
        ⏳ Période d'essai : <strong>{{ $daysLeft }} jour(s)</strong> restant(s) — valable jusqu'au {{ $trialEndsAt->format('d/m/Y à H:i') }}
    </div>
@endif

@if (session('success'))
    <div class="success-banner" style="margin: 16px 24px 0;">{{ session('success') }}</div>
@endif

<div class="adv-layout">
    <aside class="adv-sidebar">
        <div class="adv-sidebar__section">
            <h3>Menu</h3>
            <a href="{{ route('advertiser.dashboard') }}" class="active">
                <span class="icon">📊</span> Tableau de bord
            </a>
        </div>
        <div class="adv-sidebar__section">
            <h3>Annonces</h3>
            <a href="{{ route('advertiser.annonces.create') }}">
                <span class="icon">➕</span> Nouvelle annonce
            </a>
        </div>
        <div class="adv-sidebar__section">
            <h3>Nécrologies</h3>
            <a href="{{ route('advertiser.necrologies.create') }}">
                <span class="icon">🕊️</span> Nouvelle notice
            </a>
        </div>
        <div class="adv-sidebar__section">
            <h3>Pages publiques</h3>
            <a href="{{ route('annonces.index') }}" target="_blank">
                <span class="icon">🔗</span> Voir les annonces
            </a>
            <a href="{{ route('necrologies.index') }}" target="_blank">
                <span class="icon">🔗</span> Voir les nécrologies
            </a>
        </div>
    </aside>

    <main class="adv-content">
        <h1>Bonjour, {{ $advertiser->name }} 👋</h1>

        <div class="adv-stats">
            <div class="adv-stat">
                <div class="adv-stat__val">{{ $annonces->count() }}</div>
                <div class="adv-stat__label">Annonce(s) publiée(s)</div>
            </div>
            <div class="adv-stat">
                <div class="adv-stat__val">{{ $necrologies->count() }}</div>
                <div class="adv-stat__label">Notice(s) de décès</div>
            </div>
            <div class="adv-stat">
                <div class="adv-stat__val">{{ $isTrialActive ? $daysLeft.'j' : '—' }}</div>
                <div class="adv-stat__label">Jours d'essai restants</div>
            </div>
        </div>

        {{-- Annonces --}}
        <div class="adv-section">
            <div class="adv-section__header">
                <h2>Mes annonces</h2>
                <a href="{{ route('advertiser.annonces.create') }}" class="btn btn-primary">+ Nouvelle annonce</a>
            </div>

            @if ($annonces->isEmpty())
                <div class="empty-state">
                    <div class="icon">📋</div>
                    <p>Aucune annonce publiée pour l'instant.</p>
                </div>
            @else
                <table class="adv-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Localisation</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($annonces as $annonce)
                        <tr>
                            <td><strong>{{ $annonce->title }}</strong></td>
                            <td><span class="badge badge-{{ $annonce->category }}">{{ $annonce->category_label }}</span></td>
                            <td>{{ $annonce->location ?? '—' }}</td>
                            <td><span class="badge badge-{{ $annonce->status }}">{{ ucfirst($annonce->status) }}</span></td>
                            <td>{{ $annonce->created_at->format('d/m/Y') }}</td>
                            <td style="display:flex;gap:6px;">
                                <a href="{{ route('advertiser.annonces.edit', $annonce) }}" class="btn btn-outline">Modifier</a>
                                <form method="POST" action="{{ route('advertiser.annonces.destroy', $annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Nécrologies --}}
        <div class="adv-section">
            <div class="adv-section__header">
                <h2>Mes notices de décès</h2>
                <a href="{{ route('advertiser.necrologies.create') }}" class="btn btn-primary">+ Nouvelle notice</a>
            </div>

            @if ($necrologies->isEmpty())
                <div class="empty-state">
                    <div class="icon">🕊️</div>
                    <p>Aucune notice publiée pour l'instant.</p>
                </div>
            @else
                <table class="adv-table">
                    <thead>
                        <tr>
                            <th>Nom du défunt</th>
                            <th>Date de décès</th>
                            <th>Statut</th>
                            <th>Publié le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($necrologies as $necro)
                        <tr>
                            <td><strong>{{ $necro->nom_defunt }}</strong></td>
                            <td>{{ $necro->date_deces->format('d/m/Y') }}</td>
                            <td><span class="badge badge-{{ $necro->status }}">{{ ucfirst($necro->status) }}</span></td>
                            <td>{{ $necro->created_at->format('d/m/Y') }}</td>
                            <td style="display:flex;gap:6px;">
                                <a href="{{ route('advertiser.necrologies.edit', $necro) }}" class="btn btn-outline">Modifier</a>
                                <form method="POST" action="{{ route('advertiser.necrologies.destroy', $necro) }}" onsubmit="return confirm('Supprimer cette notice ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>
</div>

</body>
</html>
