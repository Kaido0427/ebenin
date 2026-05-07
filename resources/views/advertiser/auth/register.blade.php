<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte annonceur | E-Benin</title>
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
                    <h1 class="auth-visual__title">Publiez vos annonces et notices de décès sur E-Benin.</h1>
                    <p class="auth-visual__desc">
                        Atteignez des milliers de lecteurs au Bénin. Emploi, immobilier, services, évènements et nécrologies — tout en un seul espace.
                    </p>
                    <div class="auth-visual__stats">
                        <div class="auth-stat">
                            <div class="auth-stat__val">3 j</div>
                            <div class="auth-stat__label">Essai gratuit</div>
                        </div>
                        <div class="auth-stat">
                            <div class="auth-stat__val">10K</div>
                            <div class="auth-stat__label">FCFA / semaine</div>
                        </div>
                        <div class="auth-stat">
                            <div class="auth-stat__val">0 F</div>
                            <div class="auth-stat__label">À l'inscription</div>
                        </div>
                    </div>
                </div>

                <div class="auth-visual__footer">
                    <div class="auth-visual__quote">
                        <p>Inscription gratuite · 3 jours d'essai · Aucun paiement requis pour démarrer.</p>
                        <footer>E-Benin · Espace Annonceur</footer>
                    </div>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-form-wrap">
                    <div class="auth-form-wrap__head">
                        <h1>Créer un compte annonceur</h1>
                        <p>Rejoignez E-Benin et commencez à publier gratuitement.</p>
                    </div>

                    @if ($errors->any())
                        <div class="auth-error">
                            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                        </div>
                    @endif

                    <div class="auth-card">
                        <form method="POST" action="{{ route('advertiser.register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="auth-grid">
                                <div class="form-group">
                                    <label>Nom complet *</label>
                                    <input name="name" type="text" value="{{ old('name') }}" required placeholder="Votre nom">
                                </div>
                                <div class="form-group">
                                    <label>Nom de l'entreprise</label>
                                    <input name="company_name" type="text" value="{{ old('company_name') }}" placeholder="Optionnel">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Adresse e-mail *</label>
                                <input name="email" type="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="auth-grid">
                                <div class="form-group">
                                    <label>Mot de passe *</label>
                                    <input name="password" type="password" required>
                                </div>
                                <div class="form-group">
                                    <label>Confirmation *</label>
                                    <input name="password_confirmation" type="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Téléphone</label>
                                <input name="phone" type="text" value="{{ old('phone') }}">
                            </div>

                            <div class="form-group">
                                <label>Logo (optionnel)</label>
                                <input name="logo" type="file" accept="image/*">
                            </div>

                            <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:8px;">
                                Créer mon compte gratuitement
                            </button>
                        </form>
                    </div>

                    <p class="auth-form-note">
                        Déjà un compte ? <a href="{{ route('advertiser.login') }}">Se connecter</a>
                    </p>
                    <p class="auth-form-note" style="margin-top:6px;">
                        <a href="{{ route('info.annonces') }}" style="color:var(--primary);">En savoir plus sur les annonces E-Benin →</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
