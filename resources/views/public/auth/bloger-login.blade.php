@extends('public.layouts.app')

@section('title', 'Connexion Blogueur | E-Benin')
@section('meta_description', 'Connectez-vous à votre espace blogueur E-Benin.')

@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
@endphp

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-page">
            <div class="auth-visual">
                <div class="auth-visual__logo">
                    <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" class="logo__img--light">
                </div>
                <div class="auth-visual__body">
                    <div class="auth-visual__tag">Espace Blogueur</div>
                    <h1 class="auth-visual__title">Publiez vos articles et gérez votre blog sur E-Benin.</h1>
                    <p class="auth-visual__desc">Accédez à votre dashboard, publiez des articles, gérez votre audience et développez votre présence éditoriale au Bénin.</p>
                    <div class="auth-visual__stats">
                        <div class="auth-stat"><div class="auth-stat__val">24/7</div><div class="auth-stat__label">Publication</div></div>
                        <div class="auth-stat"><div class="auth-stat__val">Multi</div><div class="auth-stat__label">Rubriques</div></div>
                        <div class="auth-stat"><div class="auth-stat__val">0 F</div><div class="auth-stat__label">Inscription</div></div>
                    </div>
                </div>
                <div class="auth-visual__footer">
                    <div class="auth-visual__quote">
                        <p>Rejoignez le réseau des rédactions et blogueurs du Bénin.</p>
                        <footer>E-Benin · Espace blogueur</footer>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-wrap">
                    <div class="auth-form-wrap__head">
                        <h1>Connexion blogueur</h1>
                        <p>Pas encore de blog ? <a href="{{ $siteRoot }}/bloger/register">Créer un blog gratuitement</a></p>
                    </div>

                    @if (session('success'))
                        <div class="auth-success" style="margin-bottom:12px;">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="auth-error" style="margin-bottom:12px;">{{ $errors->first() }}</div>
                    @endif

                    <div class="auth-card">
                        <form method="POST" action="{{ route('userLogin') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Adresse e-mail</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="form-group" style="margin-top:14px;">
                                <label for="password">Mot de passe</label>
                                <div class="pass-wrap">
                                    <input id="password" name="password" type="password" required autocomplete="current-password">
                                    <button type="button" class="pass-eye" data-target="password" aria-label="Afficher" tabindex="-1">
                                        <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin:10px 0 18px;font-size:.83rem;">
                                <label style="display:flex;gap:6px;align-items:center;cursor:pointer;">
                                    <input type="checkbox" name="remember"> Se souvenir de moi
                                </label>
                                <a href="{{ route('forgotView') }}" style="color:var(--primary);">Mot de passe oublié ?</a>
                            </div>
                            <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">Se connecter</button>
                        </form>
                    </div>

                    <p style="text-align:center;margin-top:20px;font-size:.83rem;color:var(--muted);">
                        Vous êtes annonceur ? <a href="{{ $siteRoot }}/advertiser/login" style="color:var(--primary);">Connexion annonceur</a>
                    </p>
                    <p style="text-align:center;margin-top:8px;font-size:.83rem;color:var(--muted);">
                        <a href="{{ request()->getSchemeAndHttpHost() }}/en-savoir-plus/blog" style="color:var(--primary);">En savoir plus sur les blogs E-Benin →</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
