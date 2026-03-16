<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\organization;
use App\Models\user_organization;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255|unique:organizations,organization_name',
            'organization_email' => 'required|string|email|max:255|unique:organizations,organization_email',
            'organization_address' => 'nullable|string|max:255',
            'organization_phone' => 'nullable|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);
    }

    public function register(Request $request)
    {
        try {
            // Valider la requête
            Log::info('Début de la validation des données du formulaire.', ['request' => $request->all()]);
            $this->validator($request->all())->validate();
            Log::info('Validation des données du formulaire réussie.');

            // Normalisation du nom de l'organisation pour le sous-domaine
            $organizationName = $request->input('organization_name');

            $organizationName = trim($organizationName);
            // Supprimer les espaces
            $organizationName = str_replace(' ', '-', $organizationName);

            // Convertir en minuscules
            $subdomain = strtolower($organizationName);

            // Nettoyer les espaces au début et à la fin
            $subdomain = trim($subdomain);
            Log::info('Sous-domaine normalisé.', ['organization_name' => $organizationName, 'subdomain' => $subdomain]);

            // Gérer le fichier logo s'il est fourni
            $logoPath = null;
            if ($request->hasFile('organization_logo')) {
                try {
                    $file = $request->file('organization_logo');
                    $originalFilename = $file->getClientOriginalName();
                    $timestamp = time();
                    $extension = $file->getClientOriginalExtension();
                    $filename = $timestamp . '.' . $extension;
                    $destinationPath = public_path('images/logos');

                    // Créer le répertoire si nécessaire
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                        Log::info('Répertoire de destination créé.', ['path' => $destinationPath]);
                    }

                    // Déplacer le fichier vers le répertoire 'public/images/logos'
                    $file->move($destinationPath, $filename);
                    Log::info('Fichier de logo déplacé avec succès.', [
                        'original_filename' => $originalFilename,
                        'new_filename' => $filename,
                        'destination_path' => $destinationPath . '/' . $filename
                    ]);

                    $logoPath = 'images/logos/' . $filename;
                } catch (\Exception $e) {
                    Log::error('Erreur lors du traitement du logo.', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors du traitement du logo.'
                    ], 500);
                }
            }

            // Stocker les informations dans la session
            session()->put('registration_data', [
                'organization_name' => $request->input('organization_name'),
                'organization_email' => $request->input('organization_email'),
                'organization_address' => $request->input('organization_address'),
                'organization_phone' => $request->input('organization_phone'),
                'organization_logo' => $logoPath,
                'subdomain' => $subdomain,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);

            // Répondre avec succès pour lancer le paiement
            Log::info('Réponse JSON envoyée pour le lancement du paiement.', [
                'amount' => 10000,
                'organization_subdomain' => $subdomain
            ]);

            return response()->json([
                'success' => true,
                'amount' => 10000,
                'organization' => $subdomain,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement. ' . $e->getMessage(),
            ], 500);
        }
    }

    /* public function register(Request $request)
    {
        try {
            // Valider la requête
            Log::info('Début de la validation des données du formulaire.', ['request' => $request->all()]);
            $this->validator($request->all())->validate();
            Log::info('Validation des données du formulaire réussie.');

            // Normalisation du nom de l'organisation pour le sous-domaine
            $organizationName = $request->input('organization_name');
            $organizationName = trim($organizationName);
            $organizationName = str_replace(' ', '-', $organizationName);  // Remplace les espaces par des tirets
            $subdomain = strtolower($organizationName);  // Convertit en minuscules

            // Gérer le fichier logo s'il est fourni
            $logoPath = null;
            if ($request->hasFile('organization_logo')) {
                try {
                    $file = $request->file('organization_logo');
                    $timestamp = time();
                    $extension = $file->getClientOriginalExtension();
                    $filename = $timestamp . '.' . $extension;
                    $destinationPath = public_path('images/logos');

                    // Créer le répertoire si nécessaire
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                        Log::info('Répertoire de destination créé.', ['path' => $destinationPath]);
                    }

                    // Déplacer le fichier vers le répertoire 'public/images/logos'
                    $file->move($destinationPath, $filename);
                    $logoPath = 'images/logos/' . $filename;
                    Log::info('Fichier de logo déplacé avec succès.', [
                        'new_filename' => $filename,
                        'destination_path' => $destinationPath . '/' . $filename
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur lors du traitement du logo.', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors du traitement du logo.'
                    ], 500);
                }
            }

            // Enregistrement des informations dans la base de données
            $organization = new Organization();
            $organization->organization_name = $request->input('organization_name');
            $organization->organization_email = $request->input('organization_email');
            $organization->organization_address = $request->input('organization_address');
            $organization->organization_phone = $request->input('organization_phone');
            $organization->organization_logo = $logoPath;
            $organization->subdomain = $subdomain;
            $organization->save();

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->organization_id = $organization->id;  // Associe l'utilisateur à l'organisation
            $user->save();

            user_organization::create([
                'user_id' => $user->id,
                'organization_id' => $organization->id,
            ]);

               // Mise à jour du champ updated_at avec ajout d'un mois
            $currentUpdatedAt = $user->updated_at;
            Log::info('Date updated_at avant mise à jour', ['updated_at' => $currentUpdatedAt]);


            // Calcul explicite pour mettre à jour updated_at
            $currentDate = now(); // Date actuelle
            $newExpiryDate = now()->addMonth(); // Ajouter un mois
            $user->timestamps = false; // Désactiver la mise à jour auto de Laravel
            $user->updated_at = $newExpiryDate;
            $user->save();
            $user->timestamps = true; // Réactiver la gestion auto des timestamps
            

            Log::info('Date updated_at mise à jour avec succès.', [
                'old_date' => $currentDate->toDateTimeString(),
                'new_date' => $newExpiryDate->toDateTimeString(),
            ]);


            Auth::login($user);

            // Redirection vers le tableau de bord de l'organisation
            return redirect()->to("https://{$subdomain}.e-benin.com/dashboard");
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return redirect()->route('register')->with('error', $e->getMessage());
        }
    }
*/

    protected function redirectTo($request)
    {
        $user = auth()->user();
        $organization = $user->organization;
        $subdomain = $organization->subdomain;

        // Déterminer le domaine de base utilisé (.com ou .bj)
        $host = $request->getHost();
        $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

        return "https://{$subdomain}.{$baseDomain}/dashboard";
    }
}
