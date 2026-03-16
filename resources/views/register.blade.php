<!doctype html>
<html lang="fr" class="no-js">
<head>
    <title>Création Blog | E-Bénin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{ asset('css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="boxed-style">

    @if(session('success'))
        <div id="alertMessage" class="btn btn-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div id="alertMessage" class="btn btn-danger">{{ session('error') }}</div>
    @endif

    <div id="container">

        <header class="clearfix">
            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <a class="navbar-brand" href="/">
                                <img src="{{ asset('images/ebenins.png') }}" class="img-fluid"
                                     height="120" width="120" alt="E-Bénin">
                            </a>
                        </div>
                        <div class="col-sm-7">
                            <ul class="info-list right-align">
                                <li id="clock">
                                    <i class="fa fa-clock-o"></i>
                                    <span id="clock-text"></span>
                                </li>
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
                        <div class="register-box">
                            <div class="title-section">
                                <h1><span>Formulaire d'Inscription</span></h1>
                            </div>

                            {{-- Ce form ne se soumet PAS directement, c'est le JS qui gère --}}
                            <form id="register-form" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name">Nom*</label>
                                        <input id="name" name="name" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">E-mail*</label>
                                        <input id="email" name="email" type="email" required>
                                    </div>
                                </div>

                                <label for="password">Mot de passe*</label>
                                <input id="password" name="password" type="password" required>

                                <label for="password_confirmation">Confirmer le mot de passe*</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="phone">Téléphone</label>
                                        <input id="phone" name="phone" type="text">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address">Adresse</label>
                                        <input id="address" name="address" type="text">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="organization_name">Nom de l'organisation*</label>
                                        <input id="organization_name" name="organization_name" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="organization_email">E-mail de l'organisation*</label>
                                        <input id="organization_email" name="organization_email" type="email" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="organization_address">Adresse de l'organisation</label>
                                        <input id="organization_address" name="organization_address" type="text">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="organization_phone">Téléphone de l'organisation</label>
                                        <input id="organization_phone" name="organization_phone" type="text">
                                    </div>
                                </div>

                                <div class="user-thumbnail">
                                    <input type="file" name="organization_logo" id="organization_logo" accept="image/*" />
                                    <span>Logo de l'organisation</span>
                                    <div class="image-preview" id="imagePreview">
                                        <img src="" alt="Logo Preview" class="image-preview__image" />
                                        <span class="image-preview__default-text">Votre Logo</span>
                                    </div>
                                </div>

                                {{-- Message d'erreur visible --}}
                                <div id="form-error" style="color:red; display:none; margin-top:10px;"></div>

                                <button id="kkiapay-button" type="button" class="kkiapay-button">
                                    Créer mon blog
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="up-footer">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-12">
                            <div class="footer-widget text-widget">
                                <h1>
                                    <a href="/">
                                        <img src="{{ asset('images/logo_blanc.png') }}" alt=""
                                             class="img-fluid" height="200" width="200">
                                    </a>
                                </h1>
                                <ul class="social-icons">
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="down-footer">
                    <p>By <strong><a href="https://savplus.net">SAVPLUS CONSEIL</a></strong> &copy; 2024</p>
                </div>
            </div>
        </footer>

    </div>

    <script src="https://cdn.kkiapay.me/k.js"></script>
    <script src="{{ asset('js/modernmag-plugins.min.js') }}"></script>

    <style>
        .user-thumbnail { display: flex; align-items: center; gap: 20px; margin-bottom: 15px; }
        .image-preview { width: 100px; height: 100px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #ccc; }
        .image-preview__image { display: none; width: 100%; height: 100%; object-fit: cover; }
        .image-preview__default-text { display: block; font-size: 12px; }
    </style>

    <script>
        // ── Horloge ───────────────────────────────────────────────
        function updateClock() {
            document.getElementById('clock-text').textContent =
                new Date().toLocaleString('fr-FR', {
                    weekday: 'long', year: 'numeric', month: 'numeric',
                    day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'
                });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Alerte auto-hide ──────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const alert = document.getElementById('alertMessage');
            if (alert) setTimeout(() => alert.style.display = 'none', 5000);
        });

        // ── Preview logo ──────────────────────────────────────────
        document.getElementById('organization_logo').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader      = new FileReader();
            const preview     = document.getElementById('imagePreview');
            const img         = preview.querySelector('.image-preview__image');
            const defaultText = preview.querySelector('.image-preview__default-text');
            defaultText.style.display = 'none';
            img.style.display         = 'block';
            reader.addEventListener('load', () => img.setAttribute('src', reader.result));
            reader.readAsDataURL(file);
        });

        // ── Bouton inscription + Kkiapay ──────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const btn       = document.getElementById('kkiapay-button');
            const form      = document.getElementById('register-form');
            const errorDiv  = document.getElementById('form-error');

            btn.addEventListener('click', function () {
                errorDiv.style.display = 'none';
                btn.disabled           = true;
                btn.textContent        = 'Traitement en cours...';

                const formData  = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('register') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ✅ callback dynamique — fonctionne sur .com ET .bj
                        const callbackUrl = window.location.origin + '/transaction/' + data.organization;

                        openKkiapayWidget({
                            amount:   data.amount,
                            callback: callbackUrl,
                            data:     JSON.stringify({ organization: data.organization }),
                            position: 'center',
                            theme:    '#cc6666',
                            sandbox:  false, // ✅ était "true" — mettre false en production
                            key:      'cb876650e192fdf79d12342d023a6f4ebe257de4'
                        });
                    } else {
                        // ✅ Afficher les erreurs de validation proprement
                        let message = data.message || 'Une erreur est survenue.';
                        if (data.errors) {
                            message = Object.values(data.errors).flat().join('<br>');
                        }
                        errorDiv.innerHTML     = message;
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erreur fetch:', error);
                    errorDiv.innerHTML     = 'Erreur de communication avec le serveur. Veuillez réessayer.';
                    errorDiv.style.display = 'block';
                })
                .finally(() => {
                    btn.disabled    = false;
                    btn.textContent = 'Créer mon blog';
                });
            });
        });
    </script>

</body>
</html>