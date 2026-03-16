<!doctype html>


<html lang="en" class="no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>E-Benin | Actualités, Politique, Économie, Culture et Société au Bénin</title>

    <!-- Description meta -->
    <meta name="description"
        content="E-Benin : Premier réseau de blogs d'actualités au Bénin. Suivez toute l'actualité politique, économique, culturelle, sociale, et les nouvelles tendances du Bénin. Analyses, reportages, opinions d'experts et journalistes locaux sur les événements, la politique, l'économie, l'éducation, la santé, l'environnement, les entreprises et bien plus.">

    <!-- Keywords meta -->
    <meta name="keywords"
        content="Bénin, actualités Bénin, politique Bénin, économie Bénin, culture Bénin, société Bénin, Cotonou, blogs d'actualité, nécrologie Bénin, événements Bénin, analyses Bénin, reportages Bénin, droits de l'homme, sécurité Bénin, développement économique Bénin, innovation, éducation Bénin, santé Bénin, infrastructures Bénin, tourisme Bénin, agriculture Bénin, investissements, législation Bénin, droits civiques, culture béninoise, patrimoine béninois, entreprises béninoises, startups Bénin, commerce Bénin, technologies Bénin, gouvernance, élections Bénin, politiques publiques Bénin, médias Bénin, presse, débats Bénin, justice Bénin, économie numérique, sociétés béninoises, environnement Bénin, tendances économiques, tendances sociales, avenir du Bénin, évolution du Bénin, perspectives économiques, réformes politiques Bénin, culture et arts, patrimoine historique Bénin, développement durable Bénin">

    <!-- Balises Open Graph -->
    <meta property="og:title" content="E-Benin | L'actualité du Bénin en temps réel">
    <meta property="og:description"
        content="Actualités, analyses et reportages sur la politique, l'économie, la culture et la société au Bénin. Le premier réseau de blogs d'information béninois offrant des perspectives uniques sur l'avenir du pays et la vie quotidienne au Bénin.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://e-benin.bj">
    <meta property="og:image" content="https://e-benin.bj/images/e-benin.png">
    <meta property="og:site_name" content="E-Benin">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:updated_time" content="{{ now()->toAtomString() }}">
    <meta property="og:country-name" content="Bénin">
    <meta property="og:region" content="Bénin">
    <meta property="og:timezone" content="Africa/Porto-Novo">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Balises Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="E-Benin | L'actualité du Bénin en un clic">
    <meta name="twitter:description"
        content="Suivez l'actualité béninoise : politique, économie, culture, société. Reportages exclusifs et analyses approfondies sur les défis, opportunités et réalités du Bénin, apportées par nos journalistes.">
    <meta name="twitter:image" content="https://e-benin.bj/images/e-benin.png">

    <!-- Balises supplémentaires -->
    <link rel="canonical" href="https://e-benin.bj">
    <meta name="robots" content="index, follow">
    <meta name="author" content="E-Benin">
    <meta name="copyright" content="E-Benin, Tous droits réservés">
    <meta name="language" content="fr">
    <meta name="rating" content="general">

    <!-- Feuilles de style -->
    <link rel="stylesheet" href="css/modernmag-assets.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://e-benin.bj/favicon.ico">
</head>



