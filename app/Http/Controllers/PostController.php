<?php

namespace App\Http\Controllers;

use App\Models\articleView;
use App\Models\biographie;
use App\Models\comment;
use App\Models\post;
use App\Models\rubrique;
use App\Models\User;
use App\Models\publicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\organization;
use App\Models\organization_social;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
  
class PostController extends Controller
{

    public function index()
    {
        return view('myBlog.create');
    }
    // Fonction de création d'un post


    public function create()
    {
        $organization = $this->getOrganizationBySubdomain();
        $rubriques = Rubrique::where('organization_id', $organization->id)->get();

        return view('posts.create', compact('rubriques', 'organization'));
    }

    public function store(Request $request)
    {
        Log::info('Début du traitement de la requête pour créer un post.', ['request_data' => $request->all()]);

        // Récupération de l'organisation
        $organization = $this->getOrganizationBySubdomain();
        Log::info('Organisation récupérée.', ['organization' => $organization]);

        // Validation des données
        try {
            $validatedData = $request->validate([
                'libelle' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:2048',
                'audio' => 'nullable|mimes:audio/mpeg,mp3,wav|max:2048',
                'video' => 'nullable|string',  // Champ vidéo comme texte
                'image_url' => 'nullable|url',
                'data_url' => 'nullable|url',
                'slug' => 'required|string|max:255|unique:posts,slug',
                'rubriques' => 'required|array',
            ]);
            Log::info('Validation réussie.', ['validated_data' => $validatedData]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Gestion des fichiers
        $imageName = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;


        // Création du post
        try {
            $post = Post::create([
                'libelle' => $validatedData['libelle'],
                'description' => $validatedData['description'],
                'image' => $imageName ? basename($imageName) : null,
                'audio' => null,
                'video' => $validatedData['video'],  // Stocke le lien vidéo
                'image_url' => $validatedData['image_url'],
                'data_url' => $validatedData['data_url'],
                'slug' => $validatedData['slug'],
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,  // Associer le post à l'organisation
            ]);
            Log::info('Post créé avec succès.', ['post' => $post]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du post.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la création du post.');
        }

        // Association des rubriques
        try {
            $post->rubriques()->sync($validatedData['rubriques']);
            Log::info('Rubriques associées avec succès.', ['rubriques' => $validatedData['rubriques']]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'association des rubriques.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'association des rubriques.');
        }

        return redirect()->back()->with('success', 'Post créé avec succès.');
    }

 
    public function update(Request $request, Post $post)
    {
        Log::info('Début du traitement de la requête pour mettre à jour le post.', ['post_id' => $post->id, 'request_data' => $request->all()]);

        // Récupération de l'organisation
        $organization = $this->getOrganizationBySubdomain();
        Log::info('Organisation récupérée.', ['organization' => $organization]);

        // Validation des données
        try {
            $validatedData = $request->validate([
                'libelle' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|max:2048',
                'audio' => 'nullable|mimes:audio/mpeg,mp3,wav|max:2048',
                'video' => 'nullable|string',  // Champ vidéo comme texte
                'image_url' => 'nullable|url',
                'data_url' => 'nullable|url',
                'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
                'rubriques' => 'required|array',
            ]);
            Log::info('Validation réussie.', ['validated_data' => $validatedData]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation.', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Mise à jour des fichiers si nécessaire
        if ($request->hasFile('image')) {
            try {
                // Supprimer l'ancien fichier image
                if ($post->image && Storage::disk('public')->exists('images/' . $post->image)) {
                    Storage::disk('public')->delete('images/' . $post->image);
                    Log::info('Ancien fichier image supprimé.', ['image' => $post->image]);
                }

                // Enregistrer la nouvelle image
                $post->image = basename($request->file('image')->store('images', 'public'));
                Log::info('Nouvelle image enregistrée.', ['image' => $post->image]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la gestion de l\'image.', ['error' => $e->getMessage()]);
            }
        }

        // Mise à jour du post
        try {
            $post->update([
                'libelle' => $validatedData['libelle'],
                'description' => $validatedData['description'],
                'image_url' => $validatedData['image_url'],
                'data_url' => $validatedData['data_url'],
                'slug' => $validatedData['slug'],
                'video' => $validatedData['video'],  // Met à jour le lien vidéo
            ]);
            Log::info('Post mis à jour avec succès.', ['post' => $post]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du post.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du post.');
        }

        // Association des rubriques
        try {
            $post->rubriques()->sync($validatedData['rubriques']);
            Log::info('Rubriques associées avec succès.', ['rubriques' => $validatedData['rubriques']]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'association des rubriques.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'association des rubriques.');
        }

        return redirect()->back()->with('success', 'Post mis à jour avec succès.');
    }


    //Pour voir une publication

    /*public function show($organization, $id)
    {
        // Log the parameters for debugging
        Log::info('Organization: ' . $organization);
        Log::info('Post ID: ' . $id);

        // Fetch the organization based on the subdomain
        $org = Organization::where('subdomain', $organization)->first();

        if (!$org) {
            Log::error('Organization not found with subdomain: ' . $organization);
            return redirect()->back()->with('error', 'Organization not found');
        }

        // Initialize variables
        $bio = null;
        $postsByUser = collect(); // Initialize as an empty collection

        // Fetch the post along with related data
        $post = Post::where('id', $id)
            ->with('rubriques', 'comments', 'user')
            ->first();

        if (!$post) {
            Log::error('Post not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Post not found');
        }

        // Get the organization for the post's user
        $organization = $post->user->organization;
        $subdomain = $organization->subdomain;

        // Fetch rubriques related to the subdomain
        $rubriquesGuest = Rubrique::whereHas('posts', function ($query) use ($subdomain) {
            $query->whereHas('user', function ($query) use ($subdomain) {
                $query->whereHas('organization', function ($query) use ($subdomain) {
                    $query->where('subdomain', $subdomain);
                });
            });
        })->get();

        // Check if user is authenticated 
        $authUser = auth()->user();

        if ($authUser) {
            // Fetch the organization and user for authenticated users
            $organization = $authUser->organization;
            $user = $organization->users->first(); // Assuming you want the first user in the organization
        } else {
            // Fetch the first user in the organization for guests
            $user = User::where('organization_id', $org->id)->first();
        }

        // Fetch biography if user is available
        if (isset($user)) {
            $bio = Biographie::where('user_id', $user->id)->first();
            $postsByUser = Post::where('user_id', $user->id)->get();
        }

        // Fetch comments for the post
        $comments = Comment::where('post_id', $post->id)->get();
        $socials = organization_social::where('organization_id', $organization->id)->get();

        $pub = publicite::where("space", "article.e-benin")->first();

        return view('myBlog.single-post', compact('post','pub','socials','rubriquesGuest', 'bio', 'postsByUser', 'comments', 'organization', 'subdomain'));
    }*/

   
    
    public function show($organization, $id)
    {
        // Log the parameters for debugging
        Log::info('Organization: ' . $organization);
        Log::info('Post ID: ' . $id);
    
        // Fetch the organization based on the subdomain
        $org = Organization::where('subdomain', $organization)->first();
    
        if (!$org) {
            Log::error('Organization not found with subdomain: ' . $organization);
            return redirect()->back()->with('error', 'Organization not found');
        }
    
        // Initialize variables
        $bio = null;
        $postsByUser = collect(); // Initialize as an empty collection
    
        // Fetch the post along with related data
        $post = Post::where('id', $id)
            ->with('rubriques', 'comments', 'user')
            ->first();
    
        if (!$post) {
            Log::error('Post not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Post not found');
        }
    
        // Get the organization for the post's user
        $organization = $post->user->organization;
        $subdomain = $organization->subdomain;
    
        // Fetch rubriques related to the subdomain
        $rubriquesGuest = Rubrique::whereHas('posts', function ($query) use ($subdomain) {
            $query->whereHas('user', function ($query) use ($subdomain) {
                $query->whereHas('organization', function ($query) use ($subdomain) {
                    $query->where('subdomain', $subdomain);
                });
            });
        })->get();
    
        // Check if user is authenticated 
        $authUser = auth()->user();
    
        if ($authUser) {
            // Fetch the organization and user for authenticated users
            $organization = $authUser->organization;
            $user = $organization->users->first(); // Assuming you want the first user in the organization
        } else {
            // Fetch the first user in the organization for guests
            $user = User::where('organization_id', $org->id)->first();
        }
    
        // Fetch biography if user is available
        if (isset($user)) {
            $bio = Biographie::where('user_id', $user->id)->first(); 
            $postsByUser = Post::where('user_id', $user->id)->get();
        }
    
        // Fetch comments for the post
        $comments = Comment::where('post_id', $post->id)->get();
        $socials = organization_social::where('organization_id', $organization->id)->get();
        $pub = publicite::where("space", "article.e-benin")->first();
    
        // Increment the view count for unique IPs within the last hour
        $ipAddress = request()->ip();
        $timeLimit = Carbon::now()->subHours(12);
    
        $recentView = ArticleView::where('article_id', $post->id)
                                 ->where('ip_address', $ipAddress)
                                 ->where('viewed_at', '>=', $timeLimit)
                                 ->exists();
    
        if (!$recentView) {
            ArticleView::create([
                'article_id' => $post->id,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ]);
            $post->increment('views');
        }
    
        // Get total view count for the post
        $viewCount = $post->views()->count();
    
        return view('myBlog.single-post', compact('post', 'pub', 'socials', 'rubriquesGuest', 'bio', 'postsByUser', 'comments', 'organization', 'subdomain', 'viewCount'));
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


    public function storeComment(Request $request, $postId)
    {
        Log::info('Début du traitement du commentaire', ['postId' => $postId]);

        // Récupérer l'organisation à partir du sous-domaine
        $subdomain = $this->getSubdomain();
        Log::info('Organisation récupérée', ['organization' => $subdomain]);

        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'bail|email|max:255',
            'comment' => 'required|string',
        ]);
        Log::info('Validation réussie');

        // Créer le commentaire
        $comment = new Comment([
            'reader_name' => $request->input('name'),
            'comments' => $request->input('comment'),
            'post_id' => $postId,
        ]);

        $comment->save();
        Log::info('Commentaire enregistré', ['comment' => $comment]);

        // Rediriger vers le post ou la page désirée
        return redirect()->route('single-post', ['organization' => $subdomain, 'id' => $postId]);
    }
}
