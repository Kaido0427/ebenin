<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#003f7f">
    <title>Inscription — E-Benin App</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/reader-app.css') }}?v={{ filemtime(public_path('css/reader-app.css')) }}">
</head>
<body style="padding-top:0; padding-bottom:0;">
<div class="ra-auth">

    <div class="ra-auth__hero">
        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" class="ra-auth__logo">
        <div class="ra-auth__tagline">Le réseau béninois d'actualités</div>
        <div class="ra-auth__title">Créer un compte lecteur</div>
    </div>

    <div class="ra-auth__body">

        @if($errors->any())
            <div class="ra-alert ra-alert--error" style="margin-bottom:16px">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('reader.register.post') }}" class="ra-auth__form">
            @csrf

            <div class="ra-form-group">
                <label class="ra-form-label" for="name">Votre prénom / nom</label>
                <input class="ra-form-input" type="text" id="name" name="name"
                       value="{{ old('name') }}" autocomplete="name" required>
            </div>

            <div class="ra-form-group">
                <label class="ra-form-label" for="email">Adresse email</label>
                <input class="ra-form-input" type="email" id="email" name="email"
                       value="{{ old('email') }}" autocomplete="email" required>
            </div>

            <div class="ra-form-group">
                <label class="ra-form-label" for="password">Mot de passe (8 caractères min.)</label>
                <input class="ra-form-input" type="password" id="password" name="password"
                       autocomplete="new-password" required minlength="8">
            </div>

            <div class="ra-form-group">
                <label class="ra-form-label" for="password_confirmation">Confirmer le mot de passe</label>
                <input class="ra-form-input" type="password" id="password_confirmation"
                       name="password_confirmation" autocomplete="new-password" required>
            </div>

            {{-- Newsletter (obligatoire) --}}
            <div class="ra-form-group">
                <label class="ra-checkbox-row">
                    <input type="checkbox" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }} required>
                    <span class="ra-checkbox-label">
                        J'accepte de <strong>m'abonner à la newsletter E-Benin</strong>
                        pour recevoir les dernières actualités du Bénin.
                        <em>(Obligatoire pour créer un compte lecteur)</em>
                    </span>
                </label>
            </div>

            <button type="submit" class="ra-btn-submit">Créer mon compte</button>
        </form>

        <div class="ra-auth__divider" style="margin-top:20px">Déjà un compte ?</div>
        <a href="{{ route('reader.login') }}" class="ra-auth__link">Se connecter</a>

    </div>

    <div class="ra-auth__footer">
        En créant un compte, vous acceptez nos conditions d'utilisation.
    </div>
</div>
</body>
</html>
