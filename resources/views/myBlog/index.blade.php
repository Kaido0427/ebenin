<!doctype html>


<html lang="en" class="no-js">

<head>
    <!-- Title Tag -->
    <title>{{ $organization->organization_name }} | Accueil</title>

    <!-- Meta Charset -->
    <meta charset="UTF-8">

    <!-- Meta Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Description -->
    <meta name="description"
        content="{{ $organization->organization_name }} couvre l'actualité, les événements, les analyses et les opinions sur des sujets variés en lien avec le Bénin. Rejoignez-nous pour les dernières nouvelles.">

    <!-- Meta Keywords -->
    <meta name="keywords"
        content="actualité, journalisme, {{ $organization->organization_name }}, cotonou, bénin, informations, analyses, opinions, blog">

    <!-- Meta Author -->
    <meta name="author" content="{{ $organization->organization_name }}">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $organization->organization_name }} | Accueil">
    <meta property="og:description"
        content="{{ $organization->organization_name }} est un blog dédié à l'actualité. Retrouvez les dernières nouvelles et plus encore.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url($organization->organization_logo ?? 'images/ebenins.png') }}">
    <!-- Remplacer par le chemin réel de l'image -->
    <meta property="og:site_name" content="{{ $organization->organization_name }}">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $organization->organization_name }} | Accueil">
    <meta name="twitter:description"
        content="{{ $organization->organization_name }} est un blog de journalisme couvrant les dernières actualités et analyses sur l'économie, le sport, la culture et d'autres rubriques.">
    <!-- Remplacer par le chemin réel de l'image -->
    <meta name="twitter:site" content="@YourTwitterHandle"> <!-- Remplacer par le compte Twitter réel -->

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Robots Meta Tag -->
    <meta name="robots" content="index, follow">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/style.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($organization->organization_logo) }}" />

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
                            <a class="navbar-brand" href="#">
                                <img src="{{ asset($organization->organization_logo) }}" class="img-fluid" height="90" width="90" alt="Logo">
                            </a>
                        </div>
                        <div class="col-sm-7">
                            <ul class="info-list right-align">
                                <li id="clock">
                                    <i class="fa fa-clock-o"></i> {{ now()->formatLocalized('%A %d.%m.%Y %H:%M:%S') }}
                                </li>
                                <script>
                                    function updateClock() {
                                        document.getElementById('clock').innerHTML = `
                                <i class="fa fa-clock-o"></i> ${new Date().toLocaleString('fr-FR', {
                                    weekday: 'long', year: 'numeric', month: 'numeric', 
                                    day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'
                                })}
                            `;
                                    }
                                    setInterval(updateClock, 1000);
                                </script>

                                @php
                                $host = request()->getHost();
                                $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
                                @endphp

                                @guest
                                <li>
                                    <a href="https://{{ $baseDomain }}">E-BENIN</a>
                                </li>
                                @else
                                <li>
                                    <a href="https://{{ $baseDomain }}">E-BENIN</a>
                                </li>

                                @if (isset(Auth()->user()->organization) && !empty(Auth()->user()->organization->subdomain))
                                @php
                                $subdomain = Auth()->user()->organization->subdomain;
                                @endphp
                                <li>
                                    <a href="https://{{ $subdomain }}.{{ $baseDomain }}/dashboard">Tableau de bord</a>
                                </li>
                                @endif

                                <li>
                                    <a href="{{ route('logOut') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Déconnexion') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logOut') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                                @endguest
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
                            $rubriqueCount = $rubriques->count();
                            $totalCount = $rubriqueCount + 1; // Include the "Home" link
                            $fontSize = max(12, 20 - $totalCount); // Adjust font size based on total number of links
                            @endphp
                            <li class="nav-item active">
                                <a class="nav-link"
                                    href="{{ route('home', ['organization' => $organization->subdomain]) }}"
                                    style="font-size: {{ $fontSize }}px; text-transform: uppercase;">
                                    Accueil
                                </a>
                            </li>
                            @forelse ($rubriques as $rubrique)
                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('category.show', ['id' => $rubrique->id, 'organization' => $subdomain]) }}"
                                    style="font-size: {{ $fontSize }}px; text-transform: uppercase;">
                                    {{ $rubrique->name }}
                                </a>
                            </li>
                            @empty
                            @auth
                            <li>
                                Publier un article dans une rubrique pour la voir apparaitre ici!
                            </li>
                            @endauth
                            <li>
                                Aucune rubrique disponible!
                            </li>
                            @endforelse
                        </ul>
                    </div>

                </div>
            </nav>


        </header>
        <!-- End Header -->

        <!-- content-section
   ================================================== -->
        <section id="content-section">
            <div class="container">

                <div class="row">
                    <div class="col-lg-8">

                        <!-- Posts-block -->
                        <div class="posts-block">
                            <div class="slider-news">
                                <div class="flexslider">
                                    <ul class="slides">
                                        @if ($latestNews)
                                        <!-- Vérifier si $latestPost est défini -->
                                        <li>
                                            <img alt="" src="{{ asset($latestNews->image) }}" />
                                            <div class="slider-caption">
                                                <h2><a
                                                        href="{{ route('single-post', ['id' => $latestNews->id, 'organization' => $subdomain]) }}">{{ $latestNews->libelle }}</a>
                                                </h2>
                                                <p>{{ $latestNews->libelle }}</p>
                                                <p>{{ $latestNews->sous_titre }}</p>
                                            </div>
                                        </li>
                                        @else
                                        <li>
                                            <p>Aucune publication trouvée.</p>
                                        </li>
                                        @endif
                                    </ul>

                                </div>
                            </div>
                        </div>

                        <!-- End Posts-block -->

                        <!-- Posts-block -->
                        <div class="posts-block standard-box">
                            <div class="posts-block standard-box">
                                <div class="row">
                                    @forelse ($randomPosts as $item)
                                    @php
                                    // Récupérer la rubrique et le post
                                    $rubrique = $item['rubrique'];
                                    $post = $item['post'];
                                    @endphp
                                    <div class="col-sm-6">
                                        <div class="news-post standart-post">
                                            <div class="post-image">
                                                <a
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">
                                                    <img src="{{ asset($post->image) }}" alt="">
                                                </a>
                                                <a href="#" class="category">{{ $rubrique->name }}</a>
                                            </div>
                                            <h2><a
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">{{ $post->libelle }}</a>
                                            </h2>
                                            <p>{{ $post->libelle }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <p>Rien ici pour le moment!</p>
                                    @endforelse

                                </div>
                            </div>


                        </div>
                        <!-- End Posts-block -->

                    </div>

                    <div class="col-lg-4 sidebar-sticky">

                        <!-- Sidebar -->
                        <div class="sidebar theiaStickySidebar">

                            {{-- <div class="widget news-widget">
                                <h1>Breaking News</h1>
                                <ul class="list-news">
                                    <li>
                                        <h2><a href="single-post.html">The Guardian view on Germany’s coalition deal:
                                                Merkel in the balance</a></h2>
                                    </li>
                                    <li>
                                        <h2><a href="single-post.html">Philip Dunne, sacked after his NHS remarks, must
                                                now face his constituents</a></h2>
                                    </li>
                                    <li>
                                        <h2><a href="single-post.html">Cameroon’s heartbreaking struggles are a relic
                                                of British colonialism</a></h2>
                                    </li>
                                    <li>
                                        <h2><a href="single-post.html">India has 600 million young people – and they’re
                                                set to change our world</a></h2>
                                    </li>
                                    <li>
                                        <h2><a href="single-post.html">Ramaphosa vows to fight corruption in ruling
                                                ANC</a></h2>
                                    </li>
                                </ul>
                            </div> --}}
                            @php
                            use Carbon\Carbon;
                            $now = Carbon::now();
                            $createdAt = $pub ? new Carbon($pub->created_at) : null;
                            $isValid = $createdAt ? $now->diffInHours($createdAt) <= 72 : false;
                                @endphp
                                @if ($pub)
                                <div class="advertisement">
                                <a href="{{ $pub->url }}"><img src="{{ asset($pub->image) }}"
                                        alt=""></a>
                        </div>
                        @else
                        @endif


                        <div class="widget tags-widget">
                            <div class="widget social-widget">
                                <h1>Reste connecté</h1>
                                <p>Nos pages sociales</p>
                                <ul class="social-share">
                                    @foreach ($socials as $social)
                                    @php
                                    // Assurez-vous que le nom du réseau social correspond à la classe CSS
                                    $socialClass = strtolower($social->social->nom); // Exemple: 'facebook', 'twitter'
                                    @endphp
                                    @if (in_array($socialClass, ['facebook', 'youtube', 'linkedin', 'instagram']))
                                    <li>
                                        <a href="{{ $social->url }}" class="{{ $socialClass }}">
                                            <i class="fa fa-{{ $socialClass }}"></i>
                                            <span></span>
                                            <!-- Si vous avez un champ pour le nombre de followers -->
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>

                            <h1>Rubriques</h1>
                            <ul class="tags-list">
                                @php
                                $subdomain = $organization->subdomain;
                                @endphp
                                @forelse ($randomTags as $tag)
                                <li><a
                                        href="{{ route('category.show', ['id' => $tag->id, 'organization' => $subdomain]) }}">{{ $tag->name }}</a>
                                </li>
                                @empty
                                <li>
                                    Rien ici pour le moment
                                </li>
                                @endforelse
                            </ul>
                        </div>


                    </div>

                </div>
            </div>

            <!-- Posts-block -->
            <div class="posts-block featured-box">
                <div class="title-section">
                    <h1>DERNIERES NOUVELLES</h1>
                </div>

                <div class="owl-wrapper">
                    <div class="owl-carousel" data-num="4">
                        @forelse ($rubriques as $rubrique)
                        @if ($rubrique->posts->isNotEmpty())
                        @php
                        $post = $rubrique->posts->first();
                        @endphp
                        <div class="item">
                            <div class="news-post standart-post">
                                <div class="post-image">
                                    <a
                                        href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">
                                        <img src="{{ asset($post->image) }}" alt="">
                                    </a>
                                    <a href="#" class="category">{{ $rubrique->name }}</a>
                                </div>
                                <h2><a
                                        href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">{{ $post->libelle }}</a>
                                </h2>
                                <p>{{ Str::limit($post->description, 100) }}</p>
                                <ul class="post-tags">
                                    <li>{{ $post->created_at->format('F d, Y, h:i A') }}</li>
                                </ul>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="item">
                            <div class="news-post standart-post">
                                Pas de derniere nouvelle pour le moment!
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- End Posts-block -->
            <!-- Advertisement -->
            <div class="advertisement">
                <a href="#"><img src="upload/addsense/620x80grey.jpg" alt=""></a>
            </div>
            <!-- End Advertisement -->

            <div class="row">
                <div class="col-lg-8">

                    <!-- Post-block -->
                    <div class="post-block video-section">
                        <div class="title-section">
                            <h1>Reportages</h1>
                        </div>
                        <div class="row">
                            @forelse($reportages as $key => $reportage)
                            @if ($key === 0)
                            <div class="col-lg-8">
                                @php

                                $videoUrl = $reportage->video;

                                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $queryParams);
                                $videoId = $queryParams['v'] ?? '';

                                // Crée l'URL d'intégration (embed)
                                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                @endphp
                                <div class="video-holder">
                                    <!-- youtube -->
                                    <iframe class="videoembed" src="{{ $embedUrl }}" frameborder="0"
                                        webkitallowfullscreen="" mozallowfullscreen=""
                                        allowfullscreen="">
                                    </iframe>
                                    <!-- End youtube -->
                                    <h2><a
                                            href="{{ route('single-post', ['id' => $reportage->id, 'organization' => $organization->subdomain]) }}">
                                            {{ $reportage->libelle }}
                                        </a></h2>
                                    <p>{{ $reportage->sous_titre }}</p>
                                    <ul class="post-tags">
                                        <li>{{ $reportage->created_at->format('F j, Y, g:i A') }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="video-links">
                                    @else
                                    <div class="news-post video-post">
                                        <div class="post-image">
                                            <a
                                                href="{{ route('single-post', ['id' => $reportage->id, 'organization' => $organization->subdomain]) }}">
                                                <img src="{{ $reportage->image_url }}" alt="">
                                                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <h2><a
                                                href="{{ route('single-post', ['id' => $reportage->id, 'organization' => $organization->subdomain]) }}">
                                                {{ $reportage->libelle }}
                                            </a></h2>
                                    </div>
                                    @endif

                                    @if ($loop->last && $key !== 0)
                                </div>
                            </div>
                            @endif
                            @empty
                            <p>Aucun reportage disponible pour le moment.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- End Post-block -->
                </div>

                <div class="col-lg-4 sidebar-sticky">

                    <!-- Sidebar -->
                    {{-- <div class="sidebar theiaStickySidebar">
                            <div class="widget news-widget">
                                <h1>Publication à la une</h1>
                                <ul class="small-posts">
                                    @forelse ($featuredPosts as $post)
                                    <li>
                                        <a
                                            href="{{ route('single-post', ['id' => $post->id, 'organization' => $subdomain]) }}">
                    <img src="{{ asset($post->image) }}" alt="">
                    </a>
                    <div class="post-cont">
                        <h2><a
                                href="{{ route('single-post', ['id' => $post->id, 'organization' => $subdomain]) }}">{{ $post->libelle }}</a>
                        </h2>
                    </div>
                    </li>
                    @empty
                    <p>Aucune Publication à la une pour le moment!</p>
                    @endforelse
                    </ul>
                </div>
            </div> --}}

    </div>
    </div>
    </div>
    </section>
    <!-- End content section -->

    <!-- footer
   ================================================== -->
    <footer>
        <div class="container">

            <div class="up-footer">

                <div class="footer-widget text-widget">
                    <img src="{{ asset($organization->organization_logo) }}" height="80" width="80"
                        class="img-fluid" alt="Organization Logo">

                    <ul class="social-icons">
                        @foreach ($socials as $social)
                        @php
                        // Assurez-vous que le nom du réseau social correspond à la classe CSS
                        $socialClass = strtolower($social->social->nom); // Exemple: 'facebook', 'twitter'
                        @endphp
                        @if (in_array($socialClass, ['facebook', 'youtube', 'google', 'linkedin', 'instagram']))
                        <li>
                            <a class="{{ $socialClass }}" href="{{ $social->url }}">
                                <i class="fa fa-{{ $socialClass }}"></i>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>


            </div>

        </div>
        <div class="down-footer">
            <div class="container">

                <p>&copy; BY <strong> <a style="color: rgb(145, 34, 34)" href="https://savplus.net">SAVPLUS
                            CONSEIL</a>
                    </strong>2024<a href="#" class="go-top"><i class="fa fa-caret-up"
                            aria-hidden="true"></i></a></p>
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