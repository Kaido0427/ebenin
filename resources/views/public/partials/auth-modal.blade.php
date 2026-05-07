@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $siteRoot = 'https://' . $baseDomain;
@endphp

<div class="login-modal" id="auth-login-modal">
    <div class="login-modal__backdrop" data-auth-close></div>
    <div class="login-modal__panel">
        <button type="button" class="login-modal__close" data-auth-close aria-label="Fermer">✕</button>
        <div class="login-modal__eyebrow">E-Benin</div>
        <h2 class="login-modal__title">Connexion à votre espace blogueur</h2>
        <p class="login-modal__text">
            Connectez-vous depuis le domaine principal pour retrouver ensuite automatiquement votre dashboard.
        </p>

        <form method="POST" action="{{ $siteRoot }}/bloger/login" class="login-form">
            @csrf
            <label class="login-form__label" for="login-email">Adresse e-mail</label>
            <input id="login-email" name="email" type="email" value="{{ old('email') }}" required>

            <label class="login-form__label" for="login-password">Mot de passe</label>
            <div class="pass-wrap">
                <input id="login-password" name="password" type="password" required autocomplete="current-password">
                <button type="button" class="pass-eye" data-target="login-password" aria-label="Afficher le mot de passe" tabindex="-1">
                    <svg class="eye-show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-hide" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>

            @if ($errors->has('email') || $errors->has('password'))
                <div class="login-form__error">
                    {{ $errors->first('email') ?: $errors->first('password') }}
                </div>
            @endif

            <button type="submit" class="btn btn--primary login-form__submit">Se connecter</button>
        </form>

        <div class="login-modal__links">
            <a href="{{ $siteRoot }}/forgot-password">Mot de passe oublié</a>
            <a href="{{ $siteRoot }}/bloger/register">Créer un blog</a>
        </div>
    </div>
</div>
