<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\comment;
use App\Models\Necrologie;
use App\Models\post;
use App\Models\ReaderFavorite;
use App\Models\rubrique;
use Illuminate\Http\Request;

class ReaderAppController extends Controller
{
    // ── Helpers ───────────────────────────────────────────────

    private function postResource(post $p, ?int $userId = null): array
    {
        $image = $p->image ?? '';
        if ($image && !str_starts_with($image, 'http')) {
            $image = asset($image);
        }

        $favorited = false;
        if ($userId) {
            $favorited = ReaderFavorite::where('user_type', 'reader')
                ->where('user_id', $userId)
                ->where('post_id', $p->id)
                ->exists();
        }

        return [
            'id'           => $p->id,
            'title'        => $p->libelle,
            'subtitle'     => $p->sous_titre,
            'excerpt'      => \Str::limit(html_entity_decode(strip_tags($p->description ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 160),
            'image'        => $image ?: null,
            'category'     => $p->rubriques->first()?->name,
            'category_id'  => $p->rubriques->first()?->id,
            'organization' => $p->user?->organization?->organization_name,
            'subdomain'    => $p->user?->organization?->subdomain,
            'published_at' => $p->created_at?->diffForHumans(),
            'favorited'    => $favorited,
            'comments'     => $p->comments?->count() ?? 0,
        ];
    }

    // ── Articles ──────────────────────────────────────────────

    public function articles(Request $request)
    {
        $userId     = $request->user()?->id;
        $rubriqueId = $request->query('category');
        $search     = $request->query('q');

        $query = post::published()
            ->with(['user.organization', 'rubriques', 'comments'])
            ->orderByDesc('created_at');

        if ($rubriqueId) {
            $query->whereHas('rubriques', fn($q) => $q->where('rubriques.id', $rubriqueId));
        }

        if ($search) {
            $query->where(fn($q) => $q
                ->where('libelle', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
            );
        }

        $paginator = $query->paginate(15);

        return response()->json([
            'data'       => $paginator->getCollection()->map(fn($p) => $this->postResource($p, $userId)),
            'pagination' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ]);
    }

    public function article(Request $request, int $id)
    {
        $userId = $request->user()?->id;

        $post = post::published()
            ->with(['user.organization', 'rubriques', 'comments'])
            ->findOrFail($id);

        $related = post::published()
            ->whereHas('rubriques', fn($q) => $q->whereIn('rubriques.id', $post->rubriques->pluck('id')))
            ->where('posts.id', '!=', $id)
            ->with(['user.organization', 'rubriques'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $comments = $post->comments->map(fn($c) => [
            'id'         => $c->id,
            'name'       => $c->reader_name,
            'body'       => $c->comments,
            'created_at' => $c->created_at?->diffForHumans(),
        ]);

        $resource = $this->postResource($post, $userId);
        $resource['body']     = $post->description;
        $resource['comments_list'] = $comments;
        $resource['related']  = $related->map(fn($p) => $this->postResource($p, $userId));

        return response()->json($resource);
    }

    public function toggleFavorite(Request $request, int $id)
    {
        $user = $request->user();
        $existing = ReaderFavorite::where('user_type', 'reader')
            ->where('user_id', $user->id)
            ->where('post_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        ReaderFavorite::create([
            'user_type' => 'reader',
            'user_id'   => $user->id,
            'post_id'   => $id,
        ]);

        return response()->json(['favorited' => true]);
    }

    public function addComment(Request $request, int $id)
    {
        $user = $request->user();
        $data = $request->validate(['body' => 'required|string|max:1000']);

        $post = post::published()->findOrFail($id);

        $comment = comment::create([
            'post_id'     => $post->id,
            'reader_name' => $user->name,
            'reader_mail' => $user->email,
            'comments'    => $data['body'],
        ]);

        return response()->json([
            'id'         => $comment->id,
            'name'       => $comment->reader_name,
            'body'       => $comment->comments,
            'created_at' => $comment->created_at->diffForHumans(),
        ], 201);
    }

    // ── Catégories ────────────────────────────────────────────

    public function categories()
    {
        $cats = rubrique::withCount(['posts' => fn($q) => $q->published()])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->get();

        return response()->json($cats->map(fn($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'count' => $c->posts_count,
        ]));
    }

    // ── Favoris ───────────────────────────────────────────────

    public function favoris(Request $request)
    {
        $userId = $request->user()->id;

        $paginator = post::published()
            ->whereHas('reader_favorites', fn($q) => $q
                ->where('user_type', 'reader')
                ->where('user_id', $userId)
            )
            ->with(['user.organization', 'rubriques', 'comments'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json([
            'data'       => $paginator->getCollection()->map(fn($p) => $this->postResource($p, $userId)),
            'pagination' => [
                'total'        => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ]);
    }

    // ── Annonces ──────────────────────────────────────────────

    public function annonces(Request $request)
    {
        $category = $request->query('category');

        $query = Annonce::where('status', 'active')
            ->where('payment_status', 'paid')
            ->with('advertiser')
            ->orderByDesc('created_at');

        if ($category) {
            $query->where('category', $category);
        }

        $paginator = $query->paginate(15);

        return response()->json([
            'data'       => $paginator->getCollection()->map(fn($a) => [
                'id'          => $a->id,
                'title'       => $a->title,
                'description' => $a->description ?? '',
                'category'    => $a->category,
                'category_label' => $a->category_label,
                'image'       => ($a->images && count($a->images) > 0) ? asset($a->images[0]) : null,
                'phone'       => $a->contact_phone ?? null,
                'email'       => $a->contact_email ?? null,
                'location'    => $a->location ?? null,
                'published_at'=> $a->created_at?->diffForHumans(),
            ]),
            'categories' => Annonce::CATEGORIES,
            'pagination' => [
                'total'        => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ]);
    }

    public function annonceShow(int $id)
    {
        $annonce = Annonce::where('status', 'active')
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        $similaires = Annonce::where('status', 'active')
            ->where('payment_status', 'paid')
            ->where('category', $annonce->category)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        $firstImage = ($annonce->images && count($annonce->images) > 0) ? asset($annonce->images[0]) : null;

        return response()->json([
            'id'          => $annonce->id,
            'title'       => $annonce->title,
            'description' => $annonce->description ?? '',
            'category'    => $annonce->category,
            'category_label' => $annonce->category_label,
            'images'      => collect($annonce->images ?? [])->map(fn($img) => asset($img)),
            'phone'       => $annonce->contact_phone ?? null,
            'email'       => $annonce->contact_email ?? null,
            'location'    => $annonce->location ?? null,
            'price'       => $annonce->price ?? null,
            'published_at'=> $annonce->created_at?->diffForHumans(),
            'similaires'  => $similaires->map(fn($a) => [
                'id'    => $a->id,
                'title' => $a->title,
                'image' => ($a->images && count($a->images) > 0) ? asset($a->images[0]) : null,
            ]),
        ]);
    }

    // ── Nécrologies ───────────────────────────────────────────

    public function necrologies(Request $request)
    {
        $paginator = Necrologie::where('status', 'active')
            ->with('advertiser')
            ->orderByDesc('created_at')
            ->paginate(12);

        return response()->json([
            'data'       => $paginator->getCollection()->map(fn($n) => [
                'id'            => $n->id,
                'nom_defunt'    => $n->nom_defunt,
                'date_naissance'=> $n->date_naissance?->format('Y-m-d'),
                'date_deces'    => $n->date_deces?->format('Y-m-d'),
                'photo'         => $n->photo ? asset($n->photo) : null,
                'message'       => $n->message ?? '',
                'published_at'  => $n->created_at?->diffForHumans(),
            ]),
            'pagination' => [
                'total'        => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ],
        ]);
    }

    public function necrologieShow(int $id)
    {
        $n = Necrologie::where('status', 'active')->findOrFail($id);

        return response()->json([
            'id'            => $n->id,
            'nom_defunt'    => $n->nom_defunt,
            'date_naissance'=> $n->date_naissance?->format('Y-m-d'),
            'date_deces'    => $n->date_deces?->format('Y-m-d'),
            'photo'         => $n->photo ? asset($n->photo) : null,
            'message'       => $n->message ?? '',
            'published_at'  => $n->created_at?->diffForHumans(),
        ]);
    }
}
