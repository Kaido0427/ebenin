<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .header {
            background-color: rgb(11, 25, 151);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .content {
            padding: 30px;
            border: 1px solid #e9e9e9;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .password-box {
            background-color: #f8f8f8;
            border: 1px solid #e3e3e3;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }

        .warning {
            color: #e74c3c;
            font-weight: bold;
            margin-top: 20px;
            font-size: 14px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="https://e-benin.bj/images/e-benin.png" alt="E-Benin" width="150">
            </div>
            <h1>{{ $siteName }}</h1>
        </div>
        <div class="content">
            <h2>
                {{ date('H') < 18 ? 'Bonjour' : 'Bonsoir' }}
                {{ isset($user->name) ? $user->name : 'cher utilisateur' }},
            </h2>

            <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre organisation <strong>{{ $user->organization->subdomain }}</strong>  sur <strong>{{ $siteName }}</strong>.</p>

            <p>Voici votre nouveau mot de passe :</p>

            <div class="password-box">
                {{ $password }}
            </div>

            <p>Nous vous recommandons de changer ce mot de passe dès votre prochaine connexion.</p>

            <div style="text-align: center;">
                <a href="https://e-benin.bj" class="button">Se connecter maintenant</a>
            </div>

            <p class="warning">Si vous n'avez pas demandé cette réinitialisation, ignorez ce message.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $siteName }} - Tous droits réservés</p>
            <p>Ce message est automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>

</html>