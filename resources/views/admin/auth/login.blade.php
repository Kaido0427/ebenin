<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion Admin | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/admin-panel.css') }}">
</head>
<body data-theme="light">
    <div class="auth-v2">
        <header class="auth-v2__topbar">
            <div class="auth-v2__brand">Tableau de bord E-Benin</div>
            <nav class="auth-v2__menu">
                <span>Tableau de bord</span>
                <span>Profil</span>
                <span>Se connecter</span>
            </nav>
            <div class="auth-v2__cta">Acces admin</div>
        </header>

        <section class="auth-v2__content">
            <div class="auth-v2__left">
                <h1>Content de te revoir</h1>
                <p>Saisissez votre adresse e-mail et votre mot de passe pour vous connecter au backoffice.</p>

                @if($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form class="auth-form" method="POST" action="{{ route('admin.login.store') }}">
                    @csrf
                    <div class="field">
                        <label for="email">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <input id="password" name="password" type="password" required>
                    </div>
                    <label class="remember-row remember-row--v2" for="remember">
                        <input id="remember" type="checkbox" name="remember" value="1">
                        <span>Souviens-toi de moi</span>
                    </label>
                    <button type="submit" class="primary-btn btn-block">Se connecter</button>
                </form>

                <div class="admin-note">
                    Creation de compte admin non publique. Les comptes sont geres uniquement par super administrateur.
                </div>
            </div>

            <div class="auth-v2__right">
                <div class="auth-v2__visual"></div>
            </div>
        </section>

        <footer class="auth-v2__footer">
            <span>Entreprise</span>
            <span>A propos</span>
            <span>Equipe</span>
            <span>Produit</span>
            <span>Blog</span>
        </footer>
    </div>
</body>
</html>
