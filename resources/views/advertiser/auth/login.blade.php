<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion annonceur | E-Benin</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <style>
        body { background: #f4f6fb; }
        .adv-auth-wrap { max-width: 440px; margin: 80px auto; padding: 0 16px; }
        .adv-auth-card { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 2px 16px rgba(0,0,0,.08); }
        .adv-auth-card h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 6px; color: #0d1b2a; }
        .adv-auth-card .subtitle { color: #666; margin-bottom: 28px; font-size: .95rem; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: .88rem; color: #333; }
        .form-group input[type=email],
        .form-group input[type=password] { width: 100%; padding: 10px 14px; border: 1px solid #dde1e9; border-radius: 8px; font-size: .95rem; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #003f7f; box-shadow: 0 0 0 3px rgba(0,63,127,.1); }
        .btn-primary { width: 100%; padding: 12px; background: #003f7f; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-primary:hover { background: #002d5c; }
        .error-list { background: #fdecea; color: #b71c1c; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .88rem; }
        .register-link { text-align: center; margin-top: 18px; font-size: .9rem; color: #555; }
        .register-link a { color: #003f7f; font-weight: 600; }
        .logo { display: block; margin: 0 auto 32px; text-align: center; }
        .logo img { height: 40px; }
        .success-msg { background: #e8f5e9; color: #2e7d32; border-radius: 8px; padding: 10px 14px; margin-bottom: 20px; font-size: .88rem; }
    </style>
</head>
<body>
    <div class="adv-auth-wrap">
        <div class="logo">
            <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
                <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
            </a>
        </div>

        <div class="adv-auth-card">
            <h1>Espace annonceur</h1>
            <p class="subtitle">Connectez-vous pour gérer vos annonces et nécrologies.</p>

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-list">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('advertiser.login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <button type="submit" class="btn-primary">Se connecter</button>
            </form>

            <div class="register-link">
                Pas encore de compte ? <a href="{{ route('advertiser.register') }}">Créer un compte</a>
            </div>
        </div>
    </div>
</body>
</html>
