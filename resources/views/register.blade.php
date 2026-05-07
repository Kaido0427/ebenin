@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $navItems = collect($rubriquesWithoutPosts)->take(8);
    $footerRubriques = $rubriquesWithoutPosts;
    $showAuthModal = false;
@endphp

@extends('public.layouts.app')

@section('title', "Créer un blog | E-Benin")
@section('meta_description', "Créez votre blog d'actualité sur E-Benin et rejoignez le réseau des rédactions et blogueurs du Bénin.")
@section('canonical', $siteRoot . '/bloger/register')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <section class="auth-shell">
        <div class="container">
            <div class="auth-page">
                <div class="auth-visual">
                    <div class="auth-visual__logo">
                        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" class="logo__img--light">
                    </div>

                    <div class="auth-visual__body">
                        <div class="auth-visual__tag">Réseau éditorial</div>
                        <h1 class="auth-visual__title">Lancez votre média avec un front moderne et votre propre dashboard.</h1>
                        <p class="auth-visual__desc">
                            E-Benin vous permet de publier vos articles, construire votre audience et rejoindre un portail d'information multi-auteurs déjà visible sur le web.
                        </p>

                        <div class="auth-visual__stats">
                            <div class="auth-stat">
                                <div class="auth-stat__val">{{ collect($rubriquesWithoutPosts)->count() }}</div>
                                <div class="auth-stat__label">Rubriques</div>
                            </div>
                            <div class="auth-stat">
                                <div class="auth-stat__val">24/7</div>
                                <div class="auth-stat__label">Publication</div>
                            </div>
                            <div class="auth-stat">
                                <div class="auth-stat__val">1</div>
                                <div class="auth-stat__label">Dashboard</div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-visual__footer">
                        <div class="auth-visual__quote">
                            <p>Publiez vos contenus, gérez vos rubriques, ajoutez vos réseaux et développez votre présence éditoriale.</p>
                            <footer>E-Benin · Création de blog</footer>
                        </div>
                    </div>
                </div>

                <div class="auth-form-panel">
                    <div class="auth-form-wrap">
                        <div class="auth-form-wrap__head">
                            <h1>Créer mon blog</h1>
                            <p>Vous avez déjà un compte ? <a href="{{ $siteRoot }}/?auth=login">Se connecter</a></p>
                        </div>

                        @if ($errors->any())
                            <div class="auth-error" style="margin-bottom:12px;">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="auth-card">
                            <form id="register-form" method="POST" action="/bloger/register" enctype="multipart/form-data">
                                @csrf

                                <div class="auth-grid">
                                    <div>
                                        <label for="name">Nom complet</label>
                                        <input id="name" name="name" type="text" required>
                                    </div>
                                    <div>
                                        <label for="email">E-mail</label>
                                        <input id="email" name="email" type="email" required>
                                    </div>
                                </div>

                                <div class="auth-grid" style="margin-top:14px;">
                                    <div>
                                        <label for="password">Mot de passe</label>
                                        <div class="pass-wrap">
                                            <input id="password" name="password" type="password" required>
                                            <button type="button" class="pass-eye" data-target="password" aria-label="Afficher le mot de passe" tabindex="-1">
                                                <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation">Confirmation</label>
                                        <div class="pass-wrap">
                                            <input id="password_confirmation" name="password_confirmation" type="password" required>
                                            <button type="button" class="pass-eye" data-target="password_confirmation" aria-label="Afficher le mot de passe" tabindex="-1">
                                                <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                                <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="auth-grid" style="margin-top:14px;">
                                    <div>
                                        <label for="phone">Téléphone</label>
                                        <input id="phone" name="phone" type="text">
                                    </div>
                                    <div>
                                        <label for="address">Adresse</label>
                                        <input id="address" name="address" type="text">
                                    </div>
                                </div>

                                <div class="auth-grid" style="margin-top:14px;">
                                    <div>
                                        <label for="organization_name">Nom de l'organisation</label>
                                        <input id="organization_name" name="organization_name" type="text" required>
                                    </div>
                                    <div>
                                        <label for="organization_email">E-mail de l'organisation</label>
                                        <input id="organization_email" name="organization_email" type="email" required>
                                    </div>
                                </div>

                                <div class="auth-grid" style="margin-top:14px;">
                                    <div>
                                        <label for="organization_address">Adresse de l'organisation</label>
                                        <input id="organization_address" name="organization_address" type="text">
                                    </div>
                                    <div>
                                        <label for="organization_phone">Téléphone de l'organisation</label>
                                        <input id="organization_phone" name="organization_phone" type="text">
                                    </div>
                                </div>

                                <div style="display:flex;align-items:center;gap:18px;margin-top:18px;flex-wrap:wrap;">
                                    <div style="flex:1;min-width:220px;">
                                        <label for="organization_logo">Logo de l'organisation</label>
                                        <input type="file" name="organization_logo" id="organization_logo" accept="image/*">
                                        <div class="form-hint">PNG, JPG ou WEBP recommandé pour personnaliser votre blog.</div>
                                    </div>
                                    <div class="image-preview" id="imagePreview">
                                        <img src="" alt="Aperçu du logo" class="image-preview__image">
                                        <span class="image-preview__default-text">Aperçu logo</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:18px;">
                                    Créer mon blog gratuitement
                                </button>
                                <p class="auth-form-note">90 jours d'essai gratuit. Aucun paiement requis à l'inscription.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('organization_logo').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('.image-preview__image');
            const defaultText = preview.querySelector('.image-preview__default-text');
            defaultText.style.display = 'none';
            img.style.display = 'block';
            reader.addEventListener('load', () => img.setAttribute('src', reader.result));
            reader.readAsDataURL(file);
        });
    </script>
@endpush
