<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $annonce->title }} | E-Benin Annonces</title>
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
        .pub-header nav a { color: rgba(255,255,255,.6); text-decoration: none; font-size: .88rem; margin-left: 20px; }
        .pub-header nav a:hover { color: var(--gold); }

        .ann-container { max-width: 920px; margin: 28px auto; padding: 0 16px; }
        .back-link { color: var(--primary); text-decoration: none; font-size: .88rem; margin-bottom: 18px; display: inline-block; }
        .back-link:hover { text-decoration: underline; }

        .ann-detail { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }

        .ann-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 3px; }
        .ann-gallery img { width: 100%; height: 220px; object-fit: cover; object-position: top center; display: block; }

        .ann-body { padding: 28px 32px; }
        .ann-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
        .badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: .76rem; font-weight: 700; }
        .badge-emploi { background: #e3f2fd; color: #1565c0; }
        .badge-immobilier { background: #fce4ec; color: #880e4f; }
        .badge-vente_services { background: #f3e5f5; color: #6a1b9a; }
        .badge-evenements { background: #fff8e1; color: #f57f17; }

        .ann-title { font-size: 1.55rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .ann-price { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 20px; }
        .ann-desc { font-size: .93rem; color: var(--mid); line-height: 1.75; white-space: pre-wrap; }

        .ann-layout { display: grid; grid-template-columns: 1fr 270px; gap: 24px; margin-top: 28px; }
        .ann-contact-card {
            background: var(--bg);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 20px;
        }
        .ann-contact-card h3 { font-size: .97rem; font-weight: 700; margin-bottom: 14px; color: var(--dark); }
        .ann-contact-item { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; font-size: .88rem; color: var(--mid); }
        .ann-contact-item a { color: var(--primary); }
        .ann-advertiser { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); font-size: .83rem; color: var(--muted); }
        .ann-date { font-size: .78rem; color: var(--muted); margin-top: 14px; }

        @media (max-width: 640px) {
            .ann-layout { grid-template-columns: 1fr; }
        }
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
                    <span style="color:var(--muted);font-size:.84rem;">📍 {{ $annonce->location }}</span>
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
                                📞 <a href="tel:{{ $annonce->contact_phone }}">{{ $annonce->contact_phone }}</a>
                            </div>
                        @endif
                        @if ($annonce->contact_email)
                            <div class="ann-contact-item">
                                ✉️ <a href="mailto:{{ $annonce->contact_email }}">{{ $annonce->contact_email }}</a>
                            </div>
                        @endif
                        @if (!$annonce->contact_phone && !$annonce->contact_email)
                            <p style="color:var(--muted);font-size:.84rem;">Aucune coordonnée renseignée.</p>
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
