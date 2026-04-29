<!DOCTYPE html>
@php
    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
@endphp
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $organization->organization_name }} | Manage Posts</title>
    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">


</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav m-auto">
                    @php
                        $subdomain = explode('.', request()->getHost())[0];
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link" href="https://{{ $subdomain }}.{{ $baseDomain }}/blog">Accueil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Gestion des Articles</h1>

        <!-- Bouton pour ouvrir le modal de création -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPostModal">Ajouter un
            Article</button>

        <!-- Tableau des articles -->
        <table id="postsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>{{ $post->libelle }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($post->description, 50, $end = '...') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#updatePostModal" data-id="{{ $post->id }}"
                                data-libelle="{{ $post->libelle }}" data-description="{{ $post->description }}"
                                data-rubrique-id="{{ $post->rubriques->first()->id }}"
                                data-rubrique-nom="{{ $post->rubriques->first()->name }}"
                                data-video="{{ $post->video }}"> Modifier</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucun Article publié!</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <!-- Modal de création -->
    <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPostModalLabel">Ajouter un Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createPostForm" method="POST" action="{{ route('articles.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="rubrique" class="form-label">Rubrique</label>
                            <select class="form-select" id="rubrique" name="rubrique_id" required>
                                <option value="" disabled selected>Choisissez une rubrique</option>
                                <!-- Remplacez ces options par celles dynamiques depuis votre base de données -->
                                @foreach ($rubriques as $rubrique)
                                    <option value="{{ $rubrique->id }}">{{ $rubrique->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <small class="text-danger d-block mt-1">⚠️ Ratio recommandé : <strong>16:9</strong> (ex : 1200×675 px). Une image trop haute risque d'avoir les visages coupés à l'affichage.</small>
                        </div>
                        <div class="mb-3"> 
                            <label for="video" class="form-label">Lien de la vidéo</label>
                            <input type="text" class="form-control" id="video" name="video" disabled>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var rubriqueSelect = document.getElementById('rubrique');
                                var videoInput = document.getElementById('video');

                                rubriqueSelect.addEventListener('change', function() {
                                    var selectedRubrique = rubriqueSelect.options[rubriqueSelect.selectedIndex].text;
                                    if (selectedRubrique === 'Reportage') {
                                        videoInput.disabled = false;
                                    } else {
                                        videoInput.disabled = true;
                                        videoInput.value = ''; // Clear the file input
                                    }
                                });
                            });
                        </script>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de modification -->
    <div class="modal fade" id="updatePostModal" tabindex="-1" aria-labelledby="updatePostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePostModalLabel">Modifier l'Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePostForm" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="updatePostId" name="id">
                        <div class="mb-3">
                            <label for="updateRubrique" class="form-label">Rubrique</label>
                            <select class="form-control" id="updateRubrique" name="rubrique_id" required>
                                <option id="currentRubrique" selected></option>
                                @foreach ($rubriques as $rubrique)
                                    <option value="{{ $rubrique->id }}">{{ $rubrique->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateLibelle" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="updateLibelle" name="libelle" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="updateDescription" name="description" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="updateImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="updateImage" name="image">
                            <small class="text-danger d-block mt-1">⚠️ Ratio recommandé : <strong>16:9</strong> (ex : 1200×675 px). Une image trop haute risque d'avoir les visages coupés à l'affichage.</small>
                        </div>
                        <div class="mb-3">
                            <label for="updatedVideo" class="form-label">Lien de la vidéo</label>
                            <input type="text" class="form-control" id="updateVideo" name="video">
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var rubriqueSelect = document.getElementById('updateRubrique');
                        var videoInput = document.getElementById('updateVideo');

                        rubriqueSelect.addEventListener('change', function() {
                            var selectedRubrique = rubriqueSelect.options[rubriqueSelect.selectedIndex].text;
                            if (selectedRubrique === 'Reportage') {
                                videoInput.disabled = false;
                            } else {
                                videoInput.disabled = true;
                                videoInput.value = ''; // Clear the file input
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>



    <!-- jQuery (nécessaire pour DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JavaScript de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdn.tiny.cloud/1/x8yqfgtr6nfr1pqqwtj5noxr4sla24dbm2uj55o12kivvy2d/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            // Gestion de l'ouverture du modal de mise à jour
            $('#updatePostModal').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
                var postId = button.data('id'); // Récupère l'ID du post

                var modal = $(this);
                var form = modal.find('#updatePostForm');

                // Met à jour l'action du formulaire avec l'ID du post
                var actionUrl = "{{ url('articles/update') }}/" + postId;
                form.attr('action', actionUrl);

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var libelle = button.data('libelle');
                var description = button.data('description');
                var video = button.data('video');
                var rubriqueId = button.data('rubrique-id');
                var rubriqueName = button.data('rubrique-nom');


                var modal = $(this);

                modal.find('#updatePostId').val(id);
                modal.find('#updateLibelle').val(libelle);
                modal.find('#updateDescription').val(description);
                modal.find('#updateVideo').val(video);

                // Afficher l'ancienne rubrique comme sélectionnée
                modal.find('#currentRubrique').val(rubriqueId).text(rubriqueName);
                // Mettre à jour la rubrique sélectionnée
                modal.find('#updateRubrique').val(rubriqueId);
                // Met à jour TinyMCE avec la description récupérée
                tinymce.get('updateDescription').setContent(description);
            });



        });
    </script>

    <script>
        // Initialiser TinyMCE
        tinymce.init({
            selector: 'textarea',
            plugins: 'autolink lists link',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link',
            menubar: false,
            branding: false,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Sauvegarde le contenu dans le textarea
                });
            }
        });
    </script>



</body>

</html>
