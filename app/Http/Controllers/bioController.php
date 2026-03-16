<?php

namespace App\Http\Controllers;

use App\Models\biographie;
use App\Models\organization;
use App\Models\organization_social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class bioController extends Controller
{

    public function store(Request $request)
    {
        try {
            $organization = Auth::user()->organization;

            // Validation des données
            $validator = Validator::make($request->all(), [
                'bio' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
            }

            // Gestion de l'avatar
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                try {
                    // Récupérer le fichier image
                    $avatar = $request->file('avatar');

                    // Générer un nom unique pour l'image
                    $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

                    // Définir le chemin de stockage dans le dossier public/images/logos
                    $destinationPath = public_path('images/logos');

                    // Créer le répertoire si nécessaire
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                        Log::info('Répertoire de destination créé.', ['path' => $destinationPath]);
                    }

                    // Déplacer le fichier vers le répertoire 'public/images/logos'
                    $avatar->move($destinationPath, $avatarName);
                    Log::info('Fichier avatar déplacé avec succès.', [
                        'new_filename' => $avatarName,
                        'destination_path' => $destinationPath . '/' . $avatarName
                    ]);

                    // Stocker le chemin relatif
                    $avatarPath = 'images/logos/' . $avatarName;
                } catch (\Exception $e) {
                    Log::error('Erreur lors du traitement de l\'avatar.', ['error' => $e->getMessage()]);
                    return response()->json(['status' => 'error', 'message' => 'Erreur lors du traitement de l\'avatar.'], 500);
                }
            }

            // Créer la biographie
            $biographie = Biographie::create([
                'bio' => $request->input('bio'),
                'avatar' => $avatarPath,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'Biographie créee avec succes!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la biographie: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la création de la biographie'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $organization = Auth::user()->organization;

            // Validation des données
            $validator = Validator::make($request->all(), [
                'bio' => 'required|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
            }

            // Trouver la biographie
            $biography = Biographie::findOrFail($id);

            // Gestion de l'avatar
            if ($request->hasFile('avatar')) {
                try {
                    // Supprimer l'ancien avatar s'il existe
                    if ($biography->avatar) {
                        $oldAvatarPath = public_path($biography->avatar);
                        if (file_exists($oldAvatarPath)) {
                            unlink($oldAvatarPath); // Supprimer l'ancien avatar
                            Log::info('Ancien avatar supprimé.', ['path' => $oldAvatarPath]);
                        }
                    }

                    // Récupérer le fichier image
                    $avatar = $request->file('avatar');

                    // Générer un nom unique pour l'image
                    $avatarName = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();

                    // Définir le chemin de stockage dans le dossier public/images/logos
                    $destinationPath = public_path('images/logos');

                    // Créer le répertoire si nécessaire
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                        Log::info('Répertoire de destination créé.', ['path' => $destinationPath]);
                    }

                    // Déplacer le fichier vers le répertoire 'public/images/logos'
                    $avatar->move($destinationPath, $avatarName);
                    Log::info('Nouveau fichier avatar déplacé avec succès.', [
                        'new_filename' => $avatarName,
                        'destination_path' => $destinationPath . '/' . $avatarName
                    ]);

                    // Mettre à jour le chemin relatif de l'avatar dans la base de données
                    $biography->avatar = 'images/logos/' . $avatarName;
                } catch (\Exception $e) {
                    Log::error('Erreur lors du traitement de l\'avatar.', ['error' => $e->getMessage()]);
                    return response()->json(['status' => 'error', 'message' => 'Erreur lors du traitement de l\'avatar.'], 500);
                }
            }

            // Mettre à jour la bio
            $biography->bio = $request->input('bio');
            $biography->save();

            return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'bio mis à jour avec succes!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la biographie: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la biographie'], 500);
        }
    }



    //organization
    public function updateOrg(Request $request, $id)
    {
        Log::info('Début de la mise à jour de l\'organisation', ['id' => $id]);

        // Valider les données de la requête
        try {
            $request->validate([
                'organization_name' => 'required|string|max:255',
                'organization_address' => 'nullable|string|max:255',
                'organization_phone' => 'nullable|string',
                'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'organization_email' => 'required|email|max:255',
            ]);
            Log::info('Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Échec de la validation', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Trouver l'organisation par ID
        try {
            $organization = Organization::findOrFail($id);
            Log::info('Organisation trouvée', ['organization' => $organization]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Organisation non trouvée', ['id' => $id]);
            return redirect()->back()->with('error', 'Organisation non trouvée.');
        }

        // Mettre à jour les champs avec les nouvelles valeurs
        $organization->organization_name = $request->input('organization_name');
        $organization->organization_address = $request->input('organization_address');
        $organization->organization_phone = $request->input('organization_phone');
        $organization->organization_email = $request->input('organization_email');
        Log::info('Champs mis à jour', $organization->toArray());

        // Gérer le fichier de logo s'il est fourni
        if ($request->hasFile('organization_logo')) {
            try {
                $file = $request->file('organization_logo');
                $originalFilename = $file->getClientOriginalName();
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('images/logos');

                // Vérifier si le répertoire de destination existe
                if (!file_exists($destinationPath)) {
                    Log::info('Création du répertoire de destination', ['path' => $destinationPath]);
                    mkdir($destinationPath, 0775, true);
                }

                // Déplacer le fichier vers le répertoire de destination
                $file->move($destinationPath, $filename);

                Log::info('Fichier déplacé avec succès', [
                    'original_filename' => $originalFilename,
                    'new_filename' => $filename,
                    'destination' => $destinationPath
                ]);

                // Supprimer l'ancien logo si nécessaire
                if ($organization->organization_logo) {
                    $oldLogoPath = public_path($organization->organization_logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                        Log::info('Ancien logo supprimé', ['path' => $oldLogoPath]);
                    } else {
                        Log::warning('Ancien logo non trouvé pour suppression', ['path' => $oldLogoPath]);
                    }
                }

                // Mettre à jour le chemin du nouveau logo dans la base de données
                $organization->organization_logo = 'images/logos/' . $filename;
                Log::info('Nouveau logo stocké', ['filename' => $organization->organization_logo]);
            } catch (\Exception $e) {
                Log::error('Erreur lors du traitement du logo', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Erreur lors du traitement du logo.');
            }
        }

        // Sauvegarder les modifications dans la base de données
        try {
            $organization->save();
            Log::info('Organisation mise à jour avec succès', ['organization' => $organization]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la sauvegarde de l\'organisation', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'organisation.');
        }

        return redirect()->route('dashboard', ['organization' => $organization->subdomain]);
    }


    //LES RESEAUX SOCIAUX
    public function storeSocial(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'social_id' => 'required|exists:socials,id',
            'url' => 'required|url',
        ]);

        $user = Auth::user();
        $organization = $user->organization;

        organization_social::create($request->all());

        return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', ' reseau social ajouté avec succes!');
    }
 
    // Méthode pour mettre à jour un réseau social existant
    public function updateSocial(Request $request, $id)
    {
        $user = Auth::user();
        $organization = $user->organization;


        $request->validate([
            'url' => 'required|url',
        ]);

        // Trouver l'enregistrement dans la table pivot à l'aide de l'ID
        $organizationSocial = organization_social::where('organization_id', $id)->first();

        // Mettre à jour uniquement l'URL
        $organizationSocial->update([
            'url' => $request->input('url'),
        ]);
        return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'Le lien de ' . $organizationSocial->social->nom . ' a ete mis a jour avec succes!');
    }



    private function getOrganizationBySubdomain()
    {
        $subdomain = $this->getSubdomain();
        $normalizedSubdomain = str_replace('-', ' ', $subdomain);
        return Organization::where('organization_name', $normalizedSubdomain)->firstOrFail();
    }

    private function getSubdomain()
    {
        $host = request()->getHost();
        $parts = explode('.', $host);
        return count($parts) > 2 ? $parts[0] : null;
    }
}
