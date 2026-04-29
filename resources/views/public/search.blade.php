@php
    use Illuminate\Support\Str;

    $host       = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

    $postImageUrl = function ($post) {
        $image = trim((string) ($post->image ?? ''));
        if ($image === '') return asset('images/ebenins.png');
        if (Str::startsWith($image, ['http://', 'https://'])) return $image;
        $normalized = ltrim($image, '/');
        if (Str::startsWith($normalized, ['uploads/', 'images/', 'storage/'])) return asset($normalized);
        return asset('uploads/posts/images/' . basename($normalized));
    };

    $postUrl = function ($post) use ($organization, $baseDomain) {
        $subdomain = $organization->subdomain ?? null;
        return $subdomain ? "https://{$subdomain}.{$baseDomain}/post/{$post->id}" : '#';
    };

    $excerpt = function ($post) {
        return Str::limit(html_entity_decode(strip_tags((string) ($post->description ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 150);
    };
@endphp

@extends('public.layouts.app')

@section('title', $query ? "Résultats pour : {$query}" : 'Recherche')
@section('meta_description', 'Recherchez des articles sur ' . ($organization->organization_name ?? 'E-Benin'))

@section('content')
<div class="page-hero" style="background:var(--dark);padding:48px 0 32px;">
    <div class="container">
        <p class="page-hero__eyebrow">Recherche</p>
        <h1 class="page-hero__title" style="color:#fff;">{{ $organization->organization_name ?? 'E-Benin' }}</h1>
        <form action="{{ url('/search') }}" method="GET" style="display:flex;gap:10px;margin-top:20px;max-width:600px;">
            <input type="text" name="q" value="{{ $query }}" placeholder="Rechercher un article..."
                   style="flex:1;padding:10px 16px;border-radius:8px;border:none;font-size:1rem;">
            @if($rubriques->count() > 0)
            <select name="rubrique" onchange="this.form.submit()"
                    style="padding:10px 12px;border-radius:8px;border:none;font-size:.9rem;">
                <option value="">Toutes les rubriques</option>
                @foreach($rubriques as $rubrique)
                    <option value="{{ $rubrique->id }}" {{ request('rubrique') == $rubrique->id ? 'selected' : '' }}>
                        {{ $rubrique->name }}
                    </option>
                @endforeach
            </select>
            @endif
            <button type="submit" style="padding:10px 20px;background:var(--accent);color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;">Chercher</button>
        </form>
    </div>
</div>

<main>
    <div class="container" style="padding-top:32px;padding-bottom:48px;">
        @if($query)
            <p style="margin-bottom:24px;color:var(--mid);">
                <strong>{{ $posts->total() }}</strong> résultat(s) pour « {{ $query }} »
            </p>
        @endif

        @if($posts->count() > 0)
            <div class="news-grid">
                @foreach($posts as $post)
                    <a href="{{ $postUrl($post) }}" class="card">
                        <div class="card__img-wrap">
                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                            <span class="card__cat">{{ $post->rubriques->first()->name ?? 'Actualité' }}</span>
                        </div>
                        <div class="card__body">
                            <h3 class="card__title">{{ $post->libelle }}</h3>
                            <p class="card__excerpt">{{ $excerpt($post) }}</p>
                        </div>
                        <div class="card__footer">
                            <div class="card__meta">
                                <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                <span>{{ $post->comments->count() }} com.</span>
                            </div>
                            <div class="card__author">
                                {{ $post->user->name ?? 'Rédaction' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top:32px;">
                {{ $posts->withQueryString()->links('vendor.pagination.bootstrap-4') }}
            </div>
        @elseif($query)
            <p style="color:var(--mid);text-align:center;padding:48px 0;">
                Aucun article ne correspond à « {{ $query }} ». Essayez d'autres mots-clés.
            </p>
        @else
            <p style="color:var(--mid);text-align:center;padding:48px 0;">
                Saisissez un terme de recherche pour trouver des articles.
            </p>
        @endif
    </div>
</main>
@endsection
