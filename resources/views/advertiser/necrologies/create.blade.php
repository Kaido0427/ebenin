@extends('advertiser.layouts.app')

@section('title', 'Nouvelle notice de décès')

@push('head')
<style>
    .adv-form-wrap { max-width: 740px; margin: 32px auto; padding: 0 16px; }
    .adv-form-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 32px; }
    .adv-form-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
    .adv-form-card .subtitle { font-size: .9rem; color: var(--muted); margin-bottom: 24px; }
    .hint { font-size: .78rem; color: var(--muted); margin-top: 4px; }
    .media-note { background: var(--bg); border-radius: var(--radius); padding: 10px 14px; font-size: .85rem; color: var(--mid); margin-bottom: 14px; border: 1px solid var(--border); }
    .separator { border: none; border-top: 1px solid var(--border); margin: 22px 0; }
</style>
@endpush

@section('content')
<div class="adv-form-wrap">
    <div class="adv-form-card">
        <h1>Publier une notice de décès</h1>
        <p class="subtitle">Rendez hommage à un proche en publiant une notice sur E-Benin.</p>

        @if ($errors->any())
            <div class="alert alert--error">
                @foreach ($errors->all() as $error)<p style="margin:2px 0;">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('advertiser.necrologies.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nom complet du défunt *</label>
                <input name="nom_defunt" type="text" value="{{ old('nom_defunt') }}" required placeholder="Prénom et Nom">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date de naissance</label>
                    <input name="date_naissance" type="date" value="{{ old('date_naissance') }}">
                </div>
                <div class="form-group">
                    <label>Date de décès *</label>
                    <input name="date_deces" type="date" value="{{ old('date_deces') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Message / Hommage</label>
                <textarea name="message" placeholder="Partagez un hommage, un message de la famille…">{{ old('message') }}</textarea>
            </div>

            <hr class="separator">

            <div class="media-note">
                📷 Ajoutez une photo <strong>ou</strong> une vidéo hommage — l'un ou l'autre suffit.
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Photo du défunt</label>
                    <input name="photo" type="file" accept="image/*">
                    <p class="hint">JPEG, PNG ou WEBP — 3 Mo max</p>
                </div>
                <div class="form-group">
                    <label>Vidéo hommage</label>
                    <input name="video" type="file" accept="video/*">
                    <p class="hint">MP4 ou WEBM — 50 Mo max</p>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:8px;">
                <button type="submit" class="btn btn--primary">Publier la notice</button>
                <a href="{{ route('advertiser.dashboard') }}" class="btn btn--outline">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
