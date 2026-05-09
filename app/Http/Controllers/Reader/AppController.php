<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Necrologie;
use App\Models\Post;
use App\Models\Rubrique;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
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

        return view('reader.article', compact('post', 'related'));
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

    // ── Profil ───────────────────────────────────────────────
    public function profile()
    {
        $user = Auth::guard('reader')->user()
             ?? Auth::guard('web')->user()
             ?? Auth::guard('advertiser')->user()
             ?? Auth::guard('admin')->user();

        return view('reader.profile', compact('user'));
    }
}
