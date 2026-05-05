@extends('advertiser.layouts.app')

@section('title', 'Modifier la notice')

@push('head')
<style>
    .adv-form-wrap { max-width: 740px; margin: 32px auto; padding: 0 16px; }
    .adv-form-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 32px; }
    .adv-form-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 24px; }
    .hint { font-size: .78rem; color: var(--muted); margin-top: 4px; }
    .current-media img { height: 100px; border-radius: var(--radius); object-fit: cover; margin-top: 8px; }
</style>
@endpush

@section('content')
<div class="adv-form-wrap">
    <div class="adv-form-card">
        <h1>Modifier la notice de décès</h1>

        @if ($errors->any())
            <div class="alert alert--error">
                @foreach ($errors->all() as $error)<p style="margin:2px 0;">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.necrologies.update', $necrologie) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Nom complet du défunt *</label>
                <input name="nom_defunt" type="text" value="{{ old('nom_defunt', $necrologie->nom_defunt) }}" required>
            </div>

            <div class="form-row">
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

            <div class="form-row">
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

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn--primary">Enregistrer</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn--outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
