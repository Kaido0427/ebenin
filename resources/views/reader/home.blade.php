@extends('reader.layouts.app')
@section('title', 'Accueil')

{{-- Category tabs injected into the fixed slot in the layout --}}
@push('tabs')
<div class="ra-tabs" id="ra-tabs">
    <a href="/reader" class="ra-tab {{ !$rubriqueId ? 'active' : '' }}">Tout</a>
    @foreach($categories as $cat)
        <a href="/reader?cat={{ $cat->id }}"
           class="ra-tab {{ $rubriqueId == $cat->id ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>
@endpush

@section('content')
<div class="ra-page">

    {{-- ── Hero : featured en priorité, sinon premier article du feed ── --}}
    @php
        $hero    = $featured->isNotEmpty() ? $featured->first() : $posts->first();
        $heroImg = $hero ? ($hero->image ? asset($hero->image) : ($hero->image_url ?? null)) : null;
        $feedPosts = $featured->isNotEmpty() ? $posts : $posts->slice(1);
    @endphp
    @if($hero)
    <a href="/reader/article/{{ $hero->id }}" class="ra-hero">
        @if($heroImg)
            <img src="{{ $heroImg }}" alt="{{ $hero->libelle }}" class="ra-hero__img" loading="eager">
        @else
            <div class="ra-hero__img" style="background:linear-gradient(135deg,#003f7f,#0057b3)"></div>
        @endif
        <div class="ra-hero__overlay"></div>
        <div class="ra-hero__body">
            @if($hero->rubriques->isNotEmpty())
                <div class="ra-hero__badge">{{ $hero->rubriques->first()->name }}</div>
            @endif
            <div class="ra-hero__title">{{ $hero->libelle }}</div>
            <div class="ra-hero__time">{{ $hero->created_at->diffForHumans() }}</div>
        </div>
    </a>
    @endif

    {{-- ── Feed ── --}}
    @if($feedPosts->isNotEmpty())

        <div class="ra-section-head">
            <div class="ra-section-title">À la une</div>
            <a href="/reader" class="ra-section-more">Voir tout</a>
        </div>

        <div class="ra-feed">
            @foreach($feedPosts as $i => $post)
            @php
                $img    = $post->image ? asset($post->image) : ($post->image_url ?? null);
                $catIdx = $post->rubriques->isNotEmpty() ? ($post->rubriques->first()->id % 12) : 0;
            @endphp
            <a href="/reader/article/{{ $post->id }}" class="ra-card-row">
                @if($img)
                    <img src="{{ $img }}" alt="{{ $post->libelle }}" class="ra-card-row__thumb" loading="lazy">
                @else
                    <div class="ra-card-row__thumb-ph">📰</div>
                @endif
                <div class="ra-card-row__body">
                    @if($post->rubriques->isNotEmpty())
                        <div class="ra-card-row__cat cat-color-{{ $catIdx }}">{{ $post->rubriques->first()->name }}</div>
                    @endif
                    <div class="ra-card-row__title">{{ $post->libelle }}</div>
                    <div class="ra-card-row__meta">
                        {{ $post->user?->organization?->organization_name ?? 'E-Benin' }} · {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @if($feedPosts instanceof \Illuminate\Pagination\LengthAwarePaginator && $feedPosts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" class="ra-load-more">Voir plus d'articles</a>
        @endif

    @else
        <div class="ra-empty">
            <div class="ra-empty__icon">📭</div>
            <div class="ra-empty__title">Aucun article</div>
            <div class="ra-empty__text">Aucun article pour cette catégorie.</div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function () {
    // Scroll active tab into view on load
    var activeTab = document.querySelector('#ra-tabs .ra-tab.active');
    if (activeTab) activeTab.scrollIntoView({ inline: 'center', behavior: 'instant' });
})();
</script>
@endpush
