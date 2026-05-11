<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Necrologie;
use App\Models\ReaderFavorite;
use App\Models\comment as Comment;
use App\Models\post as Post;
use App\Models\rubrique as Rubrique;
use App\Models\organization as Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    // ── Auth helper ──────────────────────────────────────────
    private function authUser(): ?object
    {
        return Auth::guard('reader')->user()
            ?? Auth::guard('web')->user()
            ?? Auth::guard('advertiser')->user()
            ?? Auth::guard('admin')->user();
    }

    private function authType(): string
    {
        if (Auth::guard('reader')->check())     return 'reader';
        if (Auth::guard('web')->check())        return 'web';
        if (Auth::guard('advertiser')->check()) return 'advertiser';
        return 'admin';
    }

    // ── Home / Feed ──────────────────────────────────────────
    public function home(Request $request)
    {
        $rubriqueId = $request->query('cat');

        $query = Post::published()
            ->with(['user.organization', 'rubriques'])
            ->orderByDesc('created_at');

        if ($rubriqueId) {
            $query->whereHas('rubriques', fn($q) => $q->where('rubriques.id', $rubriqueId));
        }

        $posts      = $query->paginate(15);
        $categories = Rubrique::whereHas('posts', fn($q) => $q->published())->get();
        $featured   = Post::published()->where('featured', 1)->with('user.organization')->latest()->take(5)->get();

        return view('reader.home', compact('posts', 'categories', 'featured', 'rubriqueId'));
    }

    // ── Article detail ───────────────────────────────────────
    public function article(int $id)
    {
        $post    = Post::with(['user.organization', 'rubriques', 'comments'])->findOrFail($id);
        $related = Post::published()
            ->whereHas('rubriques', fn($q) => $q->whereIn('rubriques.id', $post->rubriques->pluck('id')))
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        $isFavorited = false;
        $user = $this->authUser();
        if ($user) {
            $isFavorited = ReaderFavorite::where('user_type', $this->authType())
                ->where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->exists();
        }

        return view('reader.article', compact('post', 'related', 'isFavorited'));
    }

    // ── Toggle Favorite ──────────────────────────────────────
    public function toggleFavorite(int $id)
    {
        $user = $this->authUser();
        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'auth_required'], 401);
            }
            return redirect()->route('reader.login')->with('error', 'Connectez-vous pour enregistrer cet article.');
        }

        $post = Post::findOrFail($id);
        $type = $this->authType();

        $fav = ReaderFavorite::where('user_type', $type)
            ->where('user_id', $user->id)
            ->where('post_id', $id)
            ->first();

        if ($fav) {
            $fav->delete();
            $favorited = false;
        } else {
            ReaderFavorite::create(['user_type' => $type, 'user_id' => $user->id, 'post_id' => $id]);
            $favorited = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back();
    }

    // ── Add Comment ──────────────────────────────────────────
    public function addComment(Request $request, int $id)
    {
        $request->validate([
            'comments' => 'required|string|max:1000',
        ]);

        $user = $this->authUser();
        $name = $user?->name ?? $user?->organization_name ?? 'Anonyme';
        $mail = $user?->email ?? null;

        Comment::create([
            'post_id'     => $id,
            'reader_name' => $name,
            'reader_mail' => $mail,
            'comments'    => $request->input('comments'),
        ]);

        return back()->with('success', 'Commentaire publié.');
    }

    // ── Favorites list ───────────────────────────────────────
    public function favoris()
    {
        $user = $this->authUser();
        $type = $this->authType();

        $favorites = ReaderFavorite::where('user_type', $type)
            ->where('user_id', $user->id)
            ->with(['post.rubriques', 'post.user.organization'])
            ->latest()
            ->paginate(15);

        return view('reader.favoris', compact('favorites'));
    }

    // ── Annonces ─────────────────────────────────────────────
    public function annonces(Request $request)
    {
        $cat     = $request->query('cat');
        $query   = Annonce::where('status', 'active')->with('advertiser')->latest();

        if ($cat) $query->where('category', $cat);

        $annonces   = $query->paginate(16);
        $categories = Annonce::CATEGORIES;

        return view('reader.annonces.index', compact('annonces', 'categories', 'cat'));
    }

    public function annonceShow(Annonce $annonce)
    {
        abort_if($annonce->status !== 'active', 404);
        $similar = Annonce::where('status', 'active')
            ->where('category', $annonce->category)
            ->where('id', '!=', $annonce->id)
            ->latest()->take(4)->get();

        return view('reader.annonces.show', compact('annonce', 'similar'));
    }

    // ── Nécrologies ──────────────────────────────────────────
    public function necrologies()
    {
        $necrologies = Necrologie::where('status', 'active')->with('advertiser')->latest()->paginate(12);
        return view('reader.necrologies.index', compact('necrologies'));
    }

    public function necrologieShow(Necrologie $necrologie)
    {
        abort_if($necrologie->status !== 'active', 404);
        return view('reader.necrologies.show', compact('necrologie'));
    }

    // ── Catégories ───────────────────────────────────────────
    public function categories()
    {
        $categories = Rubrique::whereHas('posts', fn($q) => $q->published())
            ->withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->get();

        return view('reader.categories', compact('categories'));
    }

    // ── Profil ───────────────────────────────────────────────
    public function profile()
    {
        $user = $this->authUser();
        $type = $this->authType();

        $favCount     = ReaderFavorite::where('user_type', $type)->where('user_id', $user->id)->count();
        $commentCount = Comment::where('reader_mail', $user->email)->count();

        return view('reader.profile', compact('user', 'favCount', 'commentCount'));
    }
}
