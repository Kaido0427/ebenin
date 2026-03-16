<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function forgotPasswordForm()
    {
        return view('forgot');
    }
    public function forgotPassword(Request $request)
    {
        try {
            // Validation du formulaire
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $user = User::where('email', $request->email)->first();

            // Générer un nouveau mot de passe alphanumérique plus lisible
            $newPassword = $this->generateReadablePassword();

            // Mettre à jour le mot de passe de l'utilisateur en utilisant bcrypt
            $user->password = bcrypt($newPassword);
            $user->save();

            // Récupérer le nom du site depuis les paramètres ou utiliser une valeur par défaut
            $siteName = config('app.name', 'E-Benin');

            // Envoyer un email HTML bien formaté
            Mail::send('reset', [
                'user' => $user,
                'password' => $newPassword,
                'siteName' => $siteName
            ], function ($message) use ($user, $siteName) {
                $message->to($user->email)
                    ->subject('Réinitialisation de votre mot de passe - ' . $siteName);
            });

            return response()->json([
                'success' => true,
                'message' => 'Un nouveau mot de passe a été envoyé à votre adresse e-mail.'
            ]);
        } catch (ValidationException $e) {
            // Gestion spécifique des erreurs de validation
            return response()->json([
                'success' => false,
                'message' => 'Adresse e-mail non reconnue. Veuillez vérifier votre saisie.'
            ], 422);
        } catch (\Exception $e) {
            // Enregistrer l'erreur détaillée dans les logs
            Log::error('Erreur lors de la réinitialisation du mot de passe: ' . $e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la réinitialisation du mot de passe. Veuillez réessayer ultérieurement.'
            ], 500);
        }
    }

    /**
     * Génère un mot de passe aléatoire lisible
     * 
     * @return string
     */
    private function generateReadablePassword()
    {
        // Caractères facilement lisibles (sans caractères ambigus comme 0/O ou 1/l)
        $consonnes = 'bcdfghjkmnpqrstvwxz';
        $voyelles = 'aeiu';
        $chiffres = '23456789';
        $speciaux = '!@#$%^&*';

        // Structure : consonne-voyelle-consonne-voyelle-chiffre-chiffre-special
        $password = '';

        // 4 caractères alternant consonnes et voyelles
        $password .= $consonnes[rand(0, strlen($consonnes) - 1)];
        $password .= $voyelles[rand(0, strlen($voyelles) - 1)];
        $password .= $consonnes[rand(0, strlen($consonnes) - 1)];
        $password .= $voyelles[rand(0, strlen($voyelles) - 1)];

        // 2 chiffres
        $password .= $chiffres[rand(0, strlen($chiffres) - 1)];
        $password .= $chiffres[rand(0, strlen($chiffres) - 1)];

        // 1 caractère spécial
        $password .= $speciaux[rand(0, strlen($speciaux) - 1)];

        return $password;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect.'], 400);
        }

        // Utiliser bcrypt pour le nouveau mot de passe
        $user->password = bcrypt($request->new_password);
        $user->save();

        // Déconnecter l'utilisateur après la mise à jour du mot de passe
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Déterminer le domaine de base utilisé (.com ou .bj)
        $host = $request->getHost();
        $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

        // Redirection vers le domaine principal correspondant
        return redirect()->away("https://{$baseDomain}");
    }
}
