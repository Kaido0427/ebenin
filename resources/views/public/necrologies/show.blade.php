<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $necrologie->nom_defunt }} | Nécrologies E-Benin</title>
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
        .pub-header nav a { color: rgba(255,255,255,.5); text-decoration: none; font-size: .88rem; margin-left: 20px; }
        .pub-header nav a:hover { color: #c9a84c; }

        .nec-container { max-width: 760px; margin: 36px auto; padding: 0 16px; }
        .back-link { color: #666; text-decoration: none; font-size: .88rem; margin-bottom: 22px; display: inline-block; }
        .back-link:hover { color: #ddd; }

        .nec-card {
            background: #0d0d1a;
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 14px;
            overflow: hidden;
        }

        .nec-photo { width: 100%; max-height: 420px; object-fit: cover; object-position: top center; display: block; }
        .nec-video { width: 100%; max-height: 420px; display: block; }

        .nec-body { padding: 32px 36px; }
        .candle-row { text-align: center; font-size: 1.4rem; margin-bottom: 18px; letter-spacing: .3em; }
        .nec-name { font-size: 1.9rem; font-weight: 700; color: #eee; margin-bottom: 7px; }
        .nec-dates { font-size: .93rem; color: #777; margin-bottom: 22px; display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
        .nec-dates .sep { color: #444; }
        .nec-divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 22px 0; }
        .nec-message { font-size: .97rem; color: #bbb; line-height: 1.85; white-space: pre-wrap; font-style: italic; }
        .nec-footer { margin-top: 28px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,.05); display: flex; justify-content: space-between; align-items: center; font-size: .8rem; color: #555; }
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

<div class="nec-container">
    <a href="{{ route('necrologies.index') }}" class="back-link">← Retour aux nécrologies</a>

    <div class="nec-card">
        @if ($necrologie->video)
            <video class="nec-video" controls>
                <source src="{{ asset($necrologie->video) }}">
            </video>
        @elseif ($necrologie->photo)
            <img class="nec-photo" src="{{ asset($necrologie->photo) }}" alt="{{ $necrologie->nom_defunt }}">
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
