<!doctype html>
@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
@endphp
<html lang="fr" class="no-js">

<head>
    <title>Réinitialiser votre mot de passe</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="{{ asset('css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Style pour les notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-100px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification.success {
            background-color: #4CAF50;
        }

        .notification.error {
            background-color: #f44336;
        }

        /* Style amélioré pour le formulaire */
        .register-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 40px auto;
        }

        .register-box p {
            margin-bottom: 25px;
            color: #666;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        /* Animation de chargement */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
            vertical-align: middle;
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="boxed-style">
    <!-- Container -->
    <div id="container">
        <!-- Header ================================================== -->
        <header class="clearfix">
            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <a class="navbar-brand" href="https://{{ $baseDomain }}">
                                <img src="{{ asset('images/logo.png') }}" alt="">
                            </a>
                        </div>
                        <div class="col-sm-7">
                            <ul class="info-list right-align">
                                <li id="clock">
                                    <i class="fa fa-clock-o"></i>{{ now()->formatLocalized('%A %d.%m.%Y %H:%M:%S') }}
                                </li>
                                <script>
                                    function updateClock() {
                                        document.getElementById('clock').innerHTML = `<i class="fa fa-clock-o"></i>${new Date().toLocaleString('fr-FR', { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' })}`;
                                    }
                                    setInterval(updateClock, 1000);
                                </script>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->

        <!-- Notifications -->
        <div id="notification" class="notification"></div>

        <section id="content-section">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <!-- Forgot Password Box -->
                        <div class="register-box">
                            <div class="title-section">
                                <h1><span>Réinitialiser votre mot de passe</span></h1>
                            </div>
                            <p>Entrez votre adresse e-mail et nous vous enverrons un nouveau mot de passe temporaire.</p>
                            <form id="forgot-password-form">
                                <div class="form-group">
                                    <label for="email">Adresse e-mail</label>
                                    <input id="email" name="email" type="email" placeholder="Votre adresse e-mail" required>
                                </div>
                                <div id="form-actions">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="loading" id="loading-spinner"></span>
                                        <span id="button-text">Envoyer le nouveau mot de passe</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- End Forgot Password Box -->
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="up-footer">
                    <div class="row justify-content-between">
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-widget text-widget">
                                <h1><a href="index.html"><img src="{{ asset('images/logo.png') }}" alt=""></a></h1>
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
                    <p>&copy; Fait par <strong><a href="http://savplus.net">AGENCE WEB SAVOIR PLUS CONSEIL</a></strong> AGENCE WEB SAVOIR PLUS CONSEIL 2024<a href="#" class="go-top"><i class="fa fa-caret-up" aria-hidden="true"></i></a></p>
                </div>
            </div>
        </footer>
        <!-- End footer -->
    </div>
    <!-- End Container -->

    <script src="js/modernmag-plugins.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en"></script>
    <script src="js/gmap3.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgot-password-form');
            const loadingSpinner = document.getElementById('loading-spinner');
            const buttonText = document.getElementById('button-text');
            const submitButton = form.querySelector('button[type="submit"]');

            // Fonction pour afficher les notifications
            function showNotification(message, type) {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.className = 'notification ' + type;
                notification.classList.add('show');

                // Faire disparaître la notification après 5 secondes
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Empêche l'envoi traditionnel du formulaire

                const email = document.getElementById('email').value;

                // Afficher l'animation de chargement
                loadingSpinner.style.display = 'inline-block';
                buttonText.textContent = 'Envoi en cours...';
                submitButton.disabled = true;

                fetch('/bloger/forgot-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Masquer l'animation de chargement
                        loadingSpinner.style.display = 'none';

                        if (data.success) {
                            // En cas de succès
                            showNotification(data.message, 'success');
                            form.reset();

                            // Désactiver définitivement le bouton et changer son apparence
                            submitButton.disabled = true;
                            submitButton.classList.add('disabled');
                            buttonText.textContent = 'Nouveau mot de passe envoyé';
                            submitButton.style.opacity = '0.6';
                            submitButton.style.cursor = 'not-allowed';
                        } else {
                            // En cas d'erreur
                            showNotification(data.message, 'error');
                            submitButton.disabled = false;
                            buttonText.textContent = 'Envoyer le nouveau mot de passe';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        loadingSpinner.style.display = 'none';
                        buttonText.textContent = 'Envoyer le nouveau mot de passe';
                        submitButton.disabled = false;
                        showNotification('Une erreur est survenue lors de la communication avec le serveur. Veuillez réessayer.', 'error');
                    });
            });
        });
    </script>
</body>

</html>