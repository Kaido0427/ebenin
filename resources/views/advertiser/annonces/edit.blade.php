@extends('advertiser.layouts.app')

@section('title', "Modifier l'annonce")

@push('head')
<style>
    .adv-form-wrap { max-width: 740px; margin: 32px auto; padding: 0 16px; }
    .adv-form-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 32px; }
    .adv-form-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 24px; }
    .hint { font-size: .78rem; color: var(--muted); margin-top: 4px; }
    .current-images { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
    .current-images img { height: 80px; width: 80px; object-fit: cover; border-radius: var(--radius); }
</style>
@endpush

@section('content')
<div class="adv-form-wrap">
    <div class="adv-form-card">
        <h1>Modifier l'annonce</h1>

        @if ($errors->any())
            <div class="alert alert--error">
                @foreach ($errors->all() as $error)<p style="margin:2px 0;">{{ $error }}</p>@endforeach
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

            <div class="form-row">
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <input name="price" type="number" min="0" value="{{ old('price', $annonce->price) }}">
                </div>
                <div class="form-group">
                    <label>Localisation</label>
                    <input name="location" type="text" value="{{ old('location', $annonce->location) }}">
                </div>
            </div>

            <div class="form-row">
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
                @if ($annonce->images && count($annonce->images) > 0)
                    <div class="current-images">
                        @foreach ($annonce->images as $img)
                            <img src="{{ asset($img) }}" alt="">
                        @endforeach
                    </div>
                    <p class="hint">Photos actuelles ci-dessus. Les nouvelles seront ajoutées.</p>
                @endif
                <input name="images[]" type="file" accept="image/*" multiple>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn--primary">Enregistrer</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn--outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
