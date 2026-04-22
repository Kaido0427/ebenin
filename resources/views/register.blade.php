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

                        <div class="auth-card">
                            <form id="register-form" enctype="multipart/form-data">
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
                                        <input id="password" name="password" type="password" required>
                                    </div>
                                    <div>
                                        <label for="password_confirmation">Confirmation</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required>
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

                                <div id="form-error" class="auth-error" style="display:none;"></div>

                                <button id="kkiapay-button" type="button" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:18px;">
                                    Créer mon blog
                                </button>
                                <p class="auth-form-note">Le paiement d'activation s'ouvrira ensuite dans la fenêtre sécurisée Kkiapay.</p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.kkiapay.me/k.js"></script>
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

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('kkiapay-button');
            const form = document.getElementById('register-form');
            const errorDiv = document.getElementById('form-error');

            btn.addEventListener('click', function() {
                errorDiv.style.display = 'none';
                btn.disabled = true;
                btn.textContent = 'Traitement en cours...';

                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('register') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const callbackUrl = window.location.origin + '/transaction/' + data.organization;

                        openKkiapayWidget({
                            amount: data.amount,
                            callback: callbackUrl,
                            data: JSON.stringify({ organization: data.organization }),
                            position: 'center',
                            theme: '#003f7f',
                            sandbox: false,
                            key: 'cb876650e192fdf79d12342d023a6f4ebe257de4'
                        });
                    } else {
                        let message = data.message || 'Une erreur est survenue.';
                        if (data.errors) {
                            message = Object.values(data.errors).flat().join('<br>');
                        }
                        errorDiv.innerHTML = message;
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(() => {
                    errorDiv.innerHTML = 'Erreur de communication avec le serveur. Veuillez réessayer.';
                    errorDiv.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'Créer mon blog';
                });
            });
        });
    </script>
@endpush
