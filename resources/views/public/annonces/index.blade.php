@extends('public.layouts.app')

@section('title', 'Petites annonces | E-Benin')
@section('meta_description', "Petites annonces au Bénin : emploi, immobilier, véhicules, services et bien plus sur E-Benin.")

@push('head')
<style>
.cat-grid-section { background: #fff; border-bottom: 1px solid var(--border); padding: 20px 0 0; }
.cat-grid-toggle { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; cursor: pointer; user-select: none; }
.cat-grid-toggle h2 { font-size: .95rem; font-weight: 700; color: var(--dark); margin: 0; }
.cat-grid-toggle span { font-size: .8rem; color: var(--primary); font-weight: 600; }
.cat-grid-all { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding-bottom: 16px; }
@media(min-width:640px){ .cat-grid-all { grid-template-columns: repeat(6, 1fr); } }
@media(min-width:960px){ .cat-grid-all { grid-template-columns: repeat(8, 1fr); } }
.cat-icon-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 4px; padding: 10px 6px; border-radius: 10px; border: 1.5px solid var(--border); background: #fafbff; text-decoration: none; color: var(--dark); font-size: .72rem; text-align: center; line-height: 1.25; transition: all .18s; cursor: pointer; }
.cat-icon-btn .ci-icon { font-size: 1.5rem; line-height: 1; }
.cat-icon-btn:hover { border-color: var(--primary); background: #f0f0fa; color: var(--primary); }
.cat-icon-btn.active { border-color: var(--primary); background: var(--primary); color: #fff; }
.cat-all-btn { border-style: dashed; background: #fff; font-weight: 700; color: var(--primary); }
.cat-group-label { font-size: .7rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .04em; grid-column: 1 / -1; margin-top: 6px; padding-bottom: 2px; border-bottom: 1px solid var(--border); }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">Petites annonces</h1>
        <p class="page-hero__text">Trouvez ce dont vous avez besoin au Bénin</p>
    </div>
</div>

<div class="cat-grid-section">
    <div class="container">
        <div class="cat-grid-toggle" onclick="toggleCatGrid()">
            <h2>Parcourir par catégorie</h2>
            <span id="cat-grid-toggle-label">Masquer ▲</span>
        </div>
        <div class="cat-grid-all" id="cat-grid-all">
            <a href="{{ route('annonces.index') }}" class="cat-icon-btn cat-all-btn {{ !$category ? 'active' : '' }}">
                <span class="ci-icon">🔍</span>
                <span>Toutes</span>
            </a>
            @php
                $icons  = \App\Models\Annonce::ICONS;
                $groups = \App\Models\Annonce::CATEGORY_GROUPS;
                $cats   = \App\Models\Annonce::CATEGORIES;
            @endphp
            @foreach ($groups as $groupName => $keys)
                <div class="cat-group-label">{{ $groupName }}</div>
                @foreach ($keys as $key)
                    @if (isset($cats[$key]))
                    <a href="{{ route('annonces.index', ['category' => $key]) }}"
                       class="cat-icon-btn {{ $category === $key ? 'active' : '' }}">
                        <span class="ci-icon">{{ $icons[$key] ?? '📋' }}</span>
                        <span>{{ $cats[$key] }}</span>
                    </a>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>
</div>

<main style="padding: 28px 0 56px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                @if ($category)
                    {{ \App\Models\Annonce::ICONS[$category] ?? '' }} {{ $categories[$category] ?? 'Annonces' }}
                @else
                    Toutes les annonces
                @endif
                @if (!$annonces->isEmpty())
                    <span style="font-size:.85rem;font-weight:400;color:var(--muted);margin-left:8px;">{{ $annonces->total() }} résultat(s)</span>
                @endif
            </h2>
            <a href="{{ route('advertiser.register') }}" class="section-more">+ Publier une annonce</a>
        </div>

        @if ($annonces->isEmpty())
            <div style="text-align:center;padding:60px 0;color:var(--muted);">
                <div style="font-size:2.8rem;margin-bottom:12px;">{{ $category ? (\App\Models\Annonce::ICONS[$category] ?? '📋') : '📋' }}</div>
                <p>Aucune annonce disponible{{ $category ? ' dans cette catégorie' : '' }} pour le moment.</p>
                <a href="{{ route('advertiser.register') }}" class="btn btn--primary" style="margin-top:16px;">Publier la première annonce</a>
            </div>
        @else
            <div class="news-grid" style="margin-top:20px;">
                @foreach ($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce) }}" class="card">
                    <div class="card__img-wrap">
                        @if ($annonce->images && count($annonce->images) > 0)
                            <img class="card__img" src="{{ asset($annonce->images[0]) }}" alt="{{ $annonce->title }}">
                        @else
                            <div class="card__img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--border);">
                                {{ \App\Models\Annonce::ICONS[$annonce->category] ?? '📋' }}
                            </div>
                        @endif
                        <span class="card__cat">{{ $annonce->category_label }}</span>
                    </div>
                    <div class="card__body">
                        <h3 class="card__title">{{ $annonce->title }}</h3>
                        <p class="card__excerpt">{{ Str::limit($annonce->description, 120) }}</p>
                    </div>
                    <div class="card__footer">
                        <div class="card__meta">
                            @if ($annonce->price)
                                <span style="font-weight:700;color:var(--primary);">{{ number_format($annonce->price, 0, ',', ' ') }} FCFA</span>
                            @else
                                <span style="color:var(--muted);">Prix à débattre</span>
                            @endif
                            @if ($annonce->location)
                                <span>📍 {{ $annonce->location }}</span>
                            @endif
                        </div>
                        <div class="card__author">{{ $annonce->created_at->diffForHumans() }}</div>
                    </div>
                </a>
                @endforeach
            </div>

            <div style="margin-top:28px;display:flex;justify-content:center;">
                {{ $annonces->links() }}
            </div>
        @endif
    </div>
</main>

<script>
function toggleCatGrid() {
    var grid = document.getElementById('cat-grid-all');
    var label = document.getElementById('cat-grid-toggle-label');
    if (grid.style.display === 'none') {
        grid.style.display = 'grid';
        label.textContent = 'Masquer ▲';
    } else {
        grid.style.display = 'none';
        label.textContent = 'Afficher ▼';
    }
}
</script>

@endsection
