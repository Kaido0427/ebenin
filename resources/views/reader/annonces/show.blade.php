@extends('reader.layouts.app')
@section('title', $annonce->title)
@section('body_class', 'body--no-tabs')

@section('content')
<div class="ra-annonce-detail">

    {{-- Gallery --}}
    <div class="ra-annonce-detail__gallery">
        @if($annonce->images && count($annonce->images))
            <img src="{{ asset($annonce->images[0]) }}" alt="{{ $annonce->title }}" class="ra-annonce-detail__img">
        @else
            <div class="ra-annonce-detail__img" style="background:linear-gradient(135deg,#f5f6f8,#e5e7eb);display:flex;align-items:center;justify-content:center;font-size:3rem;">🏷️</div>
        @endif
        <button class="ra-annonce-detail__back" onclick="history.back()" aria-label="Retour">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
    </div>

    {{-- Gallery thumbnails --}}
    @if($annonce->images && count($annonce->images) > 1)
    <div style="display:flex;gap:8px;overflow-x:auto;padding:10px 16px;background:#fff;scrollbar-width:none;">
        @foreach($annonce->images as $i => $img)
        <img src="{{ asset($img) }}" alt="" onclick="this.closest('.ra-annonce-detail').querySelector('.ra-annonce-detail__img').src='{{ asset($img) }}'"
             style="width:60px;height:60px;object-fit:cover;border-radius:8px;flex-shrink:0;cursor:pointer;border:2px solid {{ $i === 0 ? 'var(--primary)' : 'transparent' }}">
        @endforeach
    </div>
    @endif

    <div class="ra-annonce-detail__body">
        <div class="ra-annonce-detail__badge">{{ $annonce->category_label }}</div>
        <h1 class="ra-annonce-detail__title">{{ $annonce->title }}</h1>
        @if($annonce->price)
            <div class="ra-annonce-detail__price">{{ number_format($annonce->price, 0, ',', ' ') }} FCFA</div>
        @endif

        <p class="ra-annonce-detail__desc">{{ $annonce->description }}</p>

        {{-- Info --}}
        <div class="ra-annonce-detail__info">
            @if($annonce->location)
            <div class="ra-annonce-detail__info-row">
                <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $annonce->location }}
            </div>
            @endif
            @if($annonce->advertiser)
            <div class="ra-annonce-detail__info-row">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $annonce->advertiser->name }}
            </div>
            @endif
            <div class="ra-annonce-detail__info-row">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Publié {{ $annonce->created_at->diffForHumans() }}
            </div>
        </div>

        {{-- Contact buttons --}}
        @if($annonce->contact_phone)
        <a href="tel:{{ $annonce->contact_phone }}" class="ra-contact-btn ra-contact-btn--primary">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            Appeler : {{ $annonce->contact_phone }}
        </a>
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $annonce->contact_phone) }}?text=Bonjour, je vous contacte pour l'annonce : {{ urlencode($annonce->title) }}"
           target="_blank" class="ra-contact-btn ra-contact-btn--green">
            <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            WhatsApp
        </a>
        @elseif($annonce->contact_email)
        <a href="mailto:{{ $annonce->contact_email }}" class="ra-contact-btn ra-contact-btn--primary">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Envoyer un email
        </a>
        @endif
    </div>

    {{-- Similar --}}
    @if($similar->isNotEmpty())
    <div style="padding:0 16px 20px">
        <div class="ra-related__title">Annonces similaires</div>
        <div class="ra-related__grid">
            @foreach($similar as $s)
            @php $sImg = $s->images && count($s->images) ? asset($s->images[0]) : null; @endphp
            <a href="/reader/annonces/{{ $s->id }}" class="ra-related__card">
                @if($sImg)
                    <img src="{{ $sImg }}" alt="{{ $s->title }}" class="ra-related__card-img" loading="lazy">
                @else
                    <div class="ra-related__card-img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🏷️</div>
                @endif
                <div class="ra-related__card-body">
                    <div class="ra-related__card-title">{{ $s->title }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
