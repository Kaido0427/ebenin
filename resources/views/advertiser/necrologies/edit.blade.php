<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la notice | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #f4f6fb; font-family: 'Inter', sans-serif; }
        .adv-topbar { background: #003f7f; color: #fff; padding: 0 24px; height: 60px; display: flex; align-items: center; justify-content: space-between; }
        .adv-topbar__logo img { height: 32px; filter: brightness(0) invert(1); }
        .adv-topbar a { color: #a8c7f0; text-decoration: none; font-size: .9rem; }
        .form-wrap { max-width: 700px; margin: 40px auto; padding: 0 16px; }
        .form-card { background: #fff; border-radius: 12px; padding: 36px; box-shadow: 0 1px 8px rgba(0,0,0,.07); }
        .form-card h1 { font-size: 1.4rem; font-weight: 700; margin-bottom: 24px; color: #0d1b2a; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: .88rem; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px 14px; border: 1px solid #dde1e9; border-radius: 8px; font-size: .95rem; box-sizing: border-box; font-family: inherit; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #003f7f; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 8px; font-size: .92rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #37474f; color: #fff; }
        .btn-outline { background: #fff; border: 1px solid #dde1e9; color: #333; }
        .error-list { background: #fdecea; color: #b71c1c; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .88rem; }
        .hint { font-size: .78rem; color: #999; margin-top: 4px; }
        .current-media { margin-top: 8px; }
        .current-media img { height: 100px; border-radius: 6px; object-fit: cover; }
    </style>
</head>
<body>
<div class="adv-topbar">
    <div class="adv-topbar__logo"><img src="{{ asset('images/ebenins.png') }}" alt="E-Benin"></div>
    <a href="{{ route('advertiser.dashboard') }}">← Retour au dashboard</a>
</div>

<div class="form-wrap">
    <div class="form-card">
        <h1>🕊️ Modifier la notice de décès</h1>

        @if ($errors->any())
            <div class="error-list">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.necrologies.update', $necrologie) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Nom complet du défunt *</label>
                <input name="nom_defunt" type="text" value="{{ old('nom_defunt', $necrologie->nom_defunt) }}" required>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input name="date_naissance" type="date" value="{{ old('date_naissance', $necrologie->date_naissance?->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label>Date de décès *</label>
                    <input name="date_deces" type="date" value="{{ old('date_deces', $necrologie->date_deces->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Message / Hommage</label>
                <textarea name="message">{{ old('message', $necrologie->message) }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Photo</label>
                    @if ($necrologie->photo)
                        <div class="current-media">
                            <img src="{{ asset($necrologie->photo) }}" alt="">
                            <p class="hint">Photo actuelle. Sélectionnez une nouvelle pour remplacer.</p>
                        </div>
                    @endif
                    <input name="photo" type="file" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Vidéo hommage</label>
                    @if ($necrologie->video)
                        <p class="hint">Une vidéo est déjà associée. Sélectionnez-en une nouvelle pour remplacer.</p>
                    @endif
                    <input name="video" type="file" accept="video/*">
                    <p class="hint">MP4 ou WEBM — 50 Mo max</p>
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
