<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Auth | E-Bénin</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: #fff; border-radius: 12px; padding: 40px 32px; max-width: 500px; width: 100%; box-shadow: 0 4px 20px rgba(0,0,0,.08); text-align: center; }
        .icon { font-size: 3rem; margin-bottom: 16px; }
        h1 { font-size: 1.3rem; font-weight: 700; color: #0f0f4b; margin-bottom: 12px; }
        p { color: #555; font-size: .95rem; line-height: 1.6; }
        .pages { margin-top: 20px; text-align: left; background: #f9f9f9; border-radius: 8px; padding: 12px 16px; font-size: .85rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">{{ $success ? '✅' : '⚠️' }}</div>
    <h1>{{ $success ? 'Connexion réussie !' : 'Problème détecté' }}</h1>
    <p>{{ $message }}</p>

    @if (!$success && count($pages) > 0)
        <div class="pages">
            <strong>Pages trouvées :</strong><br>
            @foreach ($pages as $p)
                • {{ $p['name'] }} (ID: {{ $p['id'] }})<br>
            @endforeach
        </div>
    @endif
</div>
</body>
</html>