<body class="boxed-style">

    <!-- Container -->
    <div id="container">

        <header class="clearfix">
            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-5">
                            <a class="navbar-brand" href="/">
                                <img src="{{ asset('images/ebenins.png') }}" class="img-fluid" height="90" width="90"
                                    alt="Logo">
                            </a>
                        </div>
                        <div class="col-sm-7">
                            {{-- <form class="form-inline">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search for..."
                                    aria-label="Search">
                                <button class="btn btn-primary my-2 my-sm-0" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </form> --}}
                            <ul class="info-list right-align">
                                <li id="clock">
                                    <i class="fa fa-clock-o"></i>{{ now()->formatLocalized('%A %d.%m.%Y %H:%M:%S') }}
                                </li>
                                <script>
                                    function updateClock() {
                                        document.getElementById('clock').innerHTML = `
                                            <i class="fa fa-clock-o"></i>${new Date().toLocaleString('fr-FR', { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' })}
                                        `;
                                    }
                                    setInterval(updateClock, 1000);
                                </script>
                                @guest
                                <!-- Section pour les utilisateurs non connectés -->
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#loginModal"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path fill-rule="evenodd"
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('userRegister') }}"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor" class="bi bi-card-checklist"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                                            <path
                                                d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                                        </svg></a>
                                </li>
                                @else
                                <!-- Section pour les utilisateurs connectés -->
                                @php
                                $user = auth()->user();
                                $subdomain = $user->organization->subdomain;
                                $host = request()->getHost();
                                $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
                                @endphp

                                <li>
                                    <a href="https://{{ $subdomain }}.{{ $baseDomain }}/dashboard">Tableau de bord</a>
                                </li>


                                <li>
                                    <a href="{{ route('logOut') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                                    <form id="logout-form" action="{{ route('logOut') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                                @endguest

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @php
            // Vous pouvez toujours déterminer le domaine si nécessaire
            $host = request()->getHost();
            $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
            @endphp

            <!-- Styles personnalisés pour la navigation -->
            <style>
                .navbar-nav .nav-item {
                    margin: 0 5px;
                    white-space: nowrap;
                }

                .navbar-nav .nav-link {
                    font-size: 1rem;
                    color: #fff;
                }

                .navbar-nav .nav-link:hover {
                    color: #ccc;
                }

                /* Styles pour le dropdown "Plus" */
                .dropdown-menu {
                    background-color: #343a40;
                }

                .dropdown-item {
                    color: #fff;
                }

                .dropdown-item:hover {
                    background-color: #495057;
                }

                /* Cacher le "Plus" sur mobile */
                @media (max-width: 768px) {
                    #plusDropdown {
                        display: none !important;
                    }
                }
            </style>

            <!-- Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav mx-auto">
                            <!-- Afficher les 8 premières rubriques -->
                            @foreach($rubriques as $key => $rubrique)
                            @if($key < 8)
                                <li class="nav-item">
                                <a class="nav-link" href="{{ route('categories', ['id' => $rubrique->id]) }}">
                                    {{ $rubrique->name }}
                                </a>
                                </li>
                                @endif
                                @endforeach

                                <!-- Dropdown "Plus" -->
                                @if(count($rubriques) > 8)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                        Plus
                                    </a>
                                    <div class="dropdown-menu">
                                        @foreach($rubriques as $key => $rubrique)
                                        @if($key >= 8)
                                        <a class="dropdown-item" href="{{ route('categories', ['id' => $rubrique->id]) }}">
                                            {{ $rubrique->name }}
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </li>
                                @endif
                        </ul>
                    </div>
                </div>
            </nav>




        </header>



        <style>
            .marquee-container {
                width: 100%;
                overflow: hidden;
                background-color: #f1f1f1;
                padding: 7px 0;
                position: relative;
            }

            .marquee {
                display: flex;
                white-space: nowrap;
                animation: marquee linear infinite;
            }

            .marquee-item {
                margin-right: 50px;
                font-size: 0.8rem;
                color: #333;
                text-decoration: none;
            }

            @keyframes marquee {
                from {
                    transform: translateX(100%);
                }

                to {
                    transform: translateX(-100%);
                }
            }

            .marquee-item:hover {
                color: #007bff;
                text-decoration: underline;
            }
        </style>

        <div class="marquee-container">
            <div class="marquee">
                @foreach ($flashNews as $flash)
                <a href="{{ route('single-post', ['id' => $flash->id, 'organization' => $flash->user->organization->subdomain]) }}"
                    class="marquee-item">{{ $flash->libelle }}</a>
                @endforeach
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const marquee = document.querySelector('.marquee');
                const marqueeWidth = marquee.scrollWidth;

                // Durée fixe de l'animation pour assurer une vitesse constante sur tous les écrans
                const fixedDuration = marqueeWidth * 0.01; // Ajustez le facteur 0.05 selon la vitesse souhaitée

                marquee.style.animationDuration = `${fixedDuration}s`;
            });

            // Recalculer la durée de l'animation en cas de redimensionnement de la fenêtre
            window.addEventListener('resize', function() {
                const marquee = document.querySelector('.marquee');
                const marqueeWidth = marquee.scrollWidth;

                const fixedDuration = marqueeWidth * 0.01;

                marquee.style.animationDuration = `${fixedDuration}s`;
            });
        </script>
        <br><br>
        @php
        use Carbon\Carbon;
        $now = Carbon::now();
        $createdAt = $pub ? new Carbon($pub->created_at) : null;
        $isValid = $createdAt ? $now->diffInMilliseconds($createdAt) <= 7 * 24 * 60 * 60 * 1000 : false;
            @endphp


            <div class="advertisement-container">
            @if ($pub && $isValid)
            <div class="advertisement">
                <a href="{{ $pub->url }}" class="advertisement-link">
                    <img src="{{ asset($pub->image) }}" alt="Advertisement">
                </a>
            </div>
            @else
            @endif
    </div>


    <style>
        /* Conteneur de la publicité */
        .advertisement-container {
            display: flex;
            justify-content: flex-start;
            /* Aligne le contenu à gauche pour les mobiles */
            margin: 20px 0;
            /* Espacement vertical pour séparer des autres éléments */
            overflow-x: auto;
            /* Permet le défilement horizontal */
            padding: 0;
            /* Pas de marge intérieure supplémentaire */
            position: relative;
            /* Positionnement relatif pour un meilleur contrôle */
            width: 100%;
            /* Prend toute la largeur disponible */
            max-width: 100vw;
            /* Limite la largeur maximale au viewport */
        }

        .advertisement {
            position: relative;
            display: inline-block;
            border-radius: 8px;
            /* Coins arrondis pour une meilleure apparence */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Ombre légère pour un effet de profondeur */
            height: 150px;
            /* Hauteur standard */
            min-width: 300px;
            /* Largeur minimale pour éviter qu'elle ne disparaisse */
            flex: 0 0 auto;
            /* Évite que l'élément se rétrécisse */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        /* Effet de survol */
        .advertisement:hover {
            transform: scale(1.05);
            /* Agrandissement léger au survol */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Ombre plus marquée au survol */
        }

        /* Lien de la publicité */
        .advertisement-link {
            display: block;
            height: 100%;
            /* Remplit la hauteur du conteneur */
            width: 100%;
            /* Remplit la largeur du conteneur */
        }

        /* Image de la publicité */
        .advertisement img {
            height: 100%;
            /* Remplit la hauteur du conteneur */
            width: auto;
            /* Conserve les proportions de l'image */
            max-width: none;
            /* Permet à l'image de dépasser la largeur du conteneur */
            object-fit: cover;
            /* Maintient les proportions et remplit le conteneur */
            border-radius: 8px;
            /* Coins arrondis pour l'image */
        }

        /* Effet de survol sur l'image */
        .advertisement-link:hover img {
            opacity: 0.8;
            /* Légère transparence au survol */
        }

        /* Styles pour les écrans mobiles */
        @media (max-width: 768px) {
            .advertisement-container {
                justify-content: flex-start;
                /* Aligne le contenu à gauche pour les mobiles */
                padding: 0;
                /* Pas de marge supplémentaire sur mobile */
                width: 100vw;
                /* Prend toute la largeur de la fenêtre */
            }

            .advertisement {
                min-width: 300px;
                /* Largeur minimale pour la publicité */
                flex: 0 0 auto;
                /* Évite que l'élément se rétrécisse */
            }
        }

        /* Styles pour les écrans plus larges (PC et tablettes) */
        @media (min-width: 769px) {
            .advertisement-container {
                justify-content: center;
                /* Centre le conteneur sur les grands écrans */
            }
        }
    </style>

    <section id="content-section">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">

                    <!-- Posts-block -->
                    <div class="posts-block standard-box">
                        <div class="title-section">
                            <h1>Dernières Nouvelles</h1>
                        </div>
                        <div class="row">
                            @foreach ($latestPosts as $post)
                            <div class="col-sm-6">
                                <div class="news-post standart-post">
                                    <style>
                                        .post-image {
                                            /* Conteneur de l'image */
                                            width: 100%;
                                            /* Vous pouvez ajuster la largeur en fonction de vos besoins */
                                            height: 250px;
                                            /* Hauteur fixe pour toutes les images */
                                            overflow: hidden;
                                            /* Masquer tout ce qui dépasse du conteneur */
                                            position: relative;
                                        }

                                        .post-image img {
                                            width: 100%;
                                            /* Ajuste la largeur de l'image au conteneur */
                                            height: 100%;
                                            /* Ajuste la hauteur de l'image au conteneur */
                                            object-fit: cover;
                                            /* Recadre l'image pour qu'elle remplisse le conteneur tout en gardant l'aspect ratio */
                                            object-position: center;
                                            /* Centrer l'image dans le conteneur */


                                        }

                                        h6 a {
                                            color: #000000;
                                        }

                                        h6 a:hover {
                                            color: #0f1b5f;
                                        }
                                    </style>
                                    <div class="post-image">
                                        <a
                                            href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">
                                            <img src="{{ asset($post->image ?? 'images/logo.png') }}"
                                                alt="{{ $post->libelle }}">
                                        </a>
                                        <a href="#" class="category">
                                            {{ $post->rubriques->first()->name ?? 'Uncategorized' }}
                                        </a>
                                    </div>

                                    <h6><a
                                            href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">{{ $post->libelle }}</a>
                                    </h6>
                                    <ul class="post-tags">
                                        <li>by <a
                                                href="{{ route('home', ['organization' => $post->user->organization->subdomain]) }}">{{ $post->user->organization->organization_name }}</a>
                                        </li>
                                        <li><a href="#"><span>{{ $post->comments->count() }}
                                                    comments</span></a></li>
                                    </ul>

                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    <!-- End Posts-block -->



                </div>

                <div class="col-lg-4 sidebar-sticky">
                    <!-- Sidebar -->
                    <div class="sidebar theiaStickySidebar">
                        <div class="widget slider-widget">
                            <h1>Reportages</h1>
                            <div class="flexslider">
                                @php
                                $reportages = $reportages->sortByDesc('created_at');
                                @endphp


                                <ul class="slides">
                                    @if ($reportages->isNotEmpty())
                                    <!-- Les trois derniers reportages dans le slider -->
                                    @foreach ($reportages as $reportage)
                                    @php

                                    $videoUrl = $reportage->video;

                                    parse_str(parse_url($videoUrl, PHP_URL_QUERY), $queryParams);
                                    $videoId = $queryParams['v'] ?? '';

                                    // Crée l'URL d'intégration (embed)
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                    @endphp
                                    <li>
                                        <iframe class="videoembed"
                                            src="{{ $embedUrl }}?title=0&amp;byline=0&amp;portrait=0"
                                            frameborder="0" webkitallowfullscreen="" mozallowfullscreen=""
                                            allowfullscreen="">
                                        </iframe>
                                        <div class="slider-caption">
                                            <a href="#" class="category">Reportages</a>
                                            <h2><a
                                                    href="{{ route('single-post', ['organization' => $reportage->user->organization->subdomain, 'id' => $reportage->id]) }}">{{ $reportage->title }}</a>
                                            </h2>
                                            <ul class="post-tags">
                                                <li><i class="lnr lnr-user"></i>by <a
                                                        href="#">{{ $reportage->user->organization->organization_name }}</a>
                                                </li>
                                                <li><a href="#"><i
                                                            class="lnr lnr-book"></i><span>{{ $reportage->comments->count() }}
                                                            comments</span></a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>

                        </div>


                        {{-- <div class="advertisement">
                                <a href="#"><img src="upload/addsense/300x250.jpg" alt=""></a>
                            </div> --}}

                        <div class="widget social-widget">
                            <h1>Reste connecté </h1>
                            <p>Nos pages sociales</p>
                            <ul class="social-share">

                                <li>
                                    <a href="https://web.facebook.com/profile.php?id=100089144914919"
                                        class="facebook">
                                        <i class="fa fa-facebook"></i>
                                        <span></span>
                                    </a>
                                </li>
                                {{-- <li>
                                        <a href="#" class="twitter">
                                            <i class="fa fa-twitter"></i>
                                            <span>5,600</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="google">
                                            <i class="fa fa-google-plus"></i>
                                            <span>659</span>
                                        </a>
                                    </li> --}}
                            </ul>
                        </div>

                        <div class="widget tags-widget">
                            <h1>Rubriques</h1>
                            <ul class="tags-list">
                                @php
                                // Crée un tableau associatif avec les noms des rubriques comme clés et les IDs comme valeurs
                                $rubriqueLookup = $tags->pluck('id', 'name')->toArray();
                                @endphp


                                @foreach ($tags as $tag)
                                @php
                                // Utilise le nom de la rubrique et son ID
                                $rubriqueId = $tag->id;
                                $rubriqueName = $tag->name;
                                @endphp
                                <li><a href="{{ route('categories', ['id' => $rubriqueId]) }}">
                                        {{ $rubriqueName }}
                                    </a></li>
                                @endforeach



                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="up-footer">
                <div class="row justify-content-between">

                    <!-- Bloc de gauche : Logo et description -->
                    <div class="col-md-3 text-center">
                        <div class="footer-widget text-widget">
                            <div class="d-flex flex-column align-items-center">
                                <h1>
                                    <a href="index.html">
                                        <img src="{{ asset('images/logo_blanc.png') }}" alt="Logo" class="img-fluid"
                                            height="120" width="120">
                                    </a>
                                </h1>
                                <p style="color: #fff; font-size: 14px; margin-top: 10px;">
                                    <strong>e-BENIN</strong> - Votre source incontournable pour des articles
                                    percutants sur l’actualité, le développement durable, la culture et bien plus.
                                    Rejoignez notre réseau de blogs pour rester informé, inspiré et engagé.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bloc central : Liens sous forme de liste à puces -->
                    <div class="col-md-5 col-6 text-center">
                        <div class="footer-widget" style="color: #fff;">
                            <ul style="list-style-type: none; padding: 0; text-align:center;">
                                <li style="color: #fff; font-size: 14px; margin-top: 10px;">
                                    <strong>LIENS UTILES</strong>
                                </li>
                                @foreach ($footerOrgs as $organization)
                                <li style="color: #fff; font-size: 12px; margin-top: 5px;">
                                    <a href="{{ route('home', ['organization' => urlencode($organization->subdomain)]) }}"
                                        style="color: #fff; text-decoration: none;">
                                        {{ $organization->organization_name }}
                                    </a>
                                </li>
                                @endforeach

                                <li style="color: #fff; font-size: 12px; margin-top: 5px;">
                                    <a href="{{route('politique')}}" style="color: #fff; text-decoration: none;">
                                        Politique de confidentialité
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bloc de droite : Informations de contact -->
                    <div class="col-md-3 col-6 text-center">
                        <div class="footer-widget text-widget">
                            <p style="color: #fff; font-size: 12px; margin-top: 10px;"><strong>CONTACTS</strong>
                            </p>
                            <ul class="contact-info" style="color: #fff; list-style: none; padding: 0;">
                                <li style="font-size: 14px; margin-top: 5px;"><i class="fas fa-phone"></i> (+229)
                                    20 21 37 59</li>
                                <li style="font-size: 14px; margin-top: 5px;"><i class="fas fa-phone"></i> (+229)
                                    69 41 66 66</li>
                                <li style="font-size: 14px; margin-top: 5px;"><i class="fas fa-envelope"></i>
                                    contact@savplus.net</li>
                                <li style="font-size: 14px; margin-top: 5px;"><i class="fas fa-map-marker-alt"></i>
                                    Godomey,BENIN</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bas du footer avec crédits et lien de retour en haut de page -->
            <div class="down-footer text-center mt-3">
                <p style="color: #fff; font-size: 14px;">
                    &copy; BY <strong><a href="https://savplus.net"
                            style="color: #fff; text-decoration: none;">SAVPLUS CONSEIL</a></strong> 2024 - TOUS
                    DROITS RÉSERVÉS
                    <a href="#" class="go-top" style="color: #fff; margin-left: 10px;"><i class="fa fa-caret-up"
                            aria-hidden="true"></i></a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Ajout du CSS personnalisé -->
    <style>
        /* Enlève les puces devant les éléments <li> */
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Stylise les icônes en rouge et le texte en bleu */
        .contact-info li {
            color: rgb(78, 107, 236);
            font-size: 16px;
        }

        .contact-info i {
            color: red;
            margin-right: 8px;
        }



        .social-icons a {
            text-decoration: none;
        }

        .social-icons a:hover i {
            color: rgb(155, 155, 155);
        }
    </style>



    <!-- End footer -->
    </div>
    <!-- End Container -->

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="title-section">
                        <h1>Connexion</h1>
                    </div>
                    <form id="login-form" method="POST" action="{{ route('userLogin') }}">
                        @csrf
                        <label for="email">Adresse Mail*</label>
                        <input id="email" type="text" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus>
                        <label for="password">Mot de passe*</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password">
                        <button type="submit" id="submit-login">
                            <i class="fa fa-paper-plane"></i> Se connecter
                        </button>
                    </form>
                    <p>
                        <a href="{{ route('forgotView') }}">Mot de passe oublié ? Cliquez ici!</a>
                    </p>
                    <p>Vous n'avez pas encore de compte? <a href="{{ route('userRegister') }}">Inscrivez-vous ici</a>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- End Login Modal -->

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