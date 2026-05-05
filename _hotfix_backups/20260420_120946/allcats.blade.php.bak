<!doctype html>


<html lang="en" class="no-js">

<head>
    <title>Rubriques</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


    <link rel="stylesheet" href="{{ asset('myBlogAssets/css/modernmag-assets.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('myBlogAssets/css/style.css') }}">

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
                                <li>
                                    <a href="https://e-benin.com">E-BENIN</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header -->


        <section id="content-section" style="width:100%;">
            <div class="container">
                <h1 class="text-center" style="color: red;font-size:28px;">{{ $rubrique->name }}</h1>
                <hr>
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
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">
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
                                                    href="{{ route('single-post', ['id' => $post->id, 'organization' => $post->user->organization->subdomain]) }}">{{ $post->libelle }}</a>
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
                        <h1><a href="/"><img src="images/logo.png" alt=""></a></h1>

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


    <script src="myblogAssets/js/modernmag-plugins.min.js"></script>
    <script src="myblogAssets/js/popper.js"></script>
    <script src="myblogAssets/js/bootstrap.min.js"></script>
    <script src="{{ asset('myblogAssets/js/script.js') }}"></script>
    <script
        src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en">
    </script>
    <script src="{{ asset('myblogAssets/js/gmap3.min.js') }}"></script>

</body>

</html>
