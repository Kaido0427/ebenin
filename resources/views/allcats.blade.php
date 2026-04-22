@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $homeUrl = 'https://' . $baseDomain;

    $postImageUrl = function ($post) {
        $image = trim((string) ($post->image ?? ''));
        if ($image === '') {
            return asset('images/ebenins.png');
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        $normalized = ltrim($image, '/');
        if (Str::startsWith($normalized, ['uploads/', 'images/', 'storage/'])) {
            return asset($normalized);
        }

        return asset('uploads/posts/images/' . basename($normalized));
    };

    $postUrl = function ($post) use ($baseDomain) {
        $subdomain = $post->user->organization->subdomain ?? null;
        return $subdomain ? "https://{$subdomain}.{$baseDomain}/post/{$post->id}" : '#';
    };

    $categoryUrl = fn($item) => "https://{$baseDomain}/categories/{$item->id}";
    $excerpt = fn($post, $limit = 145) => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) ($post->description ?? '')))), $limit);

    $categoryPosts = $paginatedPosts->getCollection();
    $featured = $categoryPosts->first();
    $sidePosts = $categoryPosts->slice(1, 3);
    $gridPosts = $categoryPosts->slice(4);
    $popularList = $categoryPosts->take(5);

    $navItems = $rubriquesGuest;
    $footerRubriques = $rubriquesGuest;
    $tickerPosts = $categoryPosts->take(6);
    $tickerLinkResolver = fn($post) => $postUrl($post);
    $activeCategoryId = $rubrique->id;
@endphp

@extends('public.layouts.app')

@section('title', "{$rubrique->name} | E-Benin")
@section('meta_description', "Consultez les derniers articles de la rubrique {$rubrique->name} sur E-Benin.")
@section('canonical', $homeUrl . '/categories/' . $rubrique->id)

@section('content')
    <div class="cat-hero">
        <div class="container">
            <div class="cat-hero__label">Rubrique</div>
            <h1 class="cat-hero__title">{{ $rubrique->name }}</h1>
            <p class="cat-hero__count">{{ $paginatedPosts->total() }} articles disponibles</p>
        </div>
    </div>

    <div class="cat-strip">
        <div class="container">
            <div class="cat-strip__inner">
                @foreach ($rubriquesGuest as $item)
                    <a href="{{ $categoryUrl($item) }}" class="cat-tag {{ $item->id === $rubrique->id ? 'active' : '' }}">{{ $item->name }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <main>
        <div class="container">
            <div class="main-layout">
                <div class="content-area section-stack">
                    @if ($featured)
                        <section>
                            <div class="grid-two">
                                <a href="{{ $postUrl($featured) }}" class="card card--lg">
                                    <div class="card__img-wrap">
                                        <img class="card__img" src="{{ $postImageUrl($featured) }}" alt="{{ $featured->libelle }}">
                                        <span class="card__cat">À la une</span>
                                    </div>
                                    <div class="card__body">
                                        <h2 class="card__title">{{ $featured->libelle }}</h2>
                                        <p class="card__excerpt">{{ $excerpt($featured, 190) }}</p>
                                    </div>
                                </a>
                                <div class="list-stack">
                                    @foreach ($sidePosts as $post)
                                        <a href="{{ $postUrl($post) }}" class="card card--h">
                                            <div class="card__img-wrap">
                                                <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            </div>
                                            <div class="card__body">
                                                <h3 class="card__title">{{ $post->libelle }}</h3>
                                                <p class="card__excerpt">{{ optional($post->created_at)->diffForHumans() }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                    <section>
                        <div class="section-header">
                            <h2 class="section-title">Tous les articles</h2>
                            <span style="font-size:.82rem;color:var(--muted)">{{ $paginatedPosts->total() }} résultats</span>
                        </div>

                        @if ($gridPosts->isNotEmpty())
                            <div class="news-grid">
                                @foreach ($gridPosts as $post)
                                    <a href="{{ $postUrl($post) }}" class="card">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">{{ $rubrique->name }}</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">Aucun autre article disponible dans cette rubrique pour le moment.</div>
                        @endif

                        @if ($paginatedPosts->lastPage() > 1)
                            <div class="pagination-modern">
                                @if ($paginatedPosts->onFirstPage())
                                    <span>Préc.</span>
                                @else
                                    <a href="{{ $paginatedPosts->previousPageUrl() }}">Préc.</a>
                                @endif

                                @for ($page = 1; $page <= $paginatedPosts->lastPage(); $page++)
                                    @if ($page === $paginatedPosts->currentPage())
                                        <span class="is-active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $paginatedPosts->url($page) }}">{{ $page }}</a>
                                    @endif
                                @endfor

                                @if ($paginatedPosts->hasMorePages())
                                    <a href="{{ $paginatedPosts->nextPageUrl() }}">Suiv.</a>
                                @else
                                    <span>Suiv.</span>
                                @endif
                            </div>
                        @endif
                    </section>
                </div>

                <aside class="sidebar">
                    @if ($popularList->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Les plus lus</div>
                            <div class="widget-divider"></div>
                            <div class="popular-list">
                                @foreach ($popularList as $index => $post)
                                    <div class="popular-item">
                                        <div class="popular-rank">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div>
                                            <a href="{{ $postUrl($post) }}" class="popular-title">{{ $post->libelle }}</a>
                                            <div class="popular-meta">{{ $rubrique->name }} · {{ $post->comments->count() }} commentaires</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="widget newsletter-widget">
                        <div class="widget__title">Newsletter</div>
                        <div class="widget-divider"></div>
                        <p>Restez informé de l'actualité {{ Str::lower($rubrique->name) }} du Bénin.</p>
                        <form class="newsletter-form-compact" action="#" method="GET">
                            <input type="email" placeholder="Votre e-mail">
                            <button type="submit" class="btn btn--primary">S'abonner</button>
                        </form>
                    </div>

                    <div class="widget">
                        <div class="widget__title">Réseaux sociaux</div>
                        <div class="widget-divider"></div>
                        <div class="social-grid social-grid--full">
                            <a class="social-btn social-btn--fb" href="#">Facebook</a>
                            <a class="social-btn social-btn--tw" href="#">Twitter</a>
                            <a class="social-btn social-btn--yt" href="#">YouTube</a>
                            <a class="social-btn social-btn--wa" href="#">WhatsApp</a>
                        </div>
                    </div>

                    <div class="widget">
                        <div class="widget__title">Tags</div>
                        <div class="widget-divider"></div>
                        <div class="tags-cloud">
                            @foreach ($rubriquesGuest as $item)
                                <a href="{{ $categoryUrl($item) }}" class="tag">{{ $item->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection
