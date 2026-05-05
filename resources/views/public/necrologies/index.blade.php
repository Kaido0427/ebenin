<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nécrologies | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #1a1a2e; font-family: 'Inter', sans-serif; margin: 0; }

        .nec-header { background: #0d0d1a; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,.08); }
        .nec-header__logo img { height: 32px; filter: brightness(0) invert(1); }
        .nec-header nav a { color: #aaa; text-decoration: none; font-size: .9rem; margin-left: 20px; }
        .nec-header nav a:hover { color: #fff; }

        .nec-hero { background: linear-gradient(180deg, #0d0d1a 0%, #1a1a2e 100%); color: #fff; padding: 48px 24px; text-align: center; border-bottom: 1px solid rgba(255,255,255,.06); }
        .nec-hero h1 { font-size: 2rem; font-weight: 700; margin: 0 0 8px; }
        .nec-hero p { color: #999; margin: 0; font-size: .95rem; }
        .nec-hero .candle { font-size: 2rem; margin-bottom: 12px; }

        .nec-container { max-width: 1100px; margin: 0 auto; padding: 40px 24px; }

        /* Grid */
        .nec-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }

        /* Card */
        .nec-card { background: #0d0d1a; border: 1px solid rgba(255,255,255,.08); border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit; transition: border-color .2s, transform .2s; display: flex; flex-direction: column; }
        .nec-card:hover { border-color: rgba(255,255,255,.2); transform: translateY(-2px); }
        .nec-card__photo { height: 200px; overflow: hidden; position: relative; background: #111; display: flex; align-items: center; justify-content: center; color: #444; font-size: 3rem; }
        .nec-card__photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
        .nec-card__body { padding: 20px; flex: 1; }
        .nec-card__name { font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .nec-card__dates { font-size: .82rem; color: #888; margin-bottom: 12px; }
        .nec-card__msg { font-size: .85rem; color: #aaa; line-height: 1.6; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }
        .nec-card__footer { padding: 12px 20px; border-top: 1px solid rgba(255,255,255,.06); display: flex; justify-content: space-between; align-items: center; }
        .nec-card__by { font-size: .75rem; color: #666; }
        .nec-card__date { font-size: .75rem; color: #555; }
        .nec-card .has-video { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,.7); color: #fff; font-size: .72rem; padding: 3px 8px; border-radius: 4px; }

        .empty-state { text-align: center; padding: 60px; color: #555; }
        .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }

        .pagination-wrap { margin-top: 40px; display: flex; justify-content: center; }
    </style>
</head>
<body>

<header class="nec-header">
    <div class="nec-header__logo">
        <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
            <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
        </a>
    </div>
    <nav>
        <a href="{{ route('annonces.index') }}">Annonces</a>
        <a href="{{ route('necrologies.index') }}">Nécrologies</a>
        <a href="{{ route('advertiser.login') }}">Espace annonceur</a>
    </nav>
</header>

<div class="nec-hero">
    <div class="candle">🕯️</div>
    <h1>Nécrologies</h1>
    <p>En mémoire de ceux qui nous ont quittés</p>
</div>

<div class="nec-container">
    @if ($necrologies->isEmpty())
        <div class="empty-state">
            <div class="icon">🕊️</div>
            <p>Aucune notice publiée pour le moment.</p>
        </div>
    @else
        <div class="nec-grid">
            @foreach ($necrologies as $necro)
            <a href="{{ route('necrologies.show', $necro) }}" class="nec-card">
                <div class="nec-card__photo">
                    @if ($necro->photo)
                        <img src="{{ asset($necro->photo) }}" alt="{{ $necro->nom_defunt }}">
                    @else
                        🕊️
                    @endif
                    @if ($necro->video)
                        <span class="has-video">▶ Vidéo</span>
                    @endif
                </div>
                <div class="nec-card__body">
                    <div class="nec-card__name">{{ $necro->nom_defunt }}</div>
                    <div class="nec-card__dates">
                        @if ($necro->date_naissance)
                            {{ $necro->date_naissance->format('d/m/Y') }} —
                        @endif
                        {{ $necro->date_deces->format('d/m/Y') }}
                    </div>
                    @if ($necro->message)
                        <div class="nec-card__msg">{{ $necro->message }}</div>
                    @endif
                </div>
                <div class="nec-card__footer">
                    <span class="nec-card__by">{{ $necro->advertiser->company_name ?? $necro->advertiser->name }}</span>
                    <span class="nec-card__date">{{ $necro->created_at->format('d/m/Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $necrologies->links() }}
        </div>
    @endif
</div>

</body>
</html>
