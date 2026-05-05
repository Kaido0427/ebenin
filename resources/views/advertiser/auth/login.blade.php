<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion annonceur | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<section class="auth-shell">
    <div class="container">
        <div class="auth-page">
            <div class="auth-visual">
                <div class="auth-visual__logo">
                    <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
                        <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" class="logo__img--light">
                    </a>
                </div>

                <div class="auth-visual__body">
                    <div class="auth-visual__tag">Espace Annonceur</div>
                    <h1 class="auth-visual__title">Gérez vos annonces et notices de décès sur E-Benin.</h1>
                    <p class="auth-visual__desc">
                        Connectez-vous à votre espace annonceur pour publier, modifier et suivre vos publications.
                    </p>
                    <div class="auth-visual__stats">
                        <div class="auth-stat">
                            <div class="auth-stat__val">4</div>
                            <div class="auth-stat__label">Catégories</div>
                        </div>
                        <div class="auth-stat">
                            <div class="auth-stat__val">24/7</div>
                            <div class="auth-stat__label">Publication</div>
                        </div>
                        <div class="auth-stat">
                            <div class="auth-stat__val">∞</div>
                            <div class="auth-stat__label">Annonces</div>
                        </div>
                    </div>
                </div>

                <div class="auth-visual__footer">
                    <div class="auth-visual__quote">
                        <p>Votre espace personnel pour toucher votre audience au Bénin.</p>
                        <footer>E-Benin · Espace Annonceur</footer>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-wrap">
                    <div class="auth-form-wrap__head">
                        <h1>Connexion annonceur</h1>
                        <p>Accédez à votre espace de gestion.</p>
                    </div>

                    @if (session('success'))
                        <div class="auth-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="auth-error">
                            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                        </div>
                    @endif

                    <div class="auth-card">
                        <form method="POST" action="{{ route('advertiser.login') }}">
                            @csrf

                            <div class="form-group">
                                <label>Adresse e-mail</label>
                                <input name="email" type="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input name="password" type="password" required>
                            </div>

                            <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:8px;">
                                Se connecter
                            </button>
                        </form>
                    </div>

                    <p class="auth-form-note">
                        Pas encore de compte ? <a href="{{ route('advertiser.register') }}">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
