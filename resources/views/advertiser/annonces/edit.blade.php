<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'annonce | E-Benin</title>
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
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 14px; border: 1px solid #dde1e9; border-radius: 8px; font-size: .95rem; box-sizing: border-box; font-family: inherit; }
        .form-group textarea { min-height: 160px; resize: vertical; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #003f7f; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 8px; font-size: .92rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #003f7f; color: #fff; }
        .btn-outline { background: #fff; border: 1px solid #dde1e9; color: #333; }
        .error-list { background: #fdecea; color: #b71c1c; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .88rem; }
        .hint { font-size: .78rem; color: #999; margin-top: 4px; }
        .current-images { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
        .current-images img { height: 80px; width: 80px; object-fit: cover; border-radius: 6px; }
    </style>
</head>
<body>
<div class="adv-topbar">
    <div class="adv-topbar__logo"><img src="{{ asset('images/ebenins.png') }}" alt="E-Benin"></div>
    <a href="{{ route('advertiser.dashboard') }}">← Retour au dashboard</a>
</div>

<div class="form-wrap">
    <div class="form-card">
        <h1>Modifier l'annonce</h1>

        @if ($errors->any())
            <div class="error-list">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.annonces.update', $annonce) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Titre *</label>
                <input name="title" type="text" value="{{ old('title', $annonce->title) }}" required>
            </div>

            <div class="form-group">
                <label>Catégorie *</label>
                <select name="category" required>
                    <option value="emploi" {{ old('category', $annonce->category) === 'emploi' ? 'selected' : '' }}>Emploi / Recrutement</option>
                    <option value="immobilier" {{ old('category', $annonce->category) === 'immobilier' ? 'selected' : '' }}>Immobilier</option>
                    <option value="vente_services" {{ old('category', $annonce->category) === 'vente_services' ? 'selected' : '' }}>Vente / Services</option>
                    <option value="evenements" {{ old('category', $annonce->category) === 'evenements' ? 'selected' : '' }}>Évènements</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" required>{{ old('description', $annonce->description) }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <input name="price" type="number" min="0" value="{{ old('price', $annonce->price) }}">
                </div>
                <div class="form-group">
                    <label>Localisation</label>
                    <input name="location" type="text" value="{{ old('location', $annonce->location) }}">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Téléphone</label>
                    <input name="contact_phone" type="text" value="{{ old('contact_phone', $annonce->contact_phone) }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="contact_email" type="email" value="{{ old('contact_email', $annonce->contact_email) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Ajouter des photos</label>
                @if ($annonce->images)
                    <div class="current-images">
                        @foreach ($annonce->images as $img)
                            <img src="{{ asset($img) }}" alt="">
                        @endforeach
                    </div>
                    <p class="hint">Photos actuelles ci-dessus. Les nouvelles photos seront ajoutées.</p>
                @endif
                <input name="images[]" type="file" accept="image/*" multiple>
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
