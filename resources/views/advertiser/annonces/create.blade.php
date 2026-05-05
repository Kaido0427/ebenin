@extends('advertiser.layouts.app')

@section('title', 'Nouvelle annonce')

@push('head')
<style>
    .adv-form-wrap { max-width: 740px; margin: 32px auto; padding: 0 16px; }
    .adv-form-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 32px; }
    .adv-form-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 24px; }
    .hint { font-size: .78rem; color: var(--muted); margin-top: 4px; }
</style>
@endpush

@section('content')
<div class="adv-form-wrap">
    <div class="adv-form-card">
        <h1>Publier une annonce</h1>

        @if ($errors->any())
            <div class="alert alert--error">
                @foreach ($errors->all() as $error)<p style="margin:2px 0;">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.annonces.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Titre de l'annonce *</label>
                <input name="title" type="text" value="{{ old('title') }}" required placeholder="Ex : Recherche développeur web, Villa à vendre…">
            </div>

            <div class="form-group">
                <label>Catégorie *</label>
                <select name="category" required>
                    <option value="">-- Choisir une catégorie --</option>
                    <option value="emploi" {{ old('category') === 'emploi' ? 'selected' : '' }}>Emploi / Recrutement</option>
                    <option value="immobilier" {{ old('category') === 'immobilier' ? 'selected' : '' }}>Immobilier</option>
                    <option value="vente_services" {{ old('category') === 'vente_services' ? 'selected' : '' }}>Vente / Services</option>
                    <option value="evenements" {{ old('category') === 'evenements' ? 'selected' : '' }}>Évènements</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" required placeholder="Décrivez votre annonce en détail…">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <input name="price" type="number" min="0" value="{{ old('price') }}" placeholder="Laisser vide si non applicable">
                </div>
                <div class="form-group">
                    <label>Localisation</label>
                    <input name="location" type="text" value="{{ old('location') }}" placeholder="Ex : Cotonou, Porto-Novo…">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Téléphone de contact</label>
                    <input name="contact_phone" type="text" value="{{ old('contact_phone') }}">
                </div>
                <div class="form-group">
                    <label>Email de contact</label>
                    <input name="contact_email" type="email" value="{{ old('contact_email') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Photos (max 5)</label>
                <input name="images[]" type="file" accept="image/*" multiple>
                <p class="hint">JPEG, PNG ou WEBP — 3 Mo max par photo</p>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn--primary">Publier l'annonce</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn--outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
