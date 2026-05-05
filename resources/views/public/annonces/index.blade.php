<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: var(--bg); margin: 0; }

        .pub-header {
            background: var(--dark);
            color: rgba(255,255,255,.8);
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .pub-header__logo img { height: 30px; filter: brightness(0) invert(1); }
        .pub-header nav a {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .88rem;
            margin-left: 20px;
            transition: color var(--transition);
        }
        .pub-header nav a:hover { color: var(--gold); }

        .ann-hero {
            background: linear-gradient(135deg, var(--primary) 0%, #0055a5 100%);
            color: #fff;
            padding: 40px 24px;
            text-align: center;
        }
        .ann-hero h1 { font-size: 1.9rem; font-weight: 700; margin: 0 0 6px; }
        .ann-hero p { opacity: .8; margin: 0; font-size: .95rem; }

        .ann-filters {
            background: var(--white);
            padding: 14px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        .ann-filters a {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: .83rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--mid);
            transition: all var(--transition);
        }
        .ann-filters a:hover,
        .ann-filters a.active { background: var(--primary); color: #fff; border-color: var(--primary); }

        .ann-container { max-width: 1200px; margin: 0 auto; padding: 28px 20px; }
        .ann-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(276px, 1fr)); gap: 18px; }

        .ann-card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: box-shadow var(--transition), transform var(--transition);
            display: flex;
            flex-direction: column;
        }
        .ann-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }

        .ann-card__img {
            height: 176px;
            object-fit: cover;
            width: 100%;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--border);
            font-size: 2rem;
        }
        .ann-card__img img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }

        .ann-card__body { padding: 14px 16px; flex: 1; }
        .ann-card__cat { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 7px; }
        .ann-card__cat.emploi { color: #1565c0; }
        .ann-card__cat.immobilier { color: #880e4f; }
        .ann-card__cat.vente_services { color: #6a1b9a; }
        .ann-card__cat.evenements { color: #f57f17; }
        .ann-card__title { font-size: .97rem; font-weight: 600; color: var(--dark); margin-bottom: 7px; line-height: 1.4; }
        .ann-card__desc { font-size: .83rem; color: var(--mid); line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }

        .ann-card__footer {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ann-card__price { font-weight: 700; color: var(--primary); font-size: .88rem; }
        .ann-card__location { font-size: .76rem; color: var(--muted); }

        .pagination-wrap { margin-top: 28px; display: flex; justify-content: center; }

        .empty-state { text-align: center; padding: 60px; color: var(--muted); }
        .empty-state .icon { font-size: 2.8rem; margin-bottom: 12px; }
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

<div class="ann-hero">
    <h1>Petites annonces</h1>
    <p>Emploi, immobilier, services et évènements au Bénin</p>
</div>

<div class="ann-filters">
    <a href="{{ route('annonces.index') }}" class="{{ !$category ? 'active' : '' }}">Toutes</a>
    @foreach ($categories as $key => $label)
        <a href="{{ route('annonces.index', ['category' => $key]) }}" class="{{ $category === $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

<div class="ann-container">
    @if ($annonces->isEmpty())
        <div class="empty-state">
            <div class="icon">📋</div>
            <p>Aucune annonce disponible pour le moment.</p>
        </div>
    @else
        <div class="ann-grid">
            @foreach ($annonces as $annonce)
            <a href="{{ route('annonces.show', $annonce) }}" class="ann-card">
                <div class="ann-card__img">
                    @if ($annonce->images && count($annonce->images) > 0)
                        <img src="{{ asset($annonce->images[0]) }}" alt="{{ $annonce->title }}">
                    @else
                        📋
                    @endif
                </div>
                <div class="ann-card__body">
                    <div class="ann-card__cat {{ $annonce->category }}">{{ $annonce->category_label }}</div>
                    <div class="ann-card__title">{{ $annonce->title }}</div>
                    <div class="ann-card__desc">{{ $annonce->description }}</div>
                </div>
                <div class="ann-card__footer">
                    <span class="ann-card__price">
                        {{ $annonce->price ? number_format($annonce->price, 0, ',', ' ') . ' FCFA' : 'Prix à débattre' }}
                    </span>
                    <span class="ann-card__location">📍 {{ $annonce->location ?? 'Bénin' }}</span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $annonces->links() }}
        </div>
    @endif
</div>

</body>
</html>
