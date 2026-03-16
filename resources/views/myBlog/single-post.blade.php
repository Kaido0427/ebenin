<!doctype html>


<html lang="en" class="no-js">

<head>
    <!-- Title Tag -->
    <title>{{ $post->libelle }} | {{ $organization->organization_name }}</title>

    <!-- Meta Charset -->
    <meta charset="UTF-8">

    <!-- Meta Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Description -->
    <meta name="description" content="{{ $post->description }}">

    <!-- Meta Keywords -->
    <meta name="keywords"
        content="Bénin, Cotonou, journal, actualités,actualité au bénin, blog, journalisme, médias, organisations, presse, béninoise, culture béninoise, politique béninoise, économie béninoise, événements au Bénin, société béninoise, analyses, opinions, blogs de journalistes">

    <!-- Robots Meta Tag -->
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $post->libelle }} | {{ $organization->organization_name }}">
    <meta property="og:description" content="{{ $post->description }}">
    <meta property="og:image"
        content="{{ $post->image && preg_match('/\.(jpg|jpeg|png|gif)$/', $post->image) ? url($post->image) : asset('images/e-benins.png') }}">
    @if ($post->image && preg_match('/\.(jpg|jpeg|png|gif)$/', $post->image))
        <meta property="og:image" content="{{ url($post->image) }}">
    @else
        <meta property="og:image" content="{{ asset('images/e-benins.png') }}">
    @endif

    <meta property="og:url"
        content="{{ route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id]) }}">
    <meta property="og:type" content="article">



    <!-- Canonical URL -->
    <link rel="canonical"
        href="{{ route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id]) }}">

    <!-- Structured Data for Article -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BlogPosting",
            "headline": "{{ $post->libelle }}",
            "image": "{{ asset($post->image) }}",
            "author": {
                "@type": "Person",
                "name": "{{ $organization->organization_name }}"
            },
            "publisher": {
                "@type": "Organization",
                "name": "{{ $organization->organization_name }}",
                "logo": {
                    "@type": "ImageObject",
                    "url": "{{ asset($organization->organization_logo) }}"
                }
            },
            "datePublished": "{{ $post->created_at->toIso8601String() }}",
            "dateModified": "{{ $post->updated_at->toIso8601String() }}",
            "description": "{{ $post->description }}",
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "{{ route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id]) }}"
            }
        }
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/style.css') }}">
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

            {{-- <div class="header-banner-place">
                <div class="container">
                    <a class="navbar-brand" href="">
                        <img src="{{ asset($organization->organization_logo) }}" height="200" width="200"
            class="img-fluid" alt="Organization Logo">

            </a>
    </div>
    </div> --}}

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
                                    href="{{ route('home', ['organization' => $post->user->organization->subdomain]) }}"
                                    style="font-size: {{ $fontSize }}px;text-transform: uppercase;">
                                    {{ $post->user->organization->organization_name }}
                                </a>
                            </li>
                            @foreach ($rubriquesGuest as $rubrique)
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('category.show', ['id' => $rubrique->id, 'organization' => $post->user->organization->subdomain]) }}"
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

        <!-- content-section
   ================================================== -->
        <section id="content-section">
            <div class="container">

                <div class="row">
                    <div class="col-lg-8">

                        <style>
                            .capitalize {
                                text-transform: uppercase;
                                /* Met tout le texte en minuscules */
                            }

                            .capitalize::first-letter {
                                text-transform: uppercase;
                                /* Met la première lettre en majuscule */
                            }
                        </style>
                        <!-- single-post -->
                        <div class="single-post">
                            <h1 class="">{{ $post->libelle }}</h1>
                            <h3 class="text-center">{{ $post->sous_titre }}</h3>
                            <ul class="post-tags">
                                <li><i class="lnr lnr-user"></i>Par <a href="#">LA REDACTION</a></li>
                                <li><a href="#commentaires"><i
                                            class="lnr lnr-book"></i><span>{{ $post->comments->count() }}
                                            commentaires</span></a>
                                </li>
                                <li><i class="lnr lnr-eye"></i>{{ $viewCount }} Vues</li>

                            </ul>





                            <hr>
                            <style>
                                .no-action {
                                    pointer-events: none;
                                }
                            </style>

                            <img src="{{ $post->image ? asset($post->image) : asset($post->user->organization->organization_logo) }}"
                                alt="Post Image" class="no-action" oncontextmenu="return false;">


                            <div class="share-post-box">
                                <ul class="share-box">
                                    <!-- Facebook Share -->
                                    <li>
                                        <a class="facebook"
                                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('single-post', ['organization' => $post->user->organization->subdomain, 'id' => $post->id])) }}&quote={{ urlencode($post->libele) }}"
                                            target="_blank">
                                            <i class="fa fa-facebook"></i><span>Partager sur Facebook</span>
                                        </a>
                                    </li>

                                    <!-- WhatsApp Share -->
                                    <li>
                                        <a class="whatsapp"
                                            href="https://api.whatsapp.com/send?text={{ urlencode($post->user->organization->organization_name . '-' . $post->libelle . ' Pour en savoir plus : ' . route('single-post', ['organization' => $post->user->organization->subdomain, 'id' => $post->id])) }}"
                                            target="_blank">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                    fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                                    <path
                                                        d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                                                </svg>
                                                <span>WhatsApp</span>
                                            </span>
                                        </a>
                                    </li>

                                    <!-- LinkedIn Share -->
                                    <li>
                                        <a class="linkedin"
                                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id])) }}&title={{ urlencode($post->libele) }}"
                                            target="_blank">
                                            <i class="fa fa-linkedin"></i><span>Linkedin</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="background: gray;"
                                            onclick="copyToClipboard('{{ route('single-post', ['organization' => $organization->subdomain, 'id' => $post->id]) }}')">
                                            <i class="fa fa-copy"></i><span>Copier le lien</span>
                                        </a>
                                    </li>

                                    <script>
                                        function copyToClipboard(url) {
                                            // Créer un élément temporaire pour copier le texte
                                            const tempInput = document.createElement('input');
                                            tempInput.value = url;
                                            document.body.appendChild(tempInput);
                                            tempInput.select();
                                            document.execCommand('copy');
                                            document.body.removeChild(tempInput);

                                            // Optionnel : Afficher une alerte ou une notification que le lien a été copié
                                            alert('Lien copié dans le presse-papiers : ' + url);
                                        }
                                    </script>

                                </ul>
                            </div>
                            <style>
                                .text-boxes {
                                    user-select: none;
                                    /* Empêche la sélection de texte */
                                    -webkit-user-select: none;
                                    /* Pour les navigateurs basés sur WebKit (Chrome, Safari) */
                                    -moz-user-select: none;
                                    /* Pour Firefox */
                                    -ms-user-select: none;
                                    /* Pour Internet Explorer/Edge */
                                }
                            </style>
                            <div class="text-boxes">
                                {!! $post->description !!}
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const textBoxes = document.querySelectorAll('.text-boxes');

                                    textBoxes.forEach(box => {
                                        box.addEventListener('contextmenu', function(e) {
                                            e.preventDefault(); // Empêche le menu contextuel de s'afficher
                                        });

                                        box.addEventListener('copy', function(e) {
                                            e.preventDefault(); // Empêche la copie du texte
                                        });
                                    });
                                });
                            </script>

                        </div>
                        <!-- End single-post -->
                        @if ($post->video === null)
                        @else
                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Post-block -->
                                    <div class="post-block video-section">

                                        <div class="row">
                                            <div class="col">
                                                @php
                                                    $videoUrl = $post->video;
                                                    $videoId = '';
                                                    $embedUrl = '';

                                                    // Vérification de l'URL et extraction de l'ID de la vidéo
                                                    if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                                                        parse_str(parse_url($videoUrl, PHP_URL_QUERY), $queryParams);
                                                        $videoId = $queryParams['v'] ?? '';

                                                        if ($videoId) {
                                                            // Crée l'URL d'intégration (embed)
                                                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                                        }
                                                    }
                                                @endphp

                                                @if ($embedUrl)
                                                    <div class="video-holder">
                                                        <iframe class="videoembed" src="{{ $embedUrl }}"
                                                            frameborder="0"
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                @elseif($videoUrl)
                                                    <div class="video-fallback">
                                                        <p>La vidéo ne peut pas être intégrée. <a
                                                                href="{{ $videoUrl }}" target="_blank">Cliquez
                                                                ici pour la voir sur YouTube</a>.</p>
                                                    </div>
                                                @else
                                                    <p>Aucune vidéo disponible.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Post-block -->
                                </div>
                            </div>
                        @endif

                        <!-- Advertisement -->
                        <div class="advertisement">
                            <a href="#"><img src="upload/addsense/620x80grey.jpg" alt=""></a>
                        </div>
                        <!-- End Advertisement -->



                        <!-- author-profile -->
                        <div class="author-profile">
                            <div class="author-box">
                                @if ($bio && $bio->avatar)
                                    <img src="{{ asset($bio->avatar) }}" class="img-fluid" height="100"
                                        width="100" alt="Avatar">
                                @else
                                    <img src="{{ asset('images/dists/user.webp') }}" class="img-fluid"
                                        height="100" width="100" alt="Avatar">
                                @endif
                                <div class="author-content">
                                    <h4>{{ $post->user->name }}<a href="#">{{ $postsByUser->count() }}</a></h4>
                                    @if ($bio)
                                        <p>{!! $bio->bio !!} </p>
                                    @else
                                        ...
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- End author-profile -->

                        <!-- comment area box -->
                        <div class="comment-area-box">
                            <div class="title-section">
                                <h1><span>{{ $comments->count() }}

                                        @if ($comments->count() == 1)
                                            commentaire
                                        @else
                                            commentaires
                                        @endif
                                    </span></h1>
                            </div>
                            <ul id="commentaires" class="comment-tree">
                                @forelse ($comments as $comment)
                                    <li>
                                        <div class="comment-box">
                                            <img alt="photo user-comment" src="/images/dists/user.webp"
                                                height="50" width="50">
                                            <div class="comment-content">
                                                <h4>{{ $comment->reader_name }}</h4>
                                                <span><i class="fa fa-clock-o"></i>{{ $comment->created_at }}</span>
                                                <p>{{ $comment->comments }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <p>Aucun commentaire pour le moment!</p>
                                @endforelse



                            </ul>
                        </div>
                        <!-- End comment area box -->

                        <!-- contact form box -->
                        <div class="contact-form-box">
                            <div class="title-section">
                                <h1><span>Laisser un Commentaire</span> <span class="email-not-published"></span></h1>
                            </div>
                            <form id="comment-form" method="POST"
                                action="{{ route('comments.store', ['post' => $post->id]) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name">Nom*</label>
                                        <input id="name" name="name" type="text" required>
                                    </div>
                                </div>
                                <label for="comment">Commentaire*</label>
                                <textarea id="comment" name="comment" required></textarea>

                                <button type="submit" id="submit-contact">
                                    <i class="fa fa-comment"></i> Publier le commentaire
                                </button>
                            </form>
                        </div>
                        <script>
                            document.getElementById('comment-form').addEventListener('submit', function(event) {
                                var name = document.getElementById('name').value.trim();

                                var comment = document.getElementById('comment').value.trim();

                                if (name === '' || comment === '') {
                                    alert('Tous les champs doivent être remplis.');
                                    event.preventDefault();
                                }
                            });
                        </script>


                        <!-- End contact form box -->

                    </div>

                    <div class="col-lg-4 sidebar-sticky">
                        <div class="sidebar theiaStickySidebar">
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
                                            @if (in_array($socialClass, ['facebook', 'youtube', 'linkedin', 'instagram', 'whatsapp']))
                                                <li>
                                                    <a href="{{ $social->url }}" class="{{ $socialClass }}">
                                                        @if ($socialClass == 'whatsapp')
                                                            <i class="fa fa-whatsapp"></i>
                                                            <!-- Utiliser la classe 'fab fa-whatsapp' -->
                                                        @else
                                                            <i class="fa fa-{{ $socialClass }}"></i>
                                                            <!-- Pour les autres réseaux sociaux -->
                                                        @endif
                                                        <span></span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                            </div>

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
                        </div>

                    </div>
                </div>

            </div>
        </section>
        <!-- End content section -->
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
                            <input id="email" type="text" name="email" value="{{ old('email') }}"
                                required autocomplete="email" autofocus>
                            <label for="password">Mot de passe*</label>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password">
                            <button type="submit" id="submit-login">
                                <i class="fa fa-paper-plane"></i> Se connecter
                            </button>
                        </form>
                        <p>Vous n'avez pas encore de compte? <a href="{{ route('register') }}">Inscrivez-vous ici</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer
   ================================================== -->
        <footer>
            <div class="container">

                <div class="up-footer">

                    <div class="footer-widget text-widget">
                        <img src="{{ asset($organization->organization_logo) }}" height="200" width="200"
                            class="img-fluid" alt="Organization Logo">
                        <ul class="social-icons">
                            @foreach ($socials as $social)
                                @php
                                    // Assurez-vous que le nom du réseau social correspond à la classe CSS
                                    $socialClass = strtolower($social->social->nom); // Exemple: 'facebook', 'twitter'
                                @endphp
                                @if (in_array($socialClass, ['facebook', 'youtube', 'google', 'linkedin', 'instagram','whatsapp']))
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
                    <div class="row">
                        <!-- Section gauche : Créateur du site -->
                        <div class="col-md-6 text-left">
                            <p>&copy;BY<strong>
                                    <a style="color: rgb(145, 34, 34)" href="https://savplus.net">SAVPLUS CONSEIL</a>
                                </strong> 2024</p>
                        </div>
                        <!-- Section droite : Informations de l'organisation -->
                        <div class="col-md-6 text-right">
                            <p>
                                <strong
                                    style="color: rgb(145, 34, 34)">{{ $organization->organization_name }}</strong><br>
                                Adresse : {{ $organization->organization_address }}<br>
                                Téléphone : {{ $organization->organization_phone }}<br>
                                Email : <a
                                    href="mailto:{{ $organization->organization_email }}">{{ $organization->organization_email }}</a><br>

                            </p>
                        </div>
                    </div>
                </div>
            </div>


        </footer>
        <!-- End footer -->

    </div>
    <!-- End Container -->



    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="title-section">
                        <h1>Login</h1>
                    </div>
                    <form id="login-form">
                        <p>Welcome! Login to your account.</p>
                        <label for="username">Username or Email Address*</label>
                        <input id="username" name="username" type="text">
                        <label for="password">Password*</label>
                        <input id="password" name="password" type="password">
                        <button type="submit" id="submit-register">
                            <i class="fa fa-paper-plane"></i> Login
                        </button>
                    </form>
                    <p>Don't have account? <a href="register.html">Register Here</a></p>

                </div>
            </div>
        </div>
    </div>
    <!-- End Login Modal -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="myblogAssets/js/modernmag-plugins.min.js"></script>
    <script src="myblogAssets/js/popper.js"></script>
    <script src="myblogAssets/js/bootstrap.min.js"></script>
    <script src="{{ asset('myblogAssets/js/script.js') }}"></script>


</body>

</html>
