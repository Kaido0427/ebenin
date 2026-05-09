@extends('reader.layouts.app')
@section('title', $post->libelle)

@section('content')
@php
    $imgUrl = $post->image ? asset($post->image) : ($post->image_url ?? null);
    $org    = $post->user?->organization;
    $orgLogo = $org?->organization_logo ? asset($org->organization_logo) : null;
@endphp
<div class="ra-article">

    {{-- Hero --}}
    <div class="ra-article__hero">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $post->libelle }}" class="ra-article__hero-img">
        @else
            <div class="ra-article__hero-img" style="background:linear-gradient(135deg,#003f7f,#0057b3);"></div>
        @endif
        <div class="ra-article__hero-overlay"></div>
        <button class="ra-article__back" onclick="history.back()" aria-label="Retour">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
    </div>

    {{-- Body --}}
    <div class="ra-article__body">

        {{-- Categories --}}
        @if($post->rubriques->isNotEmpty())
        <div class="ra-article__cats">
            @foreach($post->rubriques as $rub)
                <span class="ra-article__cat-badge">{{ $rub->name }}</span>
            @endforeach
        </div>
        @endif

        {{-- Title --}}
        <h1 class="ra-article__title">{{ $post->libelle }}</h1>

        {{-- Meta --}}
        <div class="ra-article__meta">
            @if($orgLogo)
                <img src="{{ $orgLogo }}" alt="{{ $org->organization_name }}" class="ra-article__meta-avatar">
            @else
                <div class="ra-article__meta-avatar" style="background:var(--primary);color:#fff;font-weight:900;font-size:.8rem;display:flex;align-items:center;justify-content:center;border-radius:50%;">
                    {{ strtoupper(substr($org?->organization_name ?? 'E', 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="ra-article__meta-name">{{ $org?->organization_name ?? 'E-Benin' }}</div>
                <div>{{ $post->created_at->translatedFormat('d F Y') }}</div>
            </div>
        </div>

        {{-- Sous-titre --}}
        @if($post->sous_titre)
            <p style="font-size:.95rem;font-weight:600;color:var(--mid);margin-bottom:16px;line-height:1.5;">{{ $post->sous_titre }}</p>
        @endif

        {{-- Content --}}
        <div class="ra-article__content">
            {!! $post->description !!}
        </div>

        {{-- Video --}}
        @if($post->video)
        <div style="margin-top:20px">
            <video controls style="width:100%;border-radius:var(--radius)">
                <source src="{{ asset($post->video) }}">
            </video>
        </div>
        @elseif($post->video_url)
        <div style="margin-top:20px;position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius)">
            <iframe src="{{ $post->video_url }}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        @endif

    </div>

    {{-- Related --}}
    @if($related->isNotEmpty())
    <div class="ra-related">
        <div class="ra-related__title">À lire aussi</div>
        <div class="ra-related__grid">
            @foreach($related as $r)
            @php $rImg = $r->image ? asset($r->image) : ($r->image_url ?? null); @endphp
            <a href="/reader/article/{{ $r->id }}" class="ra-related__card">
                @if($rImg)
                    <img src="{{ $rImg }}" alt="{{ $r->libelle }}" class="ra-related__card-img" loading="lazy">
                @else
                    <div class="ra-related__card-img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">📰</div>
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
