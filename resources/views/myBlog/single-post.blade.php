@php
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    $homeUrl = "https://{$organization->subdomain}.{$baseDomain}/blog";
    $postUrl = "https://{$organization->subdomain}.{$baseDomain}/post/{$post->id}";
    $category = $post->rubriques->first();
    $categoryUrl = $category ? "https://{$organization->subdomain}.{$baseDomain}/category/{$category->id}" : $homeUrl;
    $descriptionText = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($post->description ?? ''))));

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

    $relatedPosts = collect($postsByUser)->reject(fn($item) => $item->id === $post->id)->take(4);
    $socialLinks = collect($socials)->filter(fn($social) => filled($social->url ?? null))->take(4);
    $commentCount = $comments->count();
    $viewCount = $viewCount ?? 0;
    $brandLogoPath = $organization->organization_logo ?: 'images/ebenins.png';
    $navItems = $rubriquesGuest;
    $footerRubriques = $rubriquesGuest;
    $tickerPosts = $relatedPosts->isNotEmpty() ? $relatedPosts : collect([$post]);
    $tickerLinkResolver = fn($item) => "https://{$organization->subdomain}.{$baseDomain}/post/{$item->id}";

    $videoUrl = trim((string) ($post->video ?? ''));
    $videoEmbedUrl = null;
    if ($videoUrl !== '') {
        if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]+)~', $videoUrl, $matches)) {
            $videoEmbedUrl = 'https://www.youtube.com/embed/' . $matches[1];
        } elseif (Str::contains($videoUrl, 'youtube.com/embed/')) {
            $videoEmbedUrl = $videoUrl;
        }
    }

    $facebookShare = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($postUrl);
    $twitterShare = 'https://twitter.com/intent/tweet?url=' . urlencode($postUrl) . '&text=' . urlencode($post->libelle);
    $whatsappShare = 'https://api.whatsapp.com/send?text=' . urlencode($post->libelle . ' - ' . $postUrl);
    $linkedinShare = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($postUrl);
@endphp

@extends('public.layouts.app')

@section('title', $post->libelle . ' | ' . $organization->organization_name)
@section('meta_description', Str::limit($descriptionText, 180))
@section('canonical', $postUrl)
@section('og_type', 'article')
@section('og_title', $post->libelle . ' | ' . $organization->organization_name)
@section('og_description', Str::limit($descriptionText, 180))
@section('twitter_title', $post->libelle . ' | ' . $organization->organization_name)
@section('twitter_description', Str::limit($descriptionText, 180))
@section('og_image', $postImageUrl($post))

