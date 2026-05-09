@extends('reader.layouts.app')
@section('title', 'Accueil')

@push('head')
<style>
.ra-featured__track { scroll-behavior: smooth; }
</style>
@endpush

@section('content')
<div class="ra-page">

    {{-- ── Featured slider ── --}}
    @if($featured->isNotEmpty())
    <div class="ra-featured" id="featuredSlider">
        <div class="ra-featured__track" id="featuredTrack">
            @foreach($featured as $f)
            @php
                $org = $f->user?->organization;
                $subdomain = $org?->subdomain;
                $baseDomain = str_contains(request()->getHost(),'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
                $imgUrl = $f->image ? asset($f->image) : ($f->image_url ?? null);
            @endphp
            <a href="{{ route('reader.article', $f->id) }}" class="ra-featured__slide">
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $f->libelle }}" class="ra-featured__img" loading="lazy">
                @else
                    <div class="ra-featured__img" style="background:linear-gradient(135deg,#003f7f,#0057b3)"></div>
                @endif
                <div class="ra-featured__overlay"></div>
                <div class="ra-featured__body">
                    @if($f->rubriques->isNotEmpty())
                        <div class="ra-featured__badge">{{ $f->rubriques->first()->name }}</div>
                    @endif
                    <div class="ra-featured__title">{{ $f->libelle }}</div>
                </div>
            </a>
            @endforeach
        </div>
        @if($featured->count() > 1)
        <div class="ra-featured__dots" id="featuredDots">
            @foreach($featured as $i => $f)
                <div class="ra-featured__dot {{ $i === 0 ? 'active' : '' }}" data-idx="{{ $i }}"></div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    {{-- ── Category pills ── --}}
    <div class="ra-cats">
        <a href="{{ route('reader.home') }}" class="ra-cat-pill {{ !$rubriqueId ? 'active' : '' }}">Tout</a>
        @foreach($categories as $cat)
            <a href="{{ route('reader.home') }}?cat={{ $cat->id }}"
               class="ra-cat-pill {{ $rubriqueId == $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    {{-- ── Feed ── --}}
    @if($posts->isNotEmpty())
        {{-- First post — big card --}}
        @php $first = $posts->first(); $rest = $posts->slice(1); @endphp
        @php
            $firstImg = $first->image ? asset($first->image) : ($first->image_url ?? null);
        @endphp
        <a href="{{ route('reader.article', $first->id) }}" class="ra-card-big">
            @if($firstImg)
                <img src="{{ $firstImg }}" alt="{{ $first->libelle }}" class="ra-card-big__img" loading="lazy">
            @else
                <div class="ra-card-big__img-ph">📰</div>
            @endif
            <div class="ra-card-big__body">
                @if($first->rubriques->isNotEmpty())
                    <div class="ra-card-big__cat">{{ $first->rubriques->first()->name }}</div>
                @endif
                <div class="ra-card-big__title">{{ $first->libelle }}</div>
                <div class="ra-card-big__meta">
                    <span>{{ $first->user?->organization?->organization_name ?? 'E-Benin' }}</span>
                    <span>·</span>
                    <span>{{ $first->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </a>

        {{-- Rest — row cards --}}
        <div class="ra-feed">
            @foreach($rest as $post)
            @php
                $img = $post->image ? asset($post->image) : ($post->image_url ?? null);
            @endphp
            <a href="{{ route('reader.article', $post->id) }}" class="ra-card-row">
                @if($img)
                    <img src="{{ $img }}" alt="{{ $post->libelle }}" class="ra-card-row__img" loading="lazy">
                @else
                    <div class="ra-card-row__img-ph">📰</div>
                @endif
                <div class="ra-card-row__body">
                    @if($post->rubriques->isNotEmpty())
                        <div class="ra-card-row__cat">{{ $post->rubriques->first()->name }}</div>
                    @endif
                    <div class="ra-card-row__title">{{ $post->libelle }}</div>
                    <div class="ra-card-row__meta">
                        {{ $post->user?->organization?->organization_name ?? 'E-Benin' }} · {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasMorePages())
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
(function() {
    const track = document.getElementById('featuredTrack');
    const dots  = document.querySelectorAll('#featuredDots .ra-featured__dot');
    if (!track || !dots.length) return;

    let current = 0;
    const total = dots.length;

    function goTo(idx) {
        current = (idx + total) % total;
        track.scrollTo({ left: current * track.offsetWidth, behavior: 'smooth' });
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    // Auto-slide every 4s
    let timer = setInterval(() => goTo(current + 1), 4000);
    track.addEventListener('scroll', () => {
        clearInterval(timer);
        const idx = Math.round(track.scrollLeft / track.offsetWidth);
        dots.forEach((d, i) => d.classList.toggle('active', i === idx));
        current = idx;
        timer = setInterval(() => goTo(current + 1), 4000);
    }, { passive: true });

    dots.forEach((d, i) => d.addEventListener('click', () => goTo(i)));
})();
</script>
@endpush
