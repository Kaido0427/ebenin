@extends('public.layouts.app')

@section('title', 'Petites annonces | E-Benin')
@section('meta_description', "Petites annonces au Bénin : emploi, immobilier, véhicules, services et bien plus sur E-Benin.")

@section('content')

<div class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">Petites annonces</h1>
        <p class="page-hero__text">Trouvez ce dont vous avez besoin au Bénin</p>
    </div>
</div>

<div class="cat-strip">
    <div class="container">
        <div class="cat-strip__inner">
            <a href="{{ route('annonces.index') }}" class="cat-tag {{ !$category ? 'active' : '' }}">Toutes</a>
            @foreach ($categories as $key => $label)
                <a href="{{ route('annonces.index', ['category' => $key]) }}" class="cat-tag {{ $category === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</div>

<main style="padding: 28px 0 56px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                {{ $category ? ($categories[$category] ?? 'Annonces') : 'Toutes les annonces' }}
                @if (!$annonces->isEmpty())
                    <span style="font-size:.85rem;font-weight:400;color:var(--muted);margin-left:8px;">{{ $annonces->total() }} résultat(s)</span>
                @endif
            </h2>
            <a href="{{ route('advertiser.register') }}" class="section-more">+ Publier une annonce</a>
        </div>

        @if ($annonces->isEmpty())
            <div style="text-align:center;padding:60px 0;color:var(--muted);">
                <div style="font-size:2.8rem;margin-bottom:12px;">📋</div>
                <p>Aucune annonce disponible pour le moment.</p>
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
                            <div class="card__img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--border);">📋</div>
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

@endsection
