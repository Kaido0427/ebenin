@extends('advertiser.layouts.app')

@section('title', 'Dashboard')

@push('head')
<style>
    .adv-page { display: flex; flex: 1; min-height: 0; }

    .adv-sidebar {
        width: 230px;
        flex-shrink: 0;
        background: var(--white);
        border-right: 1px solid var(--border);
        padding: 20px 10px;
    }
    .adv-sidebar__section { margin-bottom: 24px; }
    .adv-sidebar__section h3 {
        font-size: .65rem;
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
        padding: 8px 12px;
        border-radius: var(--radius);
        color: var(--mid);
        font-size: .87rem;
        font-weight: 500;
        margin-bottom: 2px;
        transition: all var(--transition);
        text-decoration: none;
    }
    .adv-sidebar a:hover { background: var(--bg); color: var(--primary); }
    .adv-sidebar a.active { background: rgba(0,63,127,.08); color: var(--primary); font-weight: 600; }
    .adv-sidebar a .icon { width: 18px; text-align: center; }

    .adv-main { flex: 1; padding: 28px 32px; background: var(--bg); overflow-x: hidden; }

    .adv-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 28px; }
    .adv-stat-card { background: var(--white); border-radius: var(--radius); padding: 18px 20px; border: 1px solid var(--border); }
    .adv-stat-card__val { font-size: 1.9rem; font-weight: 700; color: var(--primary); }
    .adv-stat-card__label { font-size: .82rem; color: var(--muted); margin-top: 3px; }

    .adv-section { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); margin-bottom: 20px; overflow: hidden; }
    .adv-section__head { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--border); }
    .adv-section__head h2 { font-size: 1rem; font-weight: 700; color: var(--dark); margin: 0; }

    .adv-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
    .adv-table th { text-align: left; padding: 10px 16px; font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); border-bottom: 1px solid var(--border); background: var(--bg); }
    .adv-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--dark); vertical-align: middle; }
    .adv-table tr:last-child td { border-bottom: none; }

    .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; }
    .badge-active { background: #e8f5e9; color: #2e7d32; }
    .badge-draft { background: var(--bg); color: var(--muted); }
    .badge-emploi { background: #e3f2fd; color: #1565c0; }
    .badge-immobilier { background: #fce4ec; color: #880e4f; }
    .badge-vente_services { background: #f3e5f5; color: #6a1b9a; }
    .badge-evenements { background: #fff8e1; color: #f57f17; }

    .empty-state { text-align: center; padding: 40px; color: var(--muted); }
    .empty-state .icon { font-size: 2rem; margin-bottom: 10px; }

    .actions-cell { display: flex; gap: 6px; }
    .btn-sm { padding: 5px 12px !important; font-size: .8rem !important; }
    .btn--danger { background: #fdecea; color: #b71c1c; border: 1px solid #ef9a9a; }
    .btn--danger:hover { background: #f5c6cb; }
</style>
@endpush

@section('content')
<div class="adv-page">
    <aside class="adv-sidebar">
        <div class="adv-sidebar__section">
            <h3>Menu</h3>
            <a href="{{ route('advertiser.dashboard') }}" class="{{ request()->routeIs('advertiser.dashboard') ? 'active' : '' }}">
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

    <main class="adv-main">
        @if (session('success'))
            <div class="alert alert--success" style="margin-bottom:20px;">✅ {{ session('success') }}</div>
        @endif

        <h1 style="font-size:1.4rem;font-weight:700;color:var(--dark);margin-bottom:20px;">
            Bonjour, {{ $advertiser->name }}
        </h1>

        <div class="adv-stats">
            <div class="adv-stat-card">
                <div class="adv-stat-card__val">{{ $annonces->count() }}</div>
                <div class="adv-stat-card__label">Annonce(s) publiée(s)</div>
            </div>
            <div class="adv-stat-card">
                <div class="adv-stat-card__val">{{ $necrologies->count() }}</div>
                <div class="adv-stat-card__label">Notice(s) de décès</div>
            </div>
            @php
                $trialEndsAt = $advertiser->trial_ends_at;
                $isTrialActive = $trialEndsAt && $trialEndsAt->isFuture();
                $daysLeft = $trialEndsAt ? (int) now()->diffInDays($trialEndsAt, false) : 0;
            @endphp
            <div class="adv-stat-card">
                <div class="adv-stat-card__val">{{ $isTrialActive ? $daysLeft.'j' : '—' }}</div>
                <div class="adv-stat-card__label">Jours d'essai restants</div>
            </div>
        </div>

        {{-- Annonces --}}
        <div class="adv-section">
            <div class="adv-section__head">
                <h2>Mes annonces</h2>
                <a href="{{ route('advertiser.annonces.create') }}" class="btn btn--primary btn-sm">+ Nouvelle annonce</a>
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
                            <th>Titre</th><th>Catégorie</th><th>Localisation</th><th>Statut</th><th>Date</th><th>Actions</th>
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
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('advertiser.annonces.edit', $annonce) }}" class="btn btn--outline btn-sm">Modifier</a>
                                    <form method="POST" action="{{ route('advertiser.annonces.destroy', $annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Nécrologies --}}
        <div class="adv-section">
            <div class="adv-section__head">
                <h2>Mes notices de décès</h2>
                <a href="{{ route('advertiser.necrologies.create') }}" class="btn btn--primary btn-sm">+ Nouvelle notice</a>
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
                            <th>Nom du défunt</th><th>Date de décès</th><th>Statut</th><th>Publié le</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($necrologies as $necro)
                        <tr>
                            <td><strong>{{ $necro->nom_defunt }}</strong></td>
                            <td>{{ $necro->date_deces->format('d/m/Y') }}</td>
                            <td><span class="badge badge-{{ $necro->status }}">{{ ucfirst($necro->status) }}</span></td>
                            <td>{{ $necro->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('advertiser.necrologies.edit', $necro) }}" class="btn btn--outline btn-sm">Modifier</a>
                                    <form method="POST" action="{{ route('advertiser.necrologies.destroy', $necro) }}" onsubmit="return confirm('Supprimer cette notice ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn-sm">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>
</div>
@endsection
