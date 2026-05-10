@extends('reader.layouts.app')
@section('title', 'Mon profil')
@section('body_class', 'body--no-tabs')

@php
    $guard = Auth::guard('reader')->check()     ? 'reader'
           : (Auth::guard('web')->check()       ? 'web'
           : (Auth::guard('advertiser')->check() ? 'advertiser' : 'admin'));
    $typeLabel = match($guard) {
        'reader'     => 'Lecteur',
        'web'        => 'Blogueur',
        'advertiser' => 'Annonceur',
        default      => 'Admin',
    };
    $userName = $user->name ?? $user->organization_name ?? 'Utilisateur';
    $initial  = strtoupper(substr($userName, 0, 1));
@endphp

@section('content')
<div class="ra-profile">

    {{-- Hero --}}
    <div class="ra-profile__hero">
        <div class="ra-profile__avatar">{{ $initial }}</div>
        <div class="ra-profile__name">{{ $userName }}</div>
        <div class="ra-profile__email">{{ $user->email }}</div>
        <span class="ra-profile__badge">{{ $typeLabel }}</span>

        {{-- Stats --}}
        <div class="ra-profile__stats">
            <a href="/reader/favoris" class="ra-profile__stat">
                <div class="ra-profile__stat-num">{{ $favCount }}</div>
                <div class="ra-profile__stat-label">Favoris</div>
            </a>
            <div class="ra-profile__stat-sep"></div>
            <div class="ra-profile__stat">
                <div class="ra-profile__stat-num">{{ $commentCount }}</div>
                <div class="ra-profile__stat-label">Commentaires</div>
            </div>
        </div>
    </div>

    {{-- Mes contenus --}}
    <div class="ra-profile__section">
        <div class="ra-profile__section-label">Mes contenus</div>

        <a href="/reader/favoris" class="ra-profile__item">
            <svg viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
            Articles sauvegardés
            @if($favCount > 0)
                <span style="margin-left:auto;background:var(--accent);color:#fff;font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:20px;">{{ $favCount }}</span>
            @endif
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-left:{{ $favCount > 0 ? '8px' : 'auto' }};flex-shrink:0;stroke:var(--muted);fill:none;stroke-width:2.5;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    {{-- Navigation --}}
    <div class="ra-profile__section">
        <div class="ra-profile__section-label">Navigation</div>

        <a href="/reader" class="ra-profile__item">
            <svg viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><polyline points="9 21 9 13 15 13 15 21"/></svg>
            Accueil
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-left:auto;flex-shrink:0;stroke:var(--muted);fill:none;stroke-width:2.5;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="/reader/categories" class="ra-profile__item">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Catégories
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-left:auto;flex-shrink:0;stroke:var(--muted);fill:none;stroke-width:2.5;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="/reader/annonces" class="ra-profile__item">
            <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            Annonces
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-left:auto;flex-shrink:0;stroke:var(--muted);fill:none;stroke-width:2.5;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="/reader/necrologies" class="ra-profile__item">
            <svg viewBox="0 0 24 24"><path d="M12 2C8 2 5 5 5 9c0 5 7 13 7 13s7-8 7-13c0-4-3-7-7-7z"/></svg>
            Nécrologies
            <svg viewBox="0 0 24 24" style="width:16px;height:16px;margin-left:auto;flex-shrink:0;stroke:var(--muted);fill:none;stroke-width:2.5;"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    {{-- Compte --}}
    <div class="ra-profile__section">
        <div class="ra-profile__section-label">Mon compte</div>
        <form method="POST" action="/reader/logout">
            @csrf
            <button type="submit" class="ra-profile__item ra-profile__item--danger" style="width:100%;border:none;cursor:pointer;font-family:inherit;text-align:left;">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Se déconnecter
            </button>
        </form>
    </div>

    <p style="text-align:center;font-size:.72rem;color:var(--muted);padding:8px 0 20px;">
        E-Benin App v1.0 · Le réseau béninois d'actualités
    </p>

</div>
@endsection
