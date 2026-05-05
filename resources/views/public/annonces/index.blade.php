<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonces | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #f4f6fb; font-family: 'Inter', sans-serif; margin: 0; }

        /* Header */
        .ann-header { background: #003f7f; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .ann-header__logo img { height: 32px; filter: brightness(0) invert(1); }
        .ann-header nav a { color: #a8c7f0; text-decoration: none; font-size: .9rem; margin-left: 20px; }
        .ann-header nav a:hover { color: #fff; }

        /* Hero banner */
        .ann-hero { background: linear-gradient(135deg, #003f7f 0%, #0066cc 100%); color: #fff; padding: 40px 24px; text-align: center; }
        .ann-hero h1 { font-size: 2rem; font-weight: 700; margin: 0 0 8px; }
        .ann-hero p { opacity: .8; margin: 0 0 24px; }

        /* Search & filters */
        .ann-filters { background: #fff; padding: 16px 24px; border-bottom: 1px solid #e8ecf1; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .ann-filters a { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: .85rem; font-weight: 600; text-decoration: none; border: 1px solid #dde1e9; color: #555; }
        .ann-filters a:hover, .ann-filters a.active { background: #003f7f; color: #fff; border-color: #003f7f; }

        /* Grid */
        .ann-container { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }
        .ann-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }

        /* Card */
        .ann-card { background: #fff; border-radius: 10px; box-shadow: 0 1px 6px rgba(0,0,0,.06); overflow: hidden; text-decoration: none; color: inherit; transition: box-shadow .2s, transform .2s; display: flex; flex-direction: column; }
        .ann-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.12); transform: translateY(-2px); }
        .ann-card__img { height: 180px; object-fit: cover; object-position: top center; width: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #bbb; font-size: 2rem; }
        .ann-card__img img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }
        .ann-card__body { padding: 16px; flex: 1; }
        .ann-card__cat { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
        .ann-card__cat.emploi { color: #1565c0; }
        .ann-card__cat.immobilier { color: #880e4f; }
        .ann-card__cat.vente_services { color: #6a1b9a; }
        .ann-card__cat.evenements { color: #f57f17; }
        .ann-card__title { font-size: 1rem; font-weight: 600; color: #0d1b2a; margin-bottom: 8px; line-height: 1.4; }
        .ann-card__desc { font-size: .85rem; color: #666; line-height: 1.5; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }
        .ann-card__footer { padding: 12px 16px; border-top: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; }
        .ann-card__price { font-weight: 700; color: #003f7f; font-size: .9rem; }
        .ann-card__location { font-size: .78rem; color: #999; }

        /* Pagination */
        .pagination-wrap { margin-top: 32px; display: flex; justify-content: center; }
        .pagination-wrap .pagination { display: flex; gap: 4px; list-style: none; padding: 0; }
        .pagination-wrap .page-item .page-link { display: inline-block; padding: 8px 14px; border-radius: 8px; border: 1px solid #dde1e9; color: #333; text-decoration: none; font-size: .88rem; }
        .pagination-wrap .page-item.active .page-link { background: #003f7f; color: #fff; border-color: #003f7f; }

        .empty-state { text-align: center; padding: 60px; color: #999; }
        .empty-state .icon { font-size: 3rem; margin-bottom: 12px; }
    </style>
</head>
<body>

<header class="ann-header">
    <div class="ann-header__logo">
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
