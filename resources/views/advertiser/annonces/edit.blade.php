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
                @php
                    $icons   = \App\Models\Annonce::ICONS;
                    $groups  = \App\Models\Annonce::CATEGORY_GROUPS;
                    $cats    = \App\Models\Annonce::CATEGORIES;
                    $current = old('category', $annonce->category);
                @endphp
                <input type="hidden" name="category" id="cat-input" value="{{ $current }}" required>
                <div class="cat-picker" id="cat-picker">
                    @foreach ($groups as $groupName => $keys)
                        <div class="cat-group-hdr">{{ $groupName }}</div>
                        @foreach ($keys as $key)
                            @if (isset($cats[$key]))
                            <div class="cat-pick-btn {{ $current === $key ? 'selected' : '' }}"
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
                    {{ $current ? ($cats[$current] ?? '') : 'Aucune catégorie sélectionnée' }}
                </div>
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
                <label>Date d'expiration de l'annonce</label>
                <input name="expires_at" type="date" value="{{ old('expires_at', $annonce->expires_at?->format('Y-m-d')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <p class="hint">Laisser vide si l'annonce n'a pas de date limite</p>
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
