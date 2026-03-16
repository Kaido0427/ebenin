<?php

namespace App\Http\Controllers;

use App\Models\organization;
use App\Models\post;
use App\Models\post_rubrique;
use App\Models\rubrique;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class articleController extends Controller
{
    // Afficher tous les posts sur la vue index avec les modals
    /* public function index()
    {
        $organization = $this->getOrganizationBySubdomain();
        $posts = post::with('user', 'rubriques')->get();
        $rubriques = rubrique::all();
        dd($posts);
        return view('myBlog.articles.index', compact('posts', 'rubriques', 'organization'));
    }*/

    // Créer un nouveau post
    public function store(Request $request)
    {
        try {
            $organization = Auth::user()->organization;
    
            // Validation des données
            $validator = Validator::make($request->all(), [
                'description' => 'required|string',
                'libelle' => 'required|string|max:255',
                'sub_title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
                'rubrique_id' => 'required',
                'video' => 'nullable', // Validation pour la vidéo
                'necro_video' => 'nullable' // Validation pour la vidéo de nécrologie
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
            }
    
            // Créer le post
            $post = new Post([
                'description' => $request->input('description'),
                'libelle' => $request->input('libelle'),
                'sous_titre' => $request->input('sub_title'),
                'user_id' => auth()->id(),
                'image' => null, // Initialiser avec null au cas où aucune image n'est téléchargée
                'video' => $request->input('video') ?? null,
                'necro_video' => null, // Initialiser avec null au cas où aucune vidéo de nécrologie n'est téléchargée
            ]);
    
            if ($request->hasFile('image')) { 
                // Récupérer le fichier image
                $image = $request->file('image');
    
                // Générer un nom unique pour l'image
                $imageName = time() . '.' . $image->getClientOriginalExtension();
    
                // Définir le chemin de stockage dans le dossier public/uploads/posts/images
                $destinationPath = public_path('uploads/posts/images');
    
                // Déplacer l'image vers le dossier public
                $image->move($destinationPath, $imageName);
    
                // Enregistrer le chemin relatif dans la base de données
                $post->image = 'uploads/posts/images/' . $imageName;
            }
    
    
            if ($request->hasFile('necro_video')) {
                // Récupérer le fichier vidéo de nécrologie
                $necroVideo = $request->file('necro_video');
    
                // Générer un nom unique pour la vidéo de nécrologie
                $necroVideoName = time() . '.' . $necroVideo->getClientOriginalExtension();
    
                // Définir le chemin de stockage dans le dossier public/uploads/posts/necro_videos
                $destinationPath = public_path('uploads/posts/necro_videos');
    
                // Déplacer la vidéo vers le dossier public
                $necroVideo->move($destinationPath, $necroVideoName);
    
                // Enregistrer le chemin relatif dans la base de données
                $post->necro_video = 'uploads/posts/necro_videos/' . $necroVideoName;
            }
    
            $post->save();
    
            // Créer la relation entre le post et la rubrique
            post_rubrique::create([
                'post_id' => $post->id, 
                'rubrique_id' => $request->input('rubrique_id'),
            ]);
    
            return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'Article créé avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du post: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la création du post'], 500);
        }
    }
    


    // Mettre à jour un post existant
    public function update(Request $request, $id)
    {
        try {
            $organization = Auth::user()->organization;
            // Validation des données
            $validator = Validator::make($request->all(), [
                'description' => 'required|string',
                'libelle' => 'required|string|max:255',
                'sub_title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
                'video' => 'bail',
                'rubrique_id' => 'required|exists:rubriques,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
            }

            // Trouver le post
            $post = Post::findOrFail($id);

            // Mettre à jour les données du post
            $post->update([
                'description' => $request->input('description'),
                'libelle' => $request->input('libelle'),
                'sous_titre' => $request->input('sub_title'),
                'video' => $request->input('video') ?? null,
            ]);

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($post->image) {
                    $oldImagePath = public_path($post->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath); // Supprimer l'ancienne image
                    }
                }

                // Récupérer le fichier image 
                $image = $request->file('image'); 

                // Générer un nom unique pour l'image
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Définir le chemin de stockage dans le dossier public/uploads/posts/images
                $destinationPath = public_path('uploads/posts/images');
                $image->move($destinationPath, $imageName);

                // Mettre à jour le chemin relatif de l'image dans la base de données
                $post->image = 'uploads/posts/images/' . $imageName;
                $post->save();
            }

            // Mise à jour de la rubrique
            $post->rubriques()->sync([$request->input('rubrique_id')]);

            return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'Article mis à jour avec succes!');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du post: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Erreur lors de la mise à jour du post'], 500);
        }
    }

    private function getOrganizationBySubdomain()
    {
        $subdomain = $this->getSubdomain();
        $normalizedSubdomain = str_replace('-', ' ', $subdomain);
        return organization::where('organization_name', $normalizedSubdomain)->firstOrFail();
    }

    private function getSubdomain()
    {
        $host = request()->getHost();
        $parts = explode('.', $host);
        return count($parts) > 2 ? $parts[0] : null;
    }
}
 