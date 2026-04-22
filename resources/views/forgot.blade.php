@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
    $showAuthModal = false;
@endphp

@extends('public.layouts.app')

@section('title', 'Mot de passe oublié | E-Benin')
@section('meta_description', "Réinitialisez l'accès à votre compte E-Benin en demandant un nouveau mot de passe temporaire.")
@section('canonical', $siteRoot . '/forgot-password')

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
                        <div class="auth-visual__tag">Assistance</div>
                        <h1 class="auth-visual__title">Récupérez rapidement l'accès à votre espace rédaction.</h1>
                        <p class="auth-visual__desc">
                            Entrez l'adresse e-mail de votre compte. Nous générerons un mot de passe temporaire et vous l'enverrons automatiquement.
                        </p>
                    </div>

                    <div class="auth-visual__footer">
                        <div class="auth-visual__quote">
                            <p>En cas de souci persistant, contactez l'équipe support SAVPLUS pour faire vérifier votre compte.</p>
                            <footer>contact@savplus.net</footer>
                        </div>
                    </div>
                </div>

                <div class="auth-form-panel">
                    <div class="auth-form-wrap">
                        <div class="auth-form-wrap__head">
                            <h1>Nouveau mot de passe</h1>
                            <p>Retour à l'accueil <a href="{{ $siteRoot }}">E-Benin</a></p>
                        </div>

                        <div class="auth-card">
                            <div id="forgot-success" class="auth-success" style="display:none;"></div>
                            <div id="forgot-error" class="auth-error" style="display:none;"></div>

                            <form id="forgot-password-form" class="comment-form">
                                <label for="email">Adresse e-mail</label>
                                <input id="email" name="email" type="email" placeholder="Votre adresse e-mail" required>
                                <button class="btn btn--primary" type="submit" style="justify-content:center;">
                                    <span id="button-text">Envoyer le nouveau mot de passe</span>
                                </button>
                            </form>

                            <p class="auth-form-note">Le mot de passe envoyé est temporaire. Pensez à le modifier ensuite depuis votre dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgot-password-form');
            const buttonText = document.getElementById('button-text');
            const submitButton = form.querySelector('button[type="submit"]');
            const successBox = document.getElementById('forgot-success');
            const errorBox = document.getElementById('forgot-error');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                successBox.style.display = 'none';
                errorBox.style.display = 'none';
                submitButton.disabled = true;
                buttonText.textContent = 'Envoi en cours...';

                fetch('{{ $siteRoot }}/bloger/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: document.getElementById('email').value
                    })
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Une erreur est survenue.');
                    }
                    return data;
                })
                .then((data) => {
                    successBox.textContent = data.message;
                    successBox.style.display = 'block';
                    form.reset();
                })
                .catch((error) => {
                    errorBox.textContent = error.message;
                    errorBox.style.display = 'block';
                })
                .finally(() => {
                    submitButton.disabled = false;
                    buttonText.textContent = 'Envoyer le nouveau mot de passe';
                });
            });
        });
    </script>
@endpush