@section('content')
    <main>
        <div class="container">
            <div class="main-layout">
                <div class="content-area section-stack">
                    <article>
                        <div class="article-header">
                            <div class="article-breadcrumb">
                                <a href="{{ $homeUrl }}">Accueil</a>
                                @if ($category)
                                    <span>›</span>
                                    <a href="{{ $categoryUrl }}">{{ $category->name }}</a>
                                @endif
                                <span>›</span>
                                <span>Article</span>
                            </div>

                            <span class="article-cat">{{ $category?->name ?? 'Actualité' }}</span>
                            <h1 class="article-title">{{ $post->libelle }}</h1>
                            @if (filled($post->sous_titre))
                                <p class="article-lead">{{ $post->sous_titre }}</p>
                            @endif

                            <div class="article-meta">
                                <div class="article-author">
                                    <img
                                        src="{{ $bio && filled($bio->avatar ?? null) ? asset($bio->avatar) : asset('images/dists/user.webp') }}"
                                        alt="{{ $post->user->name }}"
                                        class="article-author__avatar">
                                    <div>
                                        <div class="article-author__name">{{ $post->user->name }}</div>
                                        <div class="article-author__role">{{ $organization->organization_name }}</div>
                                    </div>
                                </div>
                                <div class="article-date">{{ optional($post->created_at)->translatedFormat('d F Y à H:i') }}</div>
                                <div class="article-date">{{ $viewCount }} vues</div>
                                <div class="article-date">{{ $commentCount }} commentaire{{ $commentCount > 1 ? 's' : '' }}</div>
                                <div class="article-share">
                                    Partager :
                                    <a href="{{ $facebookShare }}" class="share-btn share-btn--fb" target="_blank" rel="noopener noreferrer">f</a>
                                    <a href="{{ $twitterShare }}" class="share-btn share-btn--tw" target="_blank" rel="noopener noreferrer">𝕏</a>
                                    <a href="{{ $whatsappShare }}" class="share-btn share-btn--wa" target="_blank" rel="noopener noreferrer">✆</a>
                                    <a href="{{ $linkedinShare }}" class="share-btn share-btn--ln" target="_blank" rel="noopener noreferrer">in</a>
                                </div>
                            </div>
                        </div>

                        <div class="article-hero">
                            <img src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                            <div class="article-hero__caption">
                                {{ $organization->organization_name }} · {{ $category?->name ?? 'Actualité' }}
                            </div>
                        </div>

                        <div class="article-body post-content-rendered">
                            {!! $post->description !!}
                        </div>

                        @if ($videoUrl !== '')
                            <div class="comments-shell" style="margin-top:28px;">
                                <div class="section-header">
                                    <h2 class="section-title">Vidéo associée</h2>
                                </div>
                                @if ($videoEmbedUrl)
                                    <iframe src="{{ $videoEmbedUrl }}" allowfullscreen loading="lazy"></iframe>
                                @else
                                    <a href="{{ $videoUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn--primary">Voir la vidéo</a>
                                @endif
                            </div>
                        @endif

                        @if ($post->rubriques->isNotEmpty())
                            <div class="article-tags">
                                <span class="article-tags-label">Rubriques :</span>
                                @foreach ($post->rubriques as $rubrique)
                                    <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/category/{{ $rubrique->id }}" class="tag">{{ $rubrique->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </article>

                    <section class="author-card">
                        <img
                            src="{{ $bio && filled($bio->avatar ?? null) ? asset($bio->avatar) : asset('images/dists/user.webp') }}"
                            alt="{{ $post->user->name }}"
                            class="author-card__avatar">
                        <div>
                            <div class="author-card__name">{{ $post->user->name }}</div>
                            <p>{{ $bio && filled($bio->bio ?? null) ? strip_tags($bio->bio) : "{$organization->organization_name} publie des actualités et analyses sur E-Benin." }}</p>
                        </div>
                    </section>

                    <section class="comments-shell">
                        <div class="section-header">
                            <h2 class="section-title">{{ $commentCount }} commentaire{{ $commentCount > 1 ? 's' : '' }}</h2>
                        </div>

                        @forelse ($comments as $comment)
                            <div class="comment-item">
                                <strong>{{ $comment->reader_name }}</strong>
                                <div class="comment-item__meta">{{ optional($comment->created_at)->translatedFormat('d F Y à H:i') }}</div>
                                <p>{{ $comment->comments }}</p>
                            </div>
                        @empty
                            <p>Aucun commentaire pour le moment.</p>
                        @endforelse
                    </section>

                    <section class="comment-form-shell">
                        <div class="section-header">
                            <h2 class="section-title">Laisser un commentaire</h2>
                        </div>

                        <form class="comment-form" method="POST" action="{{ route('comments.store', ['post' => $post->id]) }}">
                            @csrf
                            <input id="name" name="name" type="text" placeholder="Votre nom" required>
                            <textarea id="comment" name="comment" placeholder="Votre commentaire" required></textarea>
                            <button type="submit" class="btn btn--primary" style="justify-content:center;">Publier le commentaire</button>
                        </form>
                    </section>
                </div>

                <aside class="sidebar">
                    @if ($socialLinks->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Restez connecté</div>
                            <div class="social-grid">
                                @foreach ($socialLinks as $social)
                                    <a href="{{ $social->url }}" class="social-btn social-btn--fb" target="_blank" rel="noopener noreferrer">
                                        {{ ucfirst($social->social->nom ?? 'Réseau') }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($relatedPosts->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Autres articles</div>
                            <div class="list-stack">
                                @foreach ($relatedPosts as $item)
                                    <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/post/{{ $item->id }}" class="card card--h">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($item) }}" alt="{{ $item->libelle }}">
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $item->libelle }}</h3>
                                            <p class="card__excerpt">{{ optional($item->created_at)->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="widget">
                        <div class="widget__title">Rubriques</div>
                        <div class="tags-cloud">
                            @foreach ($rubriquesGuest as $rubrique)
                                <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/category/{{ $rubrique->id }}" class="tag">{{ $rubrique->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
@endsection
