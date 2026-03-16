@forelse ($posts as $post)
    <tr>
        <td>{{ \Illuminate\Support\Str::limit($post->libelle, 40, $end = '...') }}
        </td>
        <td>
            <a href="#" class="btn btn-warning btn-sm post-dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false" data-post-id="{{ $post->id }}">
                Modifier
            </a>
            <div class="dropdown-menu dropdown-menu-end p-4" style="min-width: 800px; max-width: 600px; z-index: 1050;">
                <form class="updatePostForm" method="POST" action="{{ url('articles/update') }}/{{ $post->id }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="updatePostId" name="id" value="{{ $post->id }}">

                    <div class="mb-3">
                        <label for="updateRubrique_{{ $post->id }}" class="form-label">Catégorie</label>
                        <select class="form-control updateRubrique" name="rubrique_id" required>
                            <option value="{{ $post->rubriques->first()->id }}" selected>
                                {{ $post->rubriques->first()->name }}
                            </option>
                            @foreach ($rubriques as $rubrique)
                                @if ($rubrique->id !== $post->rubriques->first()->id)
                                    <option value="{{ $rubrique->id }}">
                                        {{ $rubrique->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="updateLibelle_{{ $post->id }}" class="form-label">Titre de
                            l'article</label>
                        <input type="text" class="form-control updateLibelle" name="libelle"
                            value="{{ $post->libelle }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="updatesubTitle_{{ $post->id }}" class="form-label">Sous Titre</label>
                        <input type="text" class="form-control updatesubTitle" name="sub_title"
                            value="{{ $post->sous_titre }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="updateDescription_{{ $post->id }}" class="form-label">Description</label>
                        <textarea class="form-control updateDescription" name="description" rows="4" required>{{ $post->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="updateImage_{{ $post->id }}" class="form-label">Image</label>
                        <input type="file" class="form-control updateImage" name="image">
                    </div>

                    <div class="mb-3">
                        <label for="updateVideo_{{ $post->id }}" class="form-label">Lien de la vidéo</label>
                        <input type="text" class="form-control updateVideo" name="video"
                            value="{{ $post->video }}">
                    </div>

                    <div class="mb-3">
                        <label for="updateNecroVideo_{{ $post->id }}" class="form-label">Vidéo de
                            nécrologie</label>
                        <input type="file" class="form-control updateNecroVideo" name="necro_video">
                    </div>

                    <div class="mb-3 currentNecroVideo">
                        @if ($post->necro_video)
                            Fichier actuel : {{ $post->necro_video }}
                        @else
                            Aucun fichier actuellement
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 updatePostSubmit">Mettre
                        à jour l'article</button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="2" class="text-center">Aucun Article publié!
        </td>
    </tr>
@endforelse
