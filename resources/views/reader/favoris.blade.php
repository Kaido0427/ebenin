@extends('reader.layouts.app')
@section('title', 'Mes favoris')
@section('body_class', 'body--no-tabs')

@section('content')
<div class="ra-page">

    <div class="ra-section-head" style="padding-top:18px;">
        <div class="ra-section-title" style="font-size:1rem;">Mes articles sauvegardés</div>
    </div>

    @if($favorites->isNotEmpty())
        <div class="ra-feed">
            @foreach($favorites as $fav)
            @php
                $post   = $fav->post;
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
                <div style="flex-shrink:0;padding:4px;color:var(--accent);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#e8191e" stroke="#e8191e" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                </div>
            </a>
            @endforeach
        </div>

        @if($favorites->hasMorePages())
            <a href="{{ $favorites->nextPageUrl() }}" class="ra-load-more">Voir plus</a>
        @endif
    @else
        <div class="ra-empty">
            <div class="ra-empty__icon">🔖</div>
            <div class="ra-empty__title">Aucun article sauvegardé</div>
            <div class="ra-empty__text">Appuyez sur l'icône favori dans un article pour le retrouver ici.</div>
        </div>
    @endif

</div>
@endsection
