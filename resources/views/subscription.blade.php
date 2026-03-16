<!doctype html>
<html lang="fr">
<head>
    <title>Renouveler votre Abonnement | E-Benin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{ asset('css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="boxed-style">
    <div id="container">

        <header class="clearfix">
            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <a class="navbar-brand" href="{{ str_contains(request()->getHost(), 'e-benin.bj') ? 'https://e-benin.bj' : 'https://e-benin.com' }}">
                                <img src="{{ asset('images/logo.png') }}" alt="E-Benin">
                            </a>
                        </div>
                        <div class="col-sm-7">
                            <ul class="info-list right-align">
                                <li><i class="fa fa-clock-o"></i><span id="clock-text"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="content-section">
            <div class="container">
                <div class="row">
                    <div class="col">

                        @if(session('error'))
                            <div id="alertMessage" class="btn btn-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div id="alertMessage" class="btn btn-success">{{ session('success') }}</div>
                        @endif

                        <div class="register-box">
                            <div class="title-section">
                                <h1><span>Votre abonnement E-Benin a expire</span></h1>
                                <p>Renouvelez votre abonnement pour acceder a votre espace.</p>
                            </div>

                            <form id="subscription-form">
                                <label for="months">Nombre de mois*</label>
                                <input id="months" name="quantity" type="number" min="1" value="1" required>
                                <p style="margin-top:10px; font-weight:bold;">
                                    Montant total : <span id="total-price">10 000</span> FCFA
                                </p>
                                <div id="kkiapay-container" style="margin-top:20px;"></div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="down-footer">
                    <p>&copy; <a href="http://savplus.net">AGENCE WEB SAVOIR PLUS CONSEIL</a> 2024</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.kkiapay.me/k.js"></script>
    <script src="{{ asset('js/modernmag-plugins.min.js') }}"></script>

    <script>
        // Horloge
        function updateClock() {
            document.getElementById('clock-text').textContent =
                new Date().toLocaleString('fr-FR', {
                    weekday: 'long', year: 'numeric', month: 'numeric',
                    day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'
                });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Alerte auto-hide
        document.addEventListener('DOMContentLoaded', function () {
            var alertEl = document.getElementById('alertMessage');
            if (alertEl) setTimeout(function() { alertEl.style.display = 'none'; }, 5000);
        });

        // Widget Kkiapay
        document.addEventListener('DOMContentLoaded', function () {
            var monthsInput  = document.getElementById('months');
            var totalPriceEl = document.getElementById('total-price');
            var container    = document.getElementById('kkiapay-container');

            // Calcul du domaine principal (retire le sous-domaine)
            // ex: "dannou.e-benin.com" -> base = "e-benin.com"
            var parts     = window.location.hostname.split('.');
            var baseDomain = parts.length > 2 ? parts.slice(1).join('.') : window.location.hostname;
            var baseUrl    = window.location.protocol + '//' + baseDomain;

            {{--
                POURQUOI on injecte le subdomain ici via Blade :
                - Le paiement se fait sur dannou.e-benin.com (sous-domaine)
                - Kkiapay redirige le callback vers e-benin.com/update-subscription (domaine principal)
                - Les sessions Laravel sont isolees par domaine -> Auth::user() = null cote principal
                - En passant &subdomain=dannou dans l'URL, le controller retrouve l'user via la BDD
            --}}
            @php
                if (Auth::check() && Auth::user()->organization) {
                    $sub = Auth::user()->organization->subdomain;
                } else {
                    $hostParts = explode('.', request()->getHost());
                    $sub = count($hostParts) > 2 ? $hostParts[0] : '';
                }
            @endphp
            var subdomain = "{{ $sub }}";

            function buildWidget() {
                var quantity    = Math.max(1, parseInt(monthsInput.value) || 1);
                var totalAmount = 10000 * quantity;

                totalPriceEl.textContent = totalAmount.toLocaleString('fr-FR');
                container.innerHTML = '';

                var callbackUrl = baseUrl + '/update-subscription'
                    + '?quantite=' + quantity
                    + '&subdomain=' + encodeURIComponent(subdomain);

                var widget = document.createElement('kkiapay-widget');
                widget.setAttribute('amount',   totalAmount.toString());
                widget.setAttribute('key',      'cb876650e192fdf79d12342d023a6f4ebe257de4');
                widget.setAttribute('position', 'center');
                widget.setAttribute('sandbox',  'false');
                widget.setAttribute('data',     JSON.stringify({
                    objet: 'renouvellement',
                    quantite: quantity,
                    subdomain: subdomain
                }));
                // subdomain dans l'URL -> le controller retrouve l'user sans session
                widget.setAttribute('callback', callbackUrl);

                container.appendChild(widget);
            }

            if (monthsInput) {
                monthsInput.addEventListener('input', buildWidget);
                buildWidget();
            }
        });
    </script>
</body>
</html>