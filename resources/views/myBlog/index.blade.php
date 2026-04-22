@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $homeUrl = "https://{$organization->subdomain}.{$baseDomain}/blog";

    $postImageUrl = function ($post) use ($organization) {
        $image = trim((string) ($post->image ?? ''));
        if ($image === '') {
            $logo = trim((string) ($organization->organization_logo ?? 'images/ebenins.png'));
            return Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset(ltrim($logo, '/'));
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

    $postUrl = fn($post) => "https://{$organization->subdomain}.{$baseDomain}/post/{$post->id}";
    $categoryUrl = fn($rubrique) => "https://{$organization->subdomain}.{$baseDomain}/category/{$rubrique->id}";
    $excerpt = fn($post, $limit = 150) => Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) ($post->description ?? '')))), $limit);

    $sidePosts = collect($featuredPosts)
        ->concat(collect($randomPosts)->pluck('post'))
        ->unique('id')
        ->reject(fn($post) => $latestNews && $post->id === $latestNews->id)
        ->take(3);
    $randomCards = collect($randomPosts)->take(6);
    $featuredCards = collect($featuredPosts)->take(4);
    $reportageItems = collect($reportages)->take(4);
    $socialLinks = collect($socials)->filter(fn($social) => filled($social->url ?? null))->take(4);
    $pubIsActive = $pub && Carbon::parse($pub->created_at)->greaterThanOrEqualTo(now()->subDays(7));

    $navItems = $rubriques;
    $footerRubriques = $rubriques;
    $tickerPosts = collect($breakingPosts ?? [])->filter()->take(8);
    if ($tickerPosts->isEmpty()) {
        $tickerPosts = collect($randomPosts)->pluck('post')->filter()->take(6);
    }
    $tickerLinkResolver = fn($post) => $postUrl($post);
    $brandLogoPath = $organization->organization_logo ?: 'images/ebenins.png';
@endphp

@extends('public.layouts.app')

@section('title', "{$organization->organization_name} | Accueil")
@section('meta_description', "{$organization->organization_name} couvre l'actualité, les analyses et les reportages du Bénin sur E-Benin.")
@section('canonical', $homeUrl)

@section('content')
    @if ($latestNews)
        <section class="hero">
            <div class="container">
                <div class="hero__grid">
                    <a href="{{ $postUrl($latestNews) }}" class="hero__main">
                        <img src="{{ $postImageUrl($latestNews) }}" alt="{{ $latestNews->libelle }}">
                        <div class="hero__overlay"></div>
                        <div class="hero__content">
                            <span class="hero__category">{{ $latestNews->rubriques->first()->name ?? 'Actualité' }}</span>
                            <h1 class="hero__title">{{ $latestNews->libelle }}</h1>
                            <div class="hero__meta">
                                <span>{{ optional($latestNews->created_at)->diffForHumans() }}</span>
                                <span>{{ $latestNews->comments->count() }} commentaires</span>
                            </div>
                        </div>
                    </a>
                    <div class="hero__side">
                        @foreach ($sidePosts as $post)
                            <a href="{{ $postUrl($post) }}" class="hero__card">
                                <img src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}" class="hero__card-img">
                                <div class="hero__card-body">
                                    <div class="hero__card-cat">{{ $post->rubriques->first()->name ?? 'Actualité' }}</div>
                                    <div class="hero__card-title">{{ $post->libelle }}</div>
                                    <div class="hero__card-time">{{ optional($post->created_at)->diffForHumans() }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="cat-strip">
        <div class="container">
            <div class="cat-strip__inner">
                @foreach ($rubriques as $rubrique)
                    <a href="{{ $categoryUrl($rubrique) }}" class="cat-tag">{{ $rubrique->name }}</a>
                @endforeach
            </div>
        </div>
    </div>

    @if ($pubIsActive)
        <section class="ad-strip">
            <div class="container">
                <div class="ad-strip__inner">
                    <div class="ad-strip__text">
                        <div class="ad-strip__title">Annonce partenaire</div>
                        <div class="ad-strip__sub">Cet espace met en avant un partenaire de {{ $organization->organization_name }}</div>
                    </div>
                    <div class="ad-strip__logos">
                        <a href="{{ $pub->url }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($pub->image) }}" alt="Publicité" style="max-height:64px;border-radius:12px;">
                        </a>
                    </div>
                    <a href="{{ $pub->url }}" target="_blank" rel="noopener noreferrer" class="ad-strip__cta">Découvrir</a>
                </div>
            </div>
        </section>
    @endif

    <main>
        <div class="container">
            <div class="main-layout">
                <div class="content-area section-stack">
                    @if ($featuredCards->isNotEmpty())
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">À la une</h2>
                            </div>
                            <div class="news-grid news-grid--2">
                                @foreach ($featuredCards as $post)
                                    <a href="{{ $postUrl($post) }}" class="card">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">{{ $post->rubriques->first()->name ?? 'À la une' }}</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post, 150) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($randomCards->isNotEmpty())
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">Par rubrique</h2>
                            </div>
                            <div class="news-grid news-grid--2">
                                @foreach ($randomCards as $item)
                                    @php($post = $item['post'])
                                    @php($rubrique = $item['rubrique'])
                                    <a href="{{ $postUrl($post) }}" class="card">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">{{ $rubrique->name }}</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post, 145) }}</p>
                                        </div>
                                        <div class="card__footer">
                                            <div class="card__meta">
                                                <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                                <span>{{ $post->comments->count() }} com.</span>
                                            </div>
                                            <div class="card__author">{{ $organization->organization_name }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($reportageItems->isNotEmpty())
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">Reportages</h2>
                            </div>
                            <div class="news-grid news-grid--2">
                                @foreach ($reportageItems as $post)
                                    <a href="{{ $postUrl($post) }}" class="card card--lg">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">Reportage</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post, 155) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="sidebar">
                    @if ($socialLinks->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Réseaux sociaux</div>
                            <div class="social-grid">
                                @foreach ($socialLinks as $social)
                                    <a href="{{ $social->url }}" class="social-btn social-btn--fb" target="_blank" rel="noopener noreferrer">
                                        {{ ucfirst($social->social->nom ?? 'Réseau') }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="widget">
                        <div class="widget__title">Rubriques</div>
                        <div class="tags-cloud">
                            @foreach ($rubriques as $rubrique)
                                <a href="{{ $categoryUrl($rubrique) }}" class="tag">{{ $rubrique->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    @if (collect($randomTags)->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Explorer</div>
                            <div class="tags-cloud">
                                @foreach (collect($randomTags)->take(14) as $tag)
                                    <a href="{{ $categoryUrl($tag) }}" class="tag">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </main>
@endsection
