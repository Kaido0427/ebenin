<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'article | {{ $organization->organization_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css">
    <style>
        body { background: #f4f6f9; }
        .editor-wrap { max-width: 860px; margin: 0 auto; padding: 24px 16px 60px; }
        .editor-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.07); padding: 32px; }
        .editor-card h1 { font-size: 1.4rem; font-weight: 700; color: #0f0f4b; margin-bottom: 28px; }
        .form-label { font-weight: 600; color: #333; }
        .ql-container { min-height: 320px; font-size: 1rem; border-radius: 0 0 8px 8px !important; }
        .ql-toolbar { border-radius: 8px 8px 0 0 !important; }
        .ql-editor { min-height: 300px; }
        .hint { font-size: .8rem; color: #888; margin-top: 4px; }
        .topbar { background: #0f0f4b; padding: 12px 24px; display: flex; align-items: center; justify-content: space-between; }
        .topbar a { color: #fff; text-decoration: none; font-size: .9rem; opacity: .8; }
        .topbar a:hover { opacity: 1; }
        .topbar .brand { color: #fff; font-weight: 700; font-size: 1.1rem; opacity: 1; }
        .current-img { max-height: 140px; border-radius: 8px; margin-top: 8px; object-fit: cover; }
    </style>
</head>
<body>
<div class="topbar">
    <span class="brand">{{ $organization->organization_name }}</span>
    @php $baseDomain = str_contains(request()->getHost(), 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com'; @endphp
    <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/dashboard">← Retour au dashboard</a>
</div>

<div class="editor-wrap">
    <div class="editor-card">
        <h1>Modifier l'article</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('articles/update/' . $post->id) }}" enctype="multipart/form-data" id="articleForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Rubrique *</label>
                <select name="rubrique_id" class="form-select" required>
                    @foreach ($rubriques as $r)
                        <option value="{{ $r->id }}"
                            {{ $post->rubriques->first()?->id == $r->id ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Titre *</label>
                <input type="text" name="libelle" class="form-control" required value="{{ old('libelle', $post->libelle) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Accroche <span class="text-muted fw-normal">(phrase d'introduction courte)</span></label>
                <textarea name="sub_title" class="form-control" rows="2">{{ old('sub_title', $post->sous_titre) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Corps de l'article *</label>
                <div id="quill-editor"></div>
                <textarea name="description" id="description" style="display:none">{{ old('description', $post->description) }}</textarea>
                <p class="hint">Utilisez les boutons de la barre pour mettre en gras, créer des titres, des listes…</p>
            </div>

            <div class="mb-4">
                <label class="form-label">Photo de couverture</label>
                @if ($post->image)
                    <div>
                        <img src="{{ asset($post->image) }}" class="current-img" alt="Image actuelle">
                        <p class="hint">Image actuelle — choisir un nouveau fichier pour la remplacer</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control mt-2" accept="image/jpeg,image/png,image/webp">
                <p class="hint">Formats acceptés : JPEG, PNG, WEBP — Ratio idéal 16:9 (ex : 1200×675 px)</p>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5" style="background:#0f0f4b;border-color:#0f0f4b;">
                    Enregistrer les modifications
                </button>
                <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/dashboard" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<script>
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            ['bold', 'italic', 'underline'],
            [{ 'header': [2, 3, false] }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['blockquote'],
            ['clean']
        ]
    }
});

// Charger le contenu existant
const content = document.getElementById('description').value;
if (content) {
    quill.clipboard.dangerouslyPasteHTML(content);
}

document.getElementById('articleForm').addEventListener('submit', function() {
    document.getElementById('description').value = quill.getSemanticHTML();
});
</script>
</body>
</html>
