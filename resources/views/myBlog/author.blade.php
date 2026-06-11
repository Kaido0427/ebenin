@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $homeUrl    = "https://{$org->subdomain}.{$baseDomain}/blog";

    $avatarUrl  = ($bio && filled($bio->avatar ?? null))
        ? asset($bio->avatar)
        : asset('images/dists/user.webp');

    $postImageUrl = function ($post) use ($org) {
        $image = trim((string) ($post->image ?? ''));
        if ($image === '') {
            $logo = trim((string) ($org->organization_logo ?? 'images/ebenins.png'));
            return Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset(ltrim($logo, '/'));
        }
        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }
        $normalized = ltrim($image, '/');
        return Str::startsWith($normalized, ['uploads/', 'images/', 'storage/'])
            ? asset($normalized)
            : asset('uploads/posts/images/' . basename($normalized));
    };

    $postUrl = fn($post) => "https://{$org->subdomain}.{$baseDomain}/post/{$post->id}";
@endphp

@extends('public.layouts.app')

@section('title', $author->name . ' | ' . $org->organization_name)
@section('meta_description', 'Découvrez les articles publiés par ' . $author->name . ' sur ' . $org->organization_name . '.')
@section('canonical', "https://{$org->subdomain}.{$baseDomain}/auteur/{$author->id}")

@section('content')
<div class="container" style="max-width:860px; margin:2rem auto; padding:0 1rem;">

    {{-- Carte auteur --}}
    <div style="display:flex; align-items:flex-start; gap:1.5rem; background:var(--card-bg,#fff); border-radius:12px; padding:2rem; margin-bottom:2rem; box-shadow:0 2px 8px rgba(0,0,0,.06);">
        <img src="{{ $avatarUrl }}" alt="{{ $author->name }}"
             style="width:96px; height:96px; border-radius:50%; object-fit:cover; flex-shrink:0;">
        <div>
            <h1 style="margin:0 0 .4rem; font-size:1.4rem;">{{ $author->name }}</h1>
            <div style="color:#888; font-size:.9rem; margin-bottom:1rem;">{{ $org->organization_name }}</div>
            @if($bio && filled($bio->bio ?? null))
                @php
                    $fullBio  = strip_tags($bio->bio);
                    $shortBio = \Illuminate\Support\Str::limit($fullBio, 180);
                    $needsMore = strlen($fullBio) > 180;
                @endphp
                <p style="line-height:1.7; margin:0;">
                    <span id="bio-short">{{ $shortBio }}@if($needsMore)…
                        <button onclick="document.getElementById('bio-short').style.display='none';document.getElementById('bio-full').style.display='inline';"
                                style="background:none;border:none;padding:0;color:var(--accent,#e30613);font-size:.9em;cursor:pointer;font-weight:600;">Voir plus</button>
                    @endif</span>
                    @if($needsMore)
                        <span id="bio-full" style="display:none;">{{ $fullBio }}
                            <button onclick="document.getElementById('bio-full').style.display='none';document.getElementById('bio-short').style.display='inline';"
                                    style="background:none;border:none;padding:0;color:var(--accent,#e30613);font-size:.9em;cursor:pointer;font-weight:600;">Voir moins</button>
                        </span>
                    @endif
                </p>
            @else
                <p style="color:#aaa; margin:0;">Aucune biographie disponible.</p>
            @endif
        </div>
    </div>

    {{-- Articles de l'auteur --}}
    <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:1.2rem; border-left:3px solid var(--accent,#e30613); padding-left:.7rem;">
        Articles de {{ $author->name }} ({{ $posts->count() }})
    </h2>

    @forelse($posts as $post)
        <a href="{{ $postUrl($post) }}" style="display:flex; gap:1rem; text-decoration:none; color:inherit; background:var(--card-bg,#fff); border-radius:10px; padding:1rem; margin-bottom:1rem; box-shadow:0 1px 4px rgba(0,0,0,.05);">
            <img src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}"
                 style="width:90px; height:65px; object-fit:cover; border-radius:6px; flex-shrink:0;">
            <div>
                <div style="font-weight:600; margin-bottom:.3rem; line-height:1.4;">{{ $post->libelle }}</div>
                <div style="font-size:.82rem; color:#888;">
                    {{ $post->rubriques->first()->name ?? '' }}
                    @if($post->rubriques->first()) · @endif
                    {{ optional($post->created_at)->diffForHumans() }}
                </div>
            </div>
        </a>
    @empty
        <p style="color:#aaa;">Aucun article publié pour le moment.</p>
    @endforelse

    <div style="margin-top:1.5rem;">
        <a href="{{ $homeUrl }}" style="color:var(--accent,#e30613); font-size:.9rem;">← Retour à l'accueil</a>
    </div>
</div>
@endsection
