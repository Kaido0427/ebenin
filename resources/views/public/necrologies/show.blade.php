<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $necrologie->nom_defunt }} | Nécrologies E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #1a1a2e; font-family: 'Inter', sans-serif; margin: 0; color: #fff; }
        .nec-header { background: #0d0d1a; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,.08); }
        .nec-header__logo img { height: 32px; filter: brightness(0) invert(1); }
        .nec-header nav a { color: #aaa; text-decoration: none; font-size: .9rem; margin-left: 20px; }

        .nec-container { max-width: 760px; margin: 40px auto; padding: 0 16px; }
        .back-link { color: #888; text-decoration: none; font-size: .9rem; margin-bottom: 24px; display: inline-block; }
        .back-link:hover { color: #fff; }

        .nec-card { background: #0d0d1a; border: 1px solid rgba(255,255,255,.08); border-radius: 16px; overflow: hidden; }

        /* Photo / Video */
        .nec-media { position: relative; }
        .nec-photo { width: 100%; max-height: 420px; object-fit: cover; object-position: top center; display: block; }
        .nec-video { width: 100%; max-height: 420px; display: block; }

        .nec-body { padding: 36px; }
        .nec-name { font-size: 2rem; font-weight: 700; margin-bottom: 8px; }
        .nec-dates { font-size: .95rem; color: #888; margin-bottom: 24px; display: flex; gap: 16px; align-items: center; }
        .nec-dates .sep { color: #555; }
        .nec-divider { border: none; border-top: 1px solid rgba(255,255,255,.08); margin: 24px 0; }
        .nec-message { font-size: 1rem; color: #ccc; line-height: 1.8; white-space: pre-wrap; font-style: italic; }
        .nec-footer { margin-top: 32px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,.06); display: flex; justify-content: space-between; align-items: center; font-size: .82rem; color: #555; }
        .candle-row { text-align: center; font-size: 1.5rem; margin-bottom: 20px; letter-spacing: .3em; }
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
    </nav>
</header>

<div class="nec-container">
    <a href="{{ route('necrologies.index') }}" class="back-link">← Retour aux nécrologies</a>

    <div class="nec-card">
        @if ($necrologie->video)
            <div class="nec-media">
                <video class="nec-video" controls>
                    <source src="{{ asset($necrologie->video) }}">
                </video>
            </div>
        @elseif ($necrologie->photo)
            <div class="nec-media">
                <img class="nec-photo" src="{{ asset($necrologie->photo) }}" alt="{{ $necrologie->nom_defunt }}">
            </div>
        @endif

        <div class="nec-body">
            <div class="candle-row">🕯️ 🕊️ 🕯️</div>

            <div class="nec-name">{{ $necrologie->nom_defunt }}</div>

            <div class="nec-dates">
                @if ($necrologie->date_naissance)
                    <span>Né(e) le {{ $necrologie->date_naissance->format('d/m/Y') }}</span>
                    <span class="sep">•</span>
                @endif
                <span>Décédé(e) le {{ $necrologie->date_deces->format('d/m/Y') }}</span>
                @if ($necrologie->date_naissance)
                    @php $age = $necrologie->date_naissance->diffInYears($necrologie->date_deces); @endphp
                    <span class="sep">•</span>
                    <span>{{ $age }} ans</span>
                @endif
            </div>

            @if ($necrologie->message)
                <hr class="nec-divider">
                <div class="nec-message">{{ $necrologie->message }}</div>
            @endif

            <div class="nec-footer">
                <span>Publié par <strong>{{ $necrologie->advertiser->company_name ?? $necrologie->advertiser->name }}</strong></span>
                <span>{{ $necrologie->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
