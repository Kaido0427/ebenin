<!doctype html>


<html lang="en" class="no-js">

<head>
    <!-- Title Tag Dynamique -->
    <title>{{ $rubrique->name }} | {{ $organization->organization_name }}</title>

    <!-- Meta Charset -->
    <meta charset="UTF-8">

    <!-- Meta Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Description Dynamique -->
    <meta name="description"
        content="Découvrez les articles de la rubrique {{ $rubrique->name }} du blog {{ $organization->organization_name }}. Actualités, analyses et opinions sur divers sujets au Bénin.">

    <!-- Meta Keywords Dynamique -->
    <meta name="keywords"
        content="Bénin, Cotonou, {{ $rubrique->name }}, {{ $organization->organization_name }}, journalisme, actualités, blog, média, presse béninoise">

    <!-- Robots Meta Tag -->
    <meta name="robots" content="index, follow">

    <!-- Canonical URL -->
    <link rel="canonical"
        href="{{ route('category.show', ['organization' => $organization->subdomain, 'id' => $rubrique->id]) }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $rubrique->name }} | {{ $organization->organization_name }}">
    <meta property="og:description"
        content="Explorez les articles de la rubrique {{ $rubrique->name }}. Actualités, analyses et opinions au Bénin sur le blog {{ $organization->organization_name }}.">
    <meta property="og:image" content="{{ asset($organization->organization_logo) }}">
    <!-- Remplace par une image par défaut ou spécifique -->
    <meta property="og:url"
        content="{{ route('category.show', ['organization' => $organization->subdomain, 'id' => $rubrique->id]) }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $rubrique->name }} | {{ $organization->organization_name }}">
    <meta name="twitter:description"
        content="Découvrez la rubrique {{ $rubrique->name }} sur le blog {{ $organization->organization_name }}.">
    <meta name="twitter:image" content="{{ asset($organization->organization_logo) }}">
    <!-- Remplace par une image spécifique -->

    <!-- Structured Data pour la page de rubrique -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "{{ $rubrique->name }}",
      "description": "Découvrez les articles de la rubrique {{ $rubrique->name }} sur le blog {{ $organization->organization_name }}.",
      "url": "{{ route('category.show', ['organization' => $organization->subdomain, 'id' => $rubrique->id]) }}",
      "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
          @foreach ($paginatedPosts as $post)
              {
                "@type": "BlogPosting",
                "headline": "{{ $post->libelle }}",
                "description": "{{ $post->description }}",
                "url": "{{ route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id]) }}"
              } @if(!$loop->last),@endif
          @endforeach
        ]
      }
    }
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/style.css') }}">

</head>


<body class="boxed-style">

    <!-- Container -->
    <div id="container" style="">

        <header class="clearfix">

            <div class="top-line">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="info-list">
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
                        <div class="col-sm-6">
                            <ul class="info-list right-align">
                                @php
                                    // Déterminer dynamiquement le domaine
                                    $host = request()->getHost();
                                    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
                                @endphp

                                <li>
                                    <a href="https://{{ $baseDomain }}">E-BENIN</a>
                                </li>
                                @auth
                                                                <li>
                                                                    <a class="" href="{{ route('logOut') }}" onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                                                                        {{ __('Deconnexion') }}
                                                                    </a>

                                                                    <form id="logout-form" action="{{ route('logOut') }}" method="POST" class="d-none">
                                                                        @csrf
                                                                    </form>
                                                                </li>
                                                                @php
                                                                    $subdomain = auth()->user()->organization->subdomain;
                                                                @endphp

                                                                <li>
                                                                    <a href="{{ 'https://' . $subdomain . '.e-benin.com/dashboard' }}">Tableau de
                                                                        bord</a>
                                                                </li>
                                @endauth

                            </ul>
                        </div>
                    </div>
                </div>
            </div>



            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav m-auto">
                            @php
                                $rubriqueCount = $rubriquesGuest->count();
                                $totalCount = $rubriqueCount + 1; // Include the "Home" link
                                $fontSize = max(12, 20 - $totalCount); // Adjust font size based on total number of links
                            @endphp
                            <li class="nav-item active">
                                <a class="nav-link"
                                    href="{{ route('home', ['organization' => $organization->subdomain]) }}"
                                    style="font-size: {{ $fontSize }}px;text-transform: uppercase;">
                                    {{ $organization->organization_name }}
                                </a>
                            </li>
                            @foreach ($rubriquesGuest as $rubrique)
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('category.show', ['id' => $rubrique->id, 'organization' => $organization->subdomain]) }}"
                                        style="font-size: {{ $fontSize }}px;text-transform: uppercase;">
                                        {{ $rubrique->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- End Header -->


        <section id="content-section" style="width:100%;">
            <div class="container">

                <div class="row">
                    <div class="col">

                        <!-- Posts-block -->
                        <div class="posts-block articles-box">


                            @foreach ($paginatedPosts as $post)
                                <div class="news-post article-post">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="post-image">
                                                <a
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $organization->subdomain]) }}">
                                                    <div style="width: 100%; height: 200px; overflow: hidden;">
                                                        <img src="{{ $post->image ? asset($post->image) : asset($post->user->organization->organization_logo) }}"
                                                            alt="{{ $post->libelle }}"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                </a>

                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <h2><a
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $organization->subdomain]) }}">{{ $post->libelle }}</a>
                                                <p>{{ $post->sous_titre }}</p>
                                            </h2>


                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Affichage de la pagination -->
                            <ul class="pagination-list">
                                {{ $paginatedPosts->links('vendor.pagination.bootstrap-4') }}
                            </ul>
                        </div>

                        <!-- End Posts-block -->
                    </div>
                </div>

            </div>
        </section>
        <!-- End content section -->


        <footer>
            <div class="container">

                <div class="up-footer">

                    <div class="footer-widget text-widget">
                        <h1><a href="index.html"><img src="images/logo-black.png" alt=""></a></h1>
                        <p>{{ $organization->organization_name }}</p>
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
            <div class="down-footer">
                <div class="container">

                    <p>&copy; BY <strong> <a style="color: rgb(145, 34, 34)" href="https://savplus.net">SAVPLUS
                                CONSEIL</a>
                        </strong>2024<a href="#" class="go-top"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
                    </p>
                </div>
            </div>
        </footer>
        <!-- End footer -->

    </div>
    <!-- End Container -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="myBlogAssets/js/modernmag-plugins.min.js"></script>
    <script src="myBlogAssets/js/popper.js"></script>
    <script src="myBlogAssets/js/bootstrap.min.js"></script>
    <script src="{{ asset('myBlogAssets/js/script.js') }}"></script>
    <script
        src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en">
        </script>
    <script src="{{ asset('myBlogAssets/js/gmap3.min.js') }}"></script>

</body>

</html>