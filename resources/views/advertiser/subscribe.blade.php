<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renouveler mon abonnement | E-Benin Annonceur</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: #f4f6fb; font-family: 'Inter', sans-serif; }
        .wrap { max-width: 500px; margin: 80px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; padding: 40px; box-shadow: 0 2px 16px rgba(0,0,0,.08); text-align: center; }
        .card .icon { font-size: 3rem; margin-bottom: 16px; }
        .card h1 { font-size: 1.5rem; font-weight: 700; color: #0d1b2a; margin-bottom: 8px; }
        .card p { color: #666; margin-bottom: 28px; }
        .price-box { background: #eef3fb; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
        .price-box .amount { font-size: 2rem; font-weight: 700; color: #003f7f; }
        .price-box .label { font-size: .85rem; color: #666; }
        .warning { background: #fff3cd; color: #856404; border-radius: 8px; padding: 12px; margin-bottom: 20px; font-size: .88rem; }
        .btn-pay { width: 100%; padding: 14px; background: #003f7f; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn-pay:hover { background: #002d5c; }
        .logout-link { margin-top: 16px; font-size: .85rem; }
        .logout-link a { color: #999; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrap">
    <div style="text-align:center;margin-bottom:24px;">
        <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
            <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin" style="height:36px;">
        </a>
    </div>

    <div class="card">
        <div class="icon">⏳</div>
        <h1>Votre période d'essai est terminée</h1>
        <p>Abonnez-vous pour continuer à publier vos annonces et nécrologies sur E-Benin.</p>

        @if (session('warning'))
            <div class="warning">{{ session('warning') }}</div>
        @endif

        <div class="price-box">
            <div class="amount">10 000 FCFA</div>
            <div class="label">par semaine · accès complet</div>
        </div>

        <button id="pay-btn" class="btn-pay">Payer avec Kkiapay</button>

        <div class="logout-link">
            <form method="POST" action="{{ route('advertiser.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:#999;cursor:pointer;font-size:.85rem;">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    openKkiapayWidget({
        amount: 10000,
        callback: window.location.origin + '/advertiser/payment/callback',
        position: 'center',
        theme: '#003f7f',
        sandbox: false,
        key: 'cb876650e192fdf79d12342d023a6f4ebe257de4'
    });
});
</script>
</body>
</html>
