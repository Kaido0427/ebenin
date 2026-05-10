@extends('reader.layouts.app')
@section('title', 'Mon profil')
@section('body_class', 'body--no-tabs')

@section('content')
@php
    $guard = Auth::guard('reader')->check() ? 'reader'
           : (Auth::guard('web')->check() ? 'web' : 'advertiser');
    $typeLabel = $guard === 'reader'     ? 'Compte Lecteur'
               : ($guard === 'web'       ? 'Blogueur'
               : 'Annonceur');
@endphp
<div class="ra-profile">

    {{-- Hero card --}}
    <div class="ra-profile__hero">
        <div class="ra-profile__avatar">
            {{ strtoupper(substr($user->name ?? $user->organization_name ?? 'U', 0, 1)) }}
        </div>
        <div class="ra-profile__name">{{ $user->name ?? $user->organization_name ?? 'Utilisateur' }}</div>
        <div class="ra-profile__email">{{ $user->email }}</div>
        <span class="ra-profile__type-badge">{{ $typeLabel }}</span>
    </div>

    {{-- Menu --}}
    <div class="ra-profile__section">
        <div class="ra-profile__section-title">Navigation</div>

        <a href="/reader" class="ra-profile__menu-item">
            <svg viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9.5z"/><polyline points="9 21 9 13 15 13 15 21"/></svg>
            Accueil
        </a>
        <a href="/reader/annonces" class="ra-profile__menu-item">
            <svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            Annonces
        </a>
        <a href="/reader/necrologies" class="ra-profile__menu-item">
            <svg viewBox="0 0 24 24"><line x1="12" y1="2" x2="12" y2="6"/><path d="M9 6h6a3 3 0 0 1 3 3v2a6 6 0 0 1-6 6 6 6 0 0 1-6-6V9a3 3 0 0 1 3-3z"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></svg>
            Nécrologies
        </a>
    </div>

    {{-- Déconnexion --}}
    <div class="ra-profile__section">
        <form method="POST" action="/reader/logout">
            @csrf
            <button type="submit" class="ra-profile__menu-item ra-profile__menu-item--danger" style="width:100%">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Se déconnecter
            </button>
        </form>
    </div>

    <p style="text-align:center;font-size:.72rem;color:var(--muted);padding:8px 0 16px">
        E-Benin App · Le réseau béninois d'actualités
    </p>

</div>
@endsection
