@extends('advertiser.layouts.app')

@section('title', 'Nouvelle annonce')

@push('head')
<style>
    .adv-form-wrap { max-width: 740px; margin: 32px auto; padding: 0 16px; }
    .adv-form-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 32px; }
    .adv-form-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--dark); margin-bottom: 24px; }
    .hint { font-size: .78rem; color: var(--muted); margin-top: 4px; }
    /* Category picker */
    .cat-picker { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 8px; }
    @media(min-width:520px){ .cat-picker { grid-template-columns: repeat(4, 1fr); } }
    .cat-pick-btn { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 10px 6px; border-radius: 10px; border: 1.5px solid var(--border); background: #fafbff; cursor: pointer; font-size: .72rem; text-align: center; line-height: 1.3; color: var(--dark); transition: all .18s; }
    .cat-pick-btn .cp-icon { font-size: 1.4rem; line-height: 1; }
    .cat-pick-btn:hover { border-color: var(--primary); background: #f0f0fa; }
    .cat-pick-btn.selected { border-color: var(--primary); background: var(--primary); color: #fff; }
    .cat-group-hdr { font-size: .68rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; grid-column: 1 / -1; margin-top: 8px; padding-bottom: 3px; border-bottom: 1px solid var(--border); }
    .cat-selected-display { margin-top: 8px; font-size: .82rem; font-weight: 600; color: var(--primary); min-height: 1.2em; }
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
                <input type="hidden" name="category" id="cat-input" value="{{ old('category') }}" required>
                @php
                    $icons  = \App\Models\Annonce::ICONS;
                    $groups = \App\Models\Annonce::CATEGORY_GROUPS;
                    $cats   = \App\Models\Annonce::CATEGORIES;
                    $oldCat = old('category');
                @endphp
                <div class="cat-picker" id="cat-picker">
                    @foreach ($groups as $groupName => $keys)
                        <div class="cat-group-hdr">{{ $groupName }}</div>
                        @foreach ($keys as $key)
                            @if (isset($cats[$key]))
                            <div class="cat-pick-btn {{ $oldCat === $key ? 'selected' : '' }}"
                                 data-value="{{ $key }}"
                                 onclick="selectCat(this)">
                                <span class="cp-icon">{{ $icons[$key] ?? '📋' }}</span>
                                <span>{{ $cats[$key] }}</span>
                            </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <div class="cat-selected-display" id="cat-selected-display">
                    {{ $oldCat ? ($cats[$oldCat] ?? '') : 'Aucune catégorie sélectionnée' }}
                </div>
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
                <label>Date d'expiration de l'annonce</label>
                <input name="expires_at" type="date" value="{{ old('expires_at') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <p class="hint">Laisser vide si l'annonce n'a pas de date limite</p>
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
@push('scripts')
<script>
function selectCat(el) {
    document.querySelectorAll('#cat-picker .cat-pick-btn').forEach(function(b){ b.classList.remove('selected'); });
    el.classList.add('selected');
    var val = el.getAttribute('data-value');
    document.getElementById('cat-input').value = val;
    document.getElementById('cat-selected-display').textContent = el.querySelector('span:last-child').textContent;
}
</script>
@endpush
@endsection
