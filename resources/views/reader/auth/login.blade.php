<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#003f7f">
    <title>Connexion — E-Benin App</title>
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
        <div class="ra-auth__title">Connexion</div>
    </div>

    <div class="ra-auth__body">

        @if($errors->any())
            <div class="ra-alert ra-alert--error" style="margin-bottom:16px">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('reader.login.post') }}" class="ra-auth__form">
            @csrf
            <div class="ra-form-group">
                <label class="ra-form-label" for="email">Adresse email</label>
                <input class="ra-form-input" type="email" id="email" name="email"
                       value="{{ old('email') }}" autocomplete="email" required autofocus>
            </div>
            <div class="ra-form-group">
                <label class="ra-form-label" for="password">Mot de passe</label>
                <input class="ra-form-input" type="password" id="password" name="password"
                       autocomplete="current-password" required>
            </div>
            <button type="submit" class="ra-btn-submit">Se connecter</button>
        </form>

        <div class="ra-auth__divider" style="margin-top:20px">Pas encore de compte ?</div>
        <a href="{{ route('reader.register') }}" class="ra-auth__link">Créer un compte lecteur</a>

    </div>

    <div class="ra-auth__footer">
        Vous êtes blogueur ou annonceur ? Utilisez vos identifiants habituels.
    </div>
</div>
</body>
</html>
