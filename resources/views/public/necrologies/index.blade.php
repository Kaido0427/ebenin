<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nécrologies | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #12131a; margin: 0; color: #ddd; font-family: 'Inter', sans-serif; }

        .pub-header {
            background: #0a0a12;
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .pub-header__logo img { height: 30px; filter: brightness(0) invert(1); }
        .pub-header nav a { color: rgba(255,255,255,.5); text-decoration: none; font-size: .88rem; margin-left: 20px; transition: color .2s; }
        .pub-header nav a:hover { color: #c9a84c; }

        .nec-hero {
            background: linear-gradient(180deg, #0a0a12 0%, #12131a 100%);
            color: #fff;
            padding: 48px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .nec-hero h1 { font-size: 1.9rem; font-weight: 700; margin: 0 0 6px; }
        .nec-hero p { color: #777; margin: 0; font-size: .93rem; }
        .nec-hero .candle { font-size: 1.8rem; margin-bottom: 10px; }

        .nec-container { max-width: 1100px; margin: 0 auto; padding: 36px 20px; }
        .nec-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(276px, 1fr)); gap: 22px; }

        .nec-card {
            background: #0d0d1a;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: border-color .2s, transform .2s;
            display: flex;
            flex-direction: column;
        }
        .nec-card:hover { border-color: rgba(201,168,76,.35); transform: translateY(-2px); }

        .nec-card__photo {
            height: 200px;
            overflow: hidden;
            position: relative;
            background: #0a0a12;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-size: 3rem;
        }
        .nec-card__photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }

        .nec-card__body { padding: 18px 20px; flex: 1; }
        .nec-card__name { font-size: 1.1rem; font-weight: 700; color: #eee; margin-bottom: 5px; }
        .nec-card__dates { font-size: .8rem; color: #666; margin-bottom: 10px; }
        .nec-card__msg { font-size: .84rem; color: #888; line-height: 1.6; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }

        .nec-card__footer {
            padding: 10px 20px;
            border-top: 1px solid rgba(255,255,255,.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .74rem;
        }
        .nec-card__by { color: #555; }
        .nec-card__date { color: #444; }
        .has-video { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,.7); color: #eee; font-size: .7rem; padding: 3px 8px; border-radius: 4px; }

        .empty-state { text-align: center; padding: 60px; color: #444; }
        .empty-state .icon { font-size: 2.8rem; margin-bottom: 10px; }

        .pagination-wrap { margin-top: 36px; display: flex; justify-content: center; }
    </style>
</head>
<body>

<header class="pub-header">
    <div class="pub-header__logo">
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
