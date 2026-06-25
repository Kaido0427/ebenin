<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session expirée | E-Bénin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.08); padding: 40px 32px; max-width: 420px; width: 100%; text-align: center; }
        .icon { font-size: 3rem; margin-bottom: 16px; }
        h1 { font-size: 1.3rem; font-weight: 700; color: #0f0f4b; margin-bottom: 10px; }
        p { font-size: .95rem; color: #555; line-height: 1.6; margin-bottom: 8px; }
        .steps { background: #f4f6f9; border-radius: 10px; padding: 16px 20px; margin: 20px 0; text-align: left; }
        .steps li { font-size: .88rem; color: #333; margin-bottom: 8px; padding-left: 4px; }
        .steps li:last-child { margin-bottom: 0; }
        .btn { display: inline-block; background: #0f0f4b; color: #fff; padding: 13px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: .95rem; margin-top: 8px; }
        .btn:hover { background: #1a1a6e; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⏱️</div>
        <h1>Votre session a expiré</h1>
        <p>La page est restée ouverte trop longtemps sans activité. C'est une mesure de sécurité normale.</p>
        <div class="steps">
            <ol>
                <li>Appuyez sur le bouton ci-dessous pour revenir en arrière</li>
                <li>Rafraîchissez la page (tirez vers le bas ou appuyez sur ↺)</li>
                <li>Remplissez à nouveau le formulaire et soumettez rapidement</li>
            </ol>
        </div>
        <a href="javascript:history.back()" class="btn">← Retour à la page précédente</a>
    </div>
</body>
</html>
