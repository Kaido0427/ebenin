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
            <a href="{{ url('/') }}" class="auth-v2__brand auth-v2__brand--link">
                <span class="auth-v2__brand-mark">EB</span>
                <span>Tableau de bord E-Benin</span>
            </a>
            <nav class="auth-v2__menu">
                <a href="{{ url('/') }}" class="auth-v2__menu-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M3 11.5L12 4l9 7.5" />
                        <path d="M5 10.5V20h14v-9.5" />
                    </svg>
                    <span>Accueil</span>
                </a>
                <a href="{{ url('/admin/login') }}" class="auth-v2__menu-link is-active">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M10 17l5-5-5-5" />
                        <path d="M15 12H3" />
                        <path d="M21 19V5a2 2 0 0 0-2-2h-7" />
                    </svg>
                    <span>Connexion</span>
                </a>
                <a href="{{ url('/politique') }}" class="auth-v2__menu-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                        <path d="M6 3h12a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 0 1 2-2z" />
                    </svg>
                    <span>Politique</span>
                </a>
            </nav>
            <a href="{{ url('/') }}" class="auth-v2__cta">Retour au site</a>
        </header>

        <section class="auth-v2__content">
            <div class="auth-v2__left">
                <h1>Content de te revoir</h1>
                <p>Saisissez votre adresse e-mail et votre mot de passe pour vous connecter au backoffice.</p>

                @if($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form class="auth-form" method="POST" action="/admin/login">
                    @csrf
                    <div class="field">
                        <label for="email">E-mail</label>
                        <div class="field-icon-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <path d="M4 6h16v12H4z" />
                                <path d="M4 8l8 6 8-6" />
                            </svg>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="password">Mot de passe</label>
                        <div class="field-icon-wrap">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                                <rect x="4" y="11" width="16" height="9" rx="2" />
                                <path d="M8 11V8a4 4 0 1 1 8 0v3" />
                            </svg>
                            <input id="password" name="password" type="password" required>
                        </div>
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
            <a href="{{ url('/') }}">Accueil</a>
            <a href="{{ url('/annonces') }}">Annonces</a>
            <a href="{{ url('/necrologies') }}">Nécrologies</a>
            <a href="{{ url('/politique') }}">Politique</a>
            <a href="{{ url('/admin/login') }}">Admin</a>
        </footer>
    </div>
</body>
</html>
