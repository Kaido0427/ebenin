<!doctype html>


<html lang="en" class="no-js">

<head>
    <title>{{ $user->organization->organization_name }} | Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body class="boxed-style">

    <!-- Container -->
    <div id="container">
        <header class="clearfix">
            <div class="top-line bg-dark py-2">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-inline mb-0">
                                <li id="clock" class="list-inline-item" style="color: white;">
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
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list-inline mb-0 float-end">
                                @php
                                    // Déterminer dynamiquement le domaine
                                    $host = request()->getHost();
                                    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
                                @endphp

                                <li class="list-inline-item">
                                    <a class="btn btn-primary" href="https://{{ $baseDomain }}">E-BENIN</a>
                                </li>

                                <li class="list-inline-item">
                                    <a class="btn btn-danger" href="{{ route('logOut') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Deconnexion') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logOut') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @php
                                $host = request()->getHost();
                                $subdomain = explode('.', $host)[0];
                            @endphp

                            <li class="nav-item">
                                <a class="nav-link active"
                                    href="https://{{ $subdomain }}.{{ $baseDomain }}/blog">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Mes
                                    Articles</a>
                            </li>
                            @if ($biographie)
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="#" data-bs-toggle="modal"
                                        data-bs-target="#createBiographyModal">Ajouter une biographie</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateOrganizationModal"> Modifier
                                    {{ $organization->organization_name }}</a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#updateBiographyModal"> Modifier La biographie</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <!-- End Header -->
        <br><br>
        <section id="content-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Card pour afficher les informations -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">{{ $organization->organization_name }}</h5>
                                <h5 class="card-title mb-0">{{ $user->name }}</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ asset($organization->organization_logo) }}" class="img-fluid mb-3"
                                    height="800" width="800" alt="Logo de l'organisation">
                                <hr>
                                <div class="row">
                                    <!-- Colonne pour l'image de la biographie -->
                                    <div class="col-md-4 text-center">
                                        @if ($biographie && $biographie->avatar)
                                            <img src="{{ asset($biographie->avatar) }}"
                                                class="img-fluid rounded-circle fixed-size-avatar" alt="Avatar">
                                        @else
                                            <img src="{{ asset('images/dists/user.webp') }}"
                                                class="img-fluid rounded-circle fixed-size-avatar" alt="Avatar">
                                        @endif
                                    </div>
                                    <style>
                                        .fixed-size-avatar {
                                            width: 100px;
                                            /* Largeur fixe */
                                            height: 100px;
                                            /* Hauteur fixe */
                                            object-fit: cover;
                                            /* Redimensionne l'image tout en conservant le ratio */
                                        }
                                    </style>

                                    <!-- Colonne pour la biographie -->
                                    <div class="col-md-8">
                                        @if ($biographie && !empty($biographie->bio))
                                            <p>{!! $biographie->bio !!}</p>
                                        @else
                                            <p>Vous n'avez pas de bio !Ajouter en une</p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <!-- Boutons pour créer et mettre à jour la biographie -->

                            </div>
                        </div>

                        <!-- Modal pour créer une biographie -->
                        <div class="modal fade" id="createBiographyModal" tabindex="-1"
                            aria-labelledby="createBiographyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('bio.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="createBiographyModalLabel">Créer une
                                                Biographie</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="bio" class="form-label">Biographie</label>
                                                <textarea class="form-control" id="bio" name="bio" rows="5"
                                                    required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="avatar" class="form-label">Avatar</label>
                                                <input type="file" class="form-control" id="avatar" name="avatar">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal pour mettre à jour la biographie -->
                        <div class="modal fade" id="updateBiographyModal" tabindex="-1"
                            aria-labelledby="updateBiographyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST"
                                        action="{{ $biographie ? route('bio.update', ['id' => $biographie->id]) : route('bio.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @if ($biographie)
                                            @method('PUT')
                                        @else
                                            @method('POST')
                                        @endif

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateBiographyModalLabel">
                                                {{ $biographie ? 'Modifier la Biographie' : 'Ajouter une Biographie' }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="bio" class="form-label">Biographie</label>
                                                <textarea class="form-control" id="bio" name="bio" rows="5"
                                                    required>{{ $biographie->bio ?? '' }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="avatar" class="form-label">Avatar</label>
                                                <input type="file" class="form-control" id="avatar" name="avatar">
                                                @if ($biographie && $biographie->avatar)
                                                    <img src="{{ asset($biographie->avatar) }}" class="img-fluid mt-2"
                                                        height="70" width="70" alt="Image actuelle">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit"
                                                class="btn btn-warning">{{ $biographie ? 'Mettre à jour' : 'Ajouter' }}</button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>


                        <!-- Modal pour mettre à jour l'organisation -->
                        <div class="modal fade" id="updateOrganizationModal" tabindex="-1"
                            aria-labelledby="updateOrganizationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('org.update', ['id' => $organization->id]) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateOrganizationModalLabel">Modifier
                                                l'Organisation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="organization_name" class="form-label">Nom de
                                                    l'Organisation</label>
                                                <input type="text" class="form-control" id="organization_name"
                                                    name="organization_name"
                                                    value="{{ $organization->organization_name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="organization_address" class="form-label">Adresse</label>
                                                <input type="text" class="form-control" id="organization_address"
                                                    name="organization_address"
                                                    value="{{ $organization->organization_address }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="organization_phone" class="form-label">Téléphone</label>
                                                <input type="text" class="form-control" id="organization_phone"
                                                    name="organization_phone"
                                                    value="{{ $organization->organization_phone }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="organization_email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="organization_email"
                                                    name="organization_email"
                                                    value="{{ $organization->organization_email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="organization_logo" class="form-label">Logo</label>
                                                <input type="file" class="form-control" id="organization_logo"
                                                    name="organization_logo">
                                                @if ($organization->organization_logo)
                                                    <img src="{{ asset($organization->organization_logo) }}"
                                                        class="img-fluid mt-2" alt="Logo actuel">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script
                            src="https://cdn.tin.cloud/1/x8yqfgtr6nfr1pqqwtj5noxr4sla24dbm2uj55o12kivvy2d/tinymce/7/tinymce.min.js"
                            referrerpolicy="origin"></script>

                        <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
                        <script>
                            tinymce.init({
                                selector: 'textarea',
                                plugins: [
                                    // Core editing features
                                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                                    // Your account includes a free trial of TinyMCE premium features
                                    // Try the most popular premium features until Sep 9, 2024:
                                    'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
                                ],
                                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                                tinycomments_mode: 'embedded',
                                tinycomments_author: 'Author name',
                                mergetags_list: [
                                    { value: 'First.Name', title: 'First Name' },
                                    { value: 'Email', title: 'Email' },
                                ],
                                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
                            });
                        </script>
                        <textarea>
  Welcome to TinyMCE!
</textarea>

                    </div>
                </div>
            </div>
        </section>
        <!-- End content section -->

        <footer class="bg-dark text-light py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h3>{{ $user->organization->organization_name }}</h3>
                        <p>Agence web • <a href="http://agence.savplus.net" style="text-decoration: none;">SAVOIR PLUS
                                CONSEIL</a></p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p>&copy; {{ date('Y') }} Tous droits réservés.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <ul class="list-inline social-icons">
                            <li class="list-inline-item"><a href="#" class="text-light"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-light"><i
                                        class="fab fa-twitter"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-light"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li class="list-inline-item"><a href="#" class="text-light"><i
                                        class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!-- End Footer -->

    <!-- End Container -->
    <!-- jQuery (nécessaire pour DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Ensure the body takes full height */
        html,
        body {
            height: 100%;
        }

        /* Flexbox layout for the wrapper */
        #container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Full viewport height */
        }

        /* Main content should grow to take available space */
        #content-section {
            flex: 1;
        }

        /* Footer styling */
        footer {
            background-color: #333;
            /* Example footer background color */
            color: #fff;
            /* Example footer text color */
            text-align: center;
            padding: 10px 0;
            width: 100%;
        }
    </style>




</body>

</html>