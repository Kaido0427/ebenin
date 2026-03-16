<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\publicite;
use Illuminate\Support\Facades\File;

class pubController extends Controller
{
    // Fonction pour créer une publicité
    public function create(Request $request)
    {
        
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'required|string',
            'space' => 'required|string',
        ]);

        // Créer la publicité sans l'image pour l'instant
        $publicite = Publicite::create([
            'url' => $request->url,
            'space' => $request->space,
        ]);

        if ($request->hasFile('image')) {
            // Récupérer le fichier image
            $image = $request->file('image');

            // Générer un nom unique pour l'image
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Définir le chemin de stockage dans le dossier public/uploads/pubs/images
            $destinationPath = public_path('uploads/pubs/');


            // Déplacer l'image vers le dossier public
            $image->move($destinationPath, $imageName);

            // Enregistrer le chemin relatif dans la base de données
            $publicite->image = 'uploads/pubs/' . $imageName;
            $publicite->save();
        }

        return redirect()->back()->with('success', "Baniere créee avec succes!");
    }

    // Fonction pour mettre à jour une publicité
    public function update(Request $request, $id)
    {
        $publicite = Publicite::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'required|string',
            'space' => 'required|string',
        ]);

        // Mise à jour des autres champs
        $publicite->url = $request->url;
        $publicite->space = $request->space;

        if ($request->hasFile('image')) {
            // Suppression de l'ancienne image si elle existe
            $oldImagePath = public_path($publicite->image);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Récupérer la nouvelle image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/pubs/');

            // Créer le répertoire s'il n'existe pas
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Déplacer la nouvelle image
            $image->move($destinationPath, $imageName);

            // Enregistrer le nouveau chemin de l'image dans la base de données
            $publicite->image = 'uploads/pubs/' . $imageName;
        }

        $publicite->save();

        return redirect()->back()->with('success', "Baniere mise à jour  avec succes!");
    }
 

    // Fonction pour supprimer une publicité
    public function delete($id)
    {
        $publicite = Publicite::findOrFail($id);

        // Suppression de l'image associée
        $imagePath = public_path($publicite->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Suppression de la publicité
        $publicite->delete();

        return redirect()->back()->with('success', "Baniere supprimé avec succes!");
    }
}
