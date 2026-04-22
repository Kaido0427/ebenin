<?php

namespace App\Http\Controllers;

use App\Models\organization;
use App\Models\post;
use App\Models\post_rubrique;
use App\Models\rubrique;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
                'necro_video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo'
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
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
                File::ensureDirectoryExists($destinationPath);
    
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
                File::ensureDirectoryExists($destinationPath);
    
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
            Log::error('Erreur lors de la création du post', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du post. Vérifiez votre image et réessayez.');
        }
    }
    


    // Mettre à jour un post existant
    public function update(Request $request, $organizationOrId, $id = null)
    {
        try {
            $organization = Auth::user()->organization;
            $postId = $this->resolvePostId($organizationOrId, $id);
            // Validation des données
            $validator = Validator::make($request->all(), [
                'description' => 'required|string',
                'libelle' => 'required|string|max:255',
                'sub_title' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
                'video' => 'bail',
                'necro_video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo',
                'rubrique_id' => 'required|exists:rubriques,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Trouver le post et vérifier qu'il appartient à l'utilisateur connecté
            $post = $this->getOwnedPostOrFail($postId);

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
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                // Récupérer le fichier image 
                $image = $request->file('image'); 

                // Générer un nom unique pour l'image
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Définir le chemin de stockage dans le dossier public/uploads/posts/images
                $destinationPath = public_path('uploads/posts/images');
                File::ensureDirectoryExists($destinationPath);
                $image->move($destinationPath, $imageName);

                // Mettre à jour le chemin relatif de l'image dans la base de données
                $post->image = 'uploads/posts/images/' . $imageName;
                $post->save();
            }

            if ($request->hasFile('necro_video')) {
                if (!empty($post->necro_video)) {
                    $oldVideoPath = public_path($post->necro_video);
                    if (File::exists($oldVideoPath)) {
                        File::delete($oldVideoPath);
                    }
                }

                $necroVideo = $request->file('necro_video');
                $necroVideoName = time() . '_' . uniqid() . '.' . $necroVideo->getClientOriginalExtension();
                $videoDestinationPath = public_path('uploads/posts/necro_videos');
                File::ensureDirectoryExists($videoDestinationPath);
                $necroVideo->move($videoDestinationPath, $necroVideoName);

                $post->necro_video = 'uploads/posts/necro_videos/' . $necroVideoName;
                $post->save();
            }

            // Mise à jour de la rubrique
            $post->rubriques()->sync([$request->input('rubrique_id')]);

            return redirect()->route('dashboard', ['organization' => $organization->subdomain])->with('success', 'Article mis à jour avec succes!');
        } catch (\Exception $e) {
            if ($e instanceof HttpExceptionInterface) {
                throw $e;
            }
            Log::error('Erreur lors de la mise à jour du post', [
                'post_id' => $postId ?? null,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du post.');
        }
    }

    public function destroy($organizationOrId, $id = null)
    {
        try {
            $organization = Auth::user()->organization;
            $postId = $this->resolvePostId($organizationOrId, $id);
            $post = $this->getOwnedPostOrFail($postId);

            if (!empty($post->image)) {
                $imagePath = public_path($post->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            if (!empty($post->necro_video)) {
                $videoPath = public_path($post->necro_video);
                if (File::exists($videoPath)) {
                    File::delete($videoPath);
                }
            }

            $post->rubriques()->detach();
            $post->delete();

            return redirect()->route('dashboard', ['organization' => $organization->subdomain])
                ->with('success', 'Article supprimé avec succes!');
        } catch (\Exception $e) {
            if ($e instanceof HttpExceptionInterface) {
                throw $e;
            }
            Log::error('Erreur lors de la suppression du post: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la suppression du post');
        }
    }

    private function resolvePostId($organizationOrId, $id = null)
    {
        if ($id !== null) {
            return $id;
        }

        return $organizationOrId;
    }

    private function getOwnedPostOrFail($id)
    {
        $post = Post::findOrFail($id);

        abort_if((int) $post->user_id !== (int) auth()->id(), 403, 'Action non autorisée.');

        return $post;
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
 
