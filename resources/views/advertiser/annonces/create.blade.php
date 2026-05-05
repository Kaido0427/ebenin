<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle annonce | E-Benin</title>
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
        .btn-primary:hover { background: #002d5c; }
        .btn-outline { background: #fff; border: 1px solid #dde1e9; color: #333; }
        .error-list { background: #fdecea; color: #b71c1c; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .88rem; }
        .hint { font-size: .78rem; color: #999; margin-top: 4px; }
    </style>
</head>
<body>
<div class="adv-topbar">
    <div class="adv-topbar__logo">
        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
    </div>
    <a href="{{ route('advertiser.dashboard') }}">← Retour au dashboard</a>
</div>

<div class="form-wrap">
    <div class="form-card">
        <h1>Publier une annonce</h1>

        @if ($errors->any())
            <div class="error-list">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.annonces.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Titre de l'annonce *</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required placeholder="Ex: Recherche développeur web, Villa à vendre...">
            </div>

            <div class="form-group">
                <label for="category">Catégorie *</label>
                <select id="category" name="category" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <option value="emploi" {{ old('category') === 'emploi' ? 'selected' : '' }}>Emploi / Recrutement</option>
                    <option value="immobilier" {{ old('category') === 'immobilier' ? 'selected' : '' }}>Immobilier</option>
                    <option value="vente_services" {{ old('category') === 'vente_services' ? 'selected' : '' }}>Vente / Services</option>
                    <option value="evenements" {{ old('category') === 'evenements' ? 'selected' : '' }}>Évènements</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required placeholder="Décrivez votre annonce en détail...">{{ old('description') }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="price">Prix (FCFA)</label>
                    <input id="price" name="price" type="number" min="0" value="{{ old('price') }}" placeholder="Laisser vide si non applicable">
                </div>
                <div class="form-group">
                    <label for="location">Localisation</label>
                    <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="Ex: Cotonou, Porto-Novo...">
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="contact_phone">Téléphone de contact</label>
                    <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone') }}">
                </div>
                <div class="form-group">
                    <label for="contact_email">Email de contact</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="images">Photos (max 5)</label>
                <input id="images" name="images[]" type="file" accept="image/*" multiple>
                <p class="hint">JPEG, PNG ou WEBP — 3 Mo max par photo</p>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Publier l'annonce</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
