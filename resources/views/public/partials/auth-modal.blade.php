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
            <input id="login-password" name="password" type="password" required autocomplete="current-password">

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
