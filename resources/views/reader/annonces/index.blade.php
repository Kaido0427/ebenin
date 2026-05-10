@extends('reader.layouts.app')
@section('title', 'Annonces')
@section('body_class', 'body--no-tabs')

@section('content')
<div class="ra-page">

    {{-- Category pills --}}
    <div class="ra-cats">
        <a href="/reader/annonces" class="ra-cat-pill {{ !$cat ? 'active' : '' }}">Tout</a>
        @foreach($categories as $key => $label)
            <a href="/reader/annonces?cat={{ $key }}"
               class="ra-cat-pill {{ $cat === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    @if($annonces->isNotEmpty())
        <div class="ra-annonce-grid">
            @foreach($annonces as $annonce)
            @php $img = $annonce->images && count($annonce->images) ? asset($annonce->images[0]) : null; @endphp
            <a href="/reader/annonces/{{ $annonce->id }}" class="ra-annonce-card">
                @if($img)
                    <img src="{{ $img }}" alt="{{ $annonce->title }}" class="ra-annonce-card__img" loading="lazy">
                @else
                    <div class="ra-annonce-card__img-ph">🏷️</div>
                @endif
                <div class="ra-annonce-card__body">
                    <div class="ra-annonce-card__cat">{{ $annonce->category_label }}</div>
                    <div class="ra-annonce-card__title">{{ $annonce->title }}</div>
                    @if($annonce->price)
                        <div class="ra-annonce-card__price">{{ number_format($annonce->price, 0, ',', ' ') }} FCFA</div>
                    @endif
                    @if($annonce->location)
                        <div class="ra-annonce-card__location">📍 {{ $annonce->location }}</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        @if($annonces->hasMorePages())
            <a href="{{ $annonces->nextPageUrl() }}" class="ra-load-more">Voir plus d'annonces</a>
        @endif
    @else
        <div class="ra-empty">
            <div class="ra-empty__icon">📭</div>
            <div class="ra-empty__title">Aucune annonce</div>
            <div class="ra-empty__text">Aucune annonce disponible pour le moment.</div>
        </div>
    @endif

</div>
@endsection
