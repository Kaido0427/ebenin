<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel article | {{ $organization->organization_name }}</title>
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
        <h1>Nouvel article</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)<p class="mb-1">{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" id="articleForm">
            @csrf

            <div class="mb-3">
                <label class="form-label">Rubrique *</label>
                <select name="rubrique_id" class="form-select" required>
                    <option value="">— Choisir une rubrique —</option>
                    @foreach ($rubriques as $r)
                        <option value="{{ $r->id }}" {{ old('rubrique_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Titre *</label>
                <input type="text" name="libelle" class="form-control" required value="{{ old('libelle') }}"
                    placeholder="Ex : Prof. Igue, un pionnier de la culture yoruba">
            </div>

            <div class="mb-3">
                <label class="form-label">Accroche <span class="text-muted fw-normal">(phrase d'introduction courte)</span></label>
                <textarea name="sub_title" class="form-control" rows="2"
                    placeholder="Une ou deux phrases qui donnent envie de lire l'article…">{{ old('sub_title') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Corps de l'article *</label>
                <div id="quill-editor"></div>
                <textarea name="description" id="description" style="display:none">{{ old('description') }}</textarea>
                <p class="hint">Utilisez les boutons de la barre pour mettre en gras, créer des titres, des listes…</p>
            </div>

            <div class="mb-4">
                <label class="form-label">Photo de couverture</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <p class="hint">Formats acceptés : JPEG, PNG, WEBP — Ratio idéal 16:9 (ex : 1200×675 px)</p>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5" style="background:#0f0f4b;border-color:#0f0f4b;">
                    Publier l'article
                </button>
                @php $baseDomain2 = str_contains(request()->getHost(), 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com'; @endphp
                <a href="https://{{ $organization->subdomain }}.{{ $baseDomain2 }}/dashboard" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<script>
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    placeholder: 'Rédigez votre article ici…',
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

// Pré-remplir si old('description') existe
const oldContent = document.getElementById('description').value;
if (oldContent) {
    quill.clipboard.dangerouslyPasteHTML(oldContent);
}

// Avant soumission : copier le contenu Quill dans le textarea caché
document.getElementById('articleForm').addEventListener('submit', function() {
    document.getElementById('description').value = quill.getSemanticHTML();
});
</script>
</body>
</html>
