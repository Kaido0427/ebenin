<!doctype html>


<html lang="en" class="no-js">

<head>
    <title>Politique de confidentialité|E-Bénin</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="{{ asset('css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="boxed-style">

    <!-- Container -->
    <div id="container">
        <!-- Header
  ================================================== -->
        <header class="clearfix">

            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <a class="navbar-brand" href="https://e-benin.com">
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
                                        // Mettre à jour l'élément #clock avec la date et l'heure actuelles
                                        document.getElementById('clock').innerHTML = `
											<i class="fa fa-clock-o"></i>${new Date().toLocaleString('fr-FR', { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' })}
										`;
                                    }

                                    // Mettre à jour l'horloge toutes les secondes
                                    setInterval(updateClock, 1000);
                                </script>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </header>
        <!-- End Header -->
        <section id="content-section">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <!-- Privacy Policy Section -->
                        <div class="policy-box">
                            <div class="title-section">
                                <h1><span>Politique de Confidentialité de e-benin</span></h1>
                                <p><strong>Dernière mise à jour : 17 janvier 2025</strong></p>
                            </div>
                            <div class="content-section">
                                <h2>1. Introduction</h2>
                                <p>Bienvenue sur e-benin, une plateforme de blog multi-utilisateurs dédiée aux journalistes. Cette politique de confidentialité décrit comment nous collectons, utilisons et protégeons vos informations personnelles.</p>

                                <h2>2. Informations que nous collectons</h2>
                                <h3>2.1 Informations des journalistes</h3>
                                <ul>
                                    <li>Nom et prénom</li>
                                    <li>Adresse email professionnelle</li>
                                    <li>Informations de profil professionnel</li>
                                    <li>Contenu publié (articles, photos, liens et réseaux sociaux)</li>
                                    <li>Historique des publications</li>
                                </ul>
                                <h3>2.2 Informations des lecteurs</h3>
                                <ul>
                                    <li>Adresse IP</li>
                                    <li>Commentaires (si cette fonctionnalité est activée)</li>
                                </ul>

                                <h2>3. Utilisation des informations</h2>
                                <h3>3.1 Pour les journalistes et contributeurs</h3>
                                <p>Nous utilisons vos informations pour :</p>
                                <ul>
                                    <li>Vérifier votre identité et vos qualifications professionnelles en tant que journaliste</li>
                                    <li>Créer et gérer votre espace personnel sur la plateforme</li>
                                    <li>Permettre la publication et la modification de vos articles</li>
                                    <li>Protéger vos droits d'auteur et la propriété intellectuelle de votre contenu</li>
                                    <li>Vous informer des interactions sur vos articles (commentaires, partages)</li>
                                </ul>
                                <h3>3.2 Pour la plateforme</h3>
                                <p>Nous utilisons les données pour :</p>
                                <ul>
                                    <li>Assurer la sécurité et l'intégrité de la plateforme</li>
                                    <li>Détecter et prévenir les activités frauduleuses</li>
                                    <li>Protéger les sources journalistiques conformément à la déontologie</li>
                                    <li>Générer des statistiques anonymes sur l'utilisation</li>
                                    <li>Optimiser les performances techniques</li>
                                    <li>Se conformer aux obligations légales et réglementaires</li>
                                    <li>Résoudre les conflits éventuels</li>
                                    <li>Répondre aux demandes des autorités compétentes dans le cadre légal</li>
                                </ul>

                                <h2>4. Protection des données</h2>
                                <p>Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos informations :</p>
                                <ul>
                                    <li>Chiffrement des données sensibles</li>
                                    <li>Accès restreint aux données personnelles</li>
                                    <li>Sauvegardes régulières</li>
                                    <li>Surveillance continue de la sécurité</li>
                                </ul>

                                <h2>5. Partage des informations</h2>
                                <p>Nous ne partageons vos informations qu'avec :</p>
                                <ul>
                                    <li>Nos prestataires de services techniques (hébergement, maintenance)</li>
                                    <li>Les autorités légales si requis par la loi</li>
                                </ul>

                                <h2>6. Vos droits</h2>
                                <p>En tant qu'utilisateur, vous avez le droit de :</p>
                                <ul>
                                    <li>Accéder à vos données personnelles</li>
                                    <li>Rectifier vos informations</li>
                                    <li>Limiter le traitement de vos données</li>
                                </ul>

                                <h2>7. Conservation des données</h2>
                                <p>Nous conservons vos données :</p>
                                <ul>
                                    <li>Tant que votre compte est actif</li>
                                    <li>Selon les obligations légales applicables</li>
                                    <li>Pour une durée nécessaire à nos besoins légitimes</li>
                                </ul>

                                <h2>8. Cookies</h2>
                                <p>Nous utilisons des cookies pour :</p>
                                <ul>
                                    <li>Maintenir votre session</li>
                                    <li>Améliorer la navigation</li>
                                </ul>

                                <h2>9. Contact</h2>
                                <p>Pour toute question concernant cette politique ou vos données personnelles :</p>
                                <ul>
                                    <li>Téléphone : (+229) 20 21 37 59</li>
                                    <li>Téléphone : (+229) 69 41 66 66</li>
                                    <li>Email : contact@savplus.net</li>
                                    <li>Adresse : 08 BP 0053 Cotonou, Bénin</li>
                                    <li>Godomey, après la Boulangerie Saint Daniel</li>
                                    <li>Akpakpa, au carrefour La Roche, Rue de NSI</li>
                                </ul>

                                <h2>10. Modifications</h2>
                                <p>Nous nous réservons le droit de modifier cette politique. Les utilisateurs seront informés des changements importants par email ou notification sur la plateforme.</p>
                            </div>
                        </div>
                        <!-- End Privacy Policy Section -->
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
                                <h1><a href="index.html"><img src="{{ asset('images/logo.png') }}" alt=""></a>
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

                    <p>&copy; Fait par <strong> <a href="http://savplus.net">AGENCE WEB SAVOIR PLUS CONSEIL</a>
                        </strong> AGENCE WEB SAVOIR PLUS CONSEIL 2024<a href="#" class="go-top"><i
                                class="fa fa-caret-up" aria-hidden="true"></i></a></p>
                </div>

            </div>
        </footer>
        <!-- End footer -->

    </div>
    <!-- End Container -->


    <script src="js/modernmag-plugins.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script
        src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en">
    </script>
    <script src="js/gmap3.min.js"></script>
    <script src="js/script.js"></script>

</body>

</html>