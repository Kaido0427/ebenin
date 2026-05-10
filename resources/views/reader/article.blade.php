@extends('reader.layouts.app')
@section('title', $post->libelle)
@section('body_class', 'body--article')

@php
    $imgUrl  = $post->image ? asset($post->image) : ($post->image_url ?? null);
    $org     = $post->user?->organization;
    $orgLogo = $org?->organization_logo ? asset($org->organization_logo) : null;
    $catIdx  = $post->rubriques->isNotEmpty() ? ($post->rubriques->first()->id % 12) : 0;
@endphp

@section('content')
<div class="ra-article">

    {{-- Fixed topbar --}}
    <div class="ra-article__topbar">
        <button class="ra-article__topbar-btn" onclick="history.back()" aria-label="Retour">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="ra-article__topbar-actions">
            <button class="ra-article__topbar-btn" onclick="navigator.share && navigator.share({title:'{{ addslashes($post->libelle) }}',url:location.href})" aria-label="Partager">
                <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>
    </div>

    {{-- Hero image --}}
    <div class="ra-article__hero">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $post->libelle }}" class="ra-article__hero-img">
        @else
            <div class="ra-article__hero-img" style="background:linear-gradient(135deg,#003f7f,#0057b3);"></div>
        @endif
    </div>

    {{-- Body --}}
    <div class="ra-article__body">

        @if($post->rubriques->isNotEmpty())
        <div class="ra-article__cat cat-color-{{ $catIdx }}">
            @foreach($post->rubriques as $rub)
                {{ $rub->name }}{{ !$loop->last ? ' · ' : '' }}
            @endforeach
        </div>
        @endif

        <h1 class="ra-article__title">{{ $post->libelle }}</h1>

        <div class="ra-article__byline">
            @if($orgLogo)
                <img src="{{ $orgLogo }}" alt="{{ $org->organization_name }}"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
                <div style="width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;font-weight:900;font-size:.7rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    {{ strtoupper(substr($org?->organization_name ?? 'E', 0, 1)) }}
                </div>
            @endif
            <span>{{ $org?->organization_name ?? 'E-Benin' }}</span>
            <span class="ra-article__byline-sep">·</span>
            <span>{{ $post->created_at->translatedFormat('d F Y') }}</span>
        </div>

        @if($post->sous_titre)
            <p style="font-size:.95rem;font-weight:600;color:var(--mid);margin-bottom:18px;line-height:1.55;">{{ $post->sous_titre }}</p>
        @endif

        <div class="ra-article__content">
            {!! $post->description !!}
        </div>

        @if($post->video)
        <div style="margin-top:20px">
            <video controls style="width:100%;border-radius:var(--radius)">
                <source src="{{ asset($post->video) }}">
            </video>
        </div>
        @elseif($post->video_url)
        <div style="margin-top:20px;position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius)">
            <iframe src="{{ $post->video_url }}"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;border:0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
        @endif

    </div>

    {{-- À lire aussi --}}
    @if($related->isNotEmpty())
    <div class="ra-read-also">
        <div class="ra-read-also__title">À lire aussi</div>
        <div class="ra-related__grid">
            @foreach($related as $r)
            @php $rImg = $r->image ? asset($r->image) : ($r->image_url ?? null); @endphp
            <a href="/reader/article/{{ $r->id }}" class="ra-related__card">
                @if($rImg)
                    <img src="{{ $rImg }}" alt="{{ $r->libelle }}" class="ra-related__card-img" loading="lazy">
                @else
                    <div class="ra-related__card-img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:1.2rem;height:90px;">📰</div>
                @endif
                <div class="ra-related__card-body">
                    <div class="ra-related__card-title">{{ $r->libelle }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
