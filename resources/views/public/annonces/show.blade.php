<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $annonce->title }} | E-Benin Annonces</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #f4f6fb; font-family: 'Inter', sans-serif; margin: 0; }
        .ann-header { background: #003f7f; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .ann-header__logo img { height: 32px; filter: brightness(0) invert(1); }
        .ann-header nav a { color: #a8c7f0; text-decoration: none; font-size: .9rem; margin-left: 20px; }

        .ann-container { max-width: 900px; margin: 32px auto; padding: 0 16px; }
        .back-link { color: #003f7f; text-decoration: none; font-size: .9rem; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { text-decoration: underline; }

        .ann-detail { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,.07); }

        /* Gallery */
        .ann-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 4px; }
        .ann-gallery img { width: 100%; height: 220px; object-fit: cover; object-position: top center; }

        .ann-body { padding: 32px; }
        .ann-meta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .78rem; font-weight: 700; }
        .badge-emploi { background: #e3f2fd; color: #1565c0; }
        .badge-immobilier { background: #fce4ec; color: #880e4f; }
        .badge-vente_services { background: #f3e5f5; color: #6a1b9a; }
        .badge-evenements { background: #fff8e1; color: #f57f17; }

        .ann-title { font-size: 1.6rem; font-weight: 700; color: #0d1b2a; margin-bottom: 8px; }
        .ann-price { font-size: 1.3rem; font-weight: 700; color: #003f7f; margin-bottom: 20px; }
        .ann-desc { font-size: .95rem; color: #444; line-height: 1.7; white-space: pre-wrap; }

        /* Info sidebar */
        .ann-layout { display: grid; grid-template-columns: 1fr 280px; gap: 24px; margin-top: 32px; }
        .ann-contact-card { background: #f8fafc; border-radius: 10px; padding: 20px; border: 1px solid #e8ecf1; }
        .ann-contact-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 16px; }
        .ann-contact-item { display: flex; gap: 10px; align-items: center; margin-bottom: 12px; font-size: .9rem; color: #444; }
        .ann-contact-item .icon { font-size: 1.1rem; width: 24px; text-align: center; }
        .ann-advertiser { margin-top: 16px; padding-top: 16px; border-top: 1px solid #e8ecf1; font-size: .85rem; color: #666; }
        .ann-date { font-size: .8rem; color: #bbb; margin-top: 16px; }

        @media (max-width: 640px) {
            .ann-layout { grid-template-columns: 1fr; }
        }
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
    </nav>
</header>

<div class="ann-container">
    <a href="{{ route('annonces.index') }}" class="back-link">← Retour aux annonces</a>

    <div class="ann-detail">
        @if ($annonce->images && count($annonce->images) > 0)
            <div class="ann-gallery">
                @foreach ($annonce->images as $img)
                    <img src="{{ asset($img) }}" alt="{{ $annonce->title }}">
                @endforeach
            </div>
        @endif

        <div class="ann-body">
            <div class="ann-meta">
                <span class="badge badge-{{ $annonce->category }}">{{ $annonce->category_label }}</span>
                @if ($annonce->location)
                    <span style="color:#666;font-size:.85rem;">📍 {{ $annonce->location }}</span>
                @endif
            </div>

            <div class="ann-title">{{ $annonce->title }}</div>

            @if ($annonce->price)
                <div class="ann-price">{{ number_format($annonce->price, 0, ',', ' ') }} FCFA</div>
            @endif

            <div class="ann-layout">
                <div>
                    <div class="ann-desc">{{ $annonce->description }}</div>
                    <div class="ann-date">Publiée le {{ $annonce->created_at->format('d/m/Y') }}</div>
                </div>

                <div>
                    <div class="ann-contact-card">
                        <h3>Contacter l'annonceur</h3>
                        @if ($annonce->contact_phone)
                            <div class="ann-contact-item">
                                <span class="icon">📞</span>
                                <a href="tel:{{ $annonce->contact_phone }}">{{ $annonce->contact_phone }}</a>
                            </div>
                        @endif
                        @if ($annonce->contact_email)
                            <div class="ann-contact-item">
                                <span class="icon">✉️</span>
                                <a href="mailto:{{ $annonce->contact_email }}">{{ $annonce->contact_email }}</a>
                            </div>
                        @endif
                        @if (!$annonce->contact_phone && !$annonce->contact_email)
                            <p style="color:#999;font-size:.85rem;">Aucune coordonnée renseignée.</p>
                        @endif

                        <div class="ann-advertiser">
                            Publié par <strong>{{ $annonce->advertiser->company_name ?? $annonce->advertiser->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
