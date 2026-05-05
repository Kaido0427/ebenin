<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renouveler mon abonnement | E-Benin Annonceur</title>
    <link rel="stylesheet" href="{{ asset('css/refonte-public.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sub-shell { min-height: 100vh; background: var(--bg); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
        .sub-logo { margin-bottom: 28px; }
        .sub-logo img { height: 38px; }
        .sub-card { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); padding: 40px; max-width: 460px; width: 100%; text-align: center; box-shadow: var(--shadow); }
        .sub-card .icon { font-size: 2.8rem; margin-bottom: 14px; }
        .sub-card h1 { font-size: 1.4rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .sub-card p { color: var(--mid); margin-bottom: 24px; font-size: .93rem; }
        .sub-price { background: rgba(0,63,127,.06); border: 1px solid rgba(0,63,127,.12); border-radius: var(--radius); padding: 16px; margin-bottom: 24px; }
        .sub-price .amount { font-size: 2rem; font-weight: 700; color: var(--primary); }
        .sub-price .label { font-size: .85rem; color: var(--muted); margin-top: 2px; }
        .sub-footer { margin-top: 16px; font-size: .85rem; }
    </style>
</head>
<body>
<div class="sub-shell">
    <div class="sub-logo">
        <a href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
            <img src="{{ asset('images/ebenins.png') }}" alt="E-Benin">
        </a>
    </div>

    <div class="sub-card">
        <div class="icon">⏳</div>
        <h1>Votre période d'essai est terminée</h1>
        <p>Abonnez-vous pour continuer à publier vos annonces et nécrologies sur E-Benin.</p>

        @if (session('warning'))
            <div class="alert alert--warning" style="margin-bottom:20px;">{{ session('warning') }}</div>
        @endif

        <div class="sub-price">
            <div class="amount">10 000 FCFA</div>
            <div class="label">par semaine · accès complet</div>
        </div>

        <button id="pay-btn" class="btn btn--primary" style="width:100%;justify-content:center;padding:14px;">
            Payer avec Kkiapay
        </button>

        <div class="sub-footer">
            <form method="POST" action="{{ route('advertiser.logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.85rem;">
                    Se déconnecter
                </button>
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
