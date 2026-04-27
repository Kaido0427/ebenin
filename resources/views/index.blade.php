@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    $host = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

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

    $organizationUrl = function ($post) use ($baseDomain) {
        $subdomain = $post->user->organization->subdomain ?? null;
        return $subdomain ? "https://{$subdomain}.{$baseDomain}/blog" : '#';
    };

    $categoryUrl = function ($rubrique) use ($baseDomain) {
        return filled($rubrique->id ?? null) ? "https://{$baseDomain}/categories/{$rubrique->id}" : '#';
    };

    $excerpt = function ($post, $limit = 160) {
        return Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) ($post->description ?? '')))), $limit);
    };

    $heroSlides = collect($newPosts)->concat($latestPosts)->unique('id')->take(5);
    $headline   = $heroSlides->first();
    $heroSide   = collect($newPosts)
        ->concat($latestPosts)
        ->unique('id')
        ->reject(fn($post) => $headline && $post->id === $headline->id)
        ->take(3);
    $latestGrid = collect($latestPosts)->take(6);
    $featuredGrid = collect($featuredPosts)->take(4);
    $networkSpotlight = collect($randomizedPosts)->take(4);
    $reportageItems = collect($reportages)->sortByDesc('created_at')->take(4);
    $flashList = collect($flashNews)->take(6);
    $popularList = collect($latestPosts)->take(5);
    $pubIsActive = $pub && Carbon::parse($pub->created_at)->greaterThanOrEqualTo(now()->subDays(7));

    $navItems = $rubriques;
    $footerRubriques = $rubriquesWithoutPosts;
    $tickerPosts = $flashNews;
    $tickerLinkResolver = fn($post) => $postUrl($post);
    $showAuthModal = true;
@endphp

@extends('public.layouts.app')

@section('title', "E-Benin | Actualités, blogs et reportages au Bénin")
@section('meta_description', "Découvrez les dernières actualités, analyses, reportages et blogs du réseau E-Benin sur la politique, l'économie, la société, la culture et le sport au Bénin.")
@section('canonical', 'https://' . $baseDomain)

@section('content')
    @if ($heroSlides->isNotEmpty())
        <section class="hero">
            <div class="container">
                <div class="hero__grid">
                    <div class="hero__main hero-slider" id="heroSlider">
                        @foreach ($heroSlides as $i => $post)
                            <a href="{{ $postUrl($post) }}" class="hero-slide{{ $i === 0 ? ' is-active' : '' }}">
                                <img src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                <div class="hero__overlay"></div>
                                <div class="hero__content">
                                    <span class="hero__category">{{ $post->rubriques->first()->name ?? 'Actualité' }}</span>
                                    <h1 class="hero__title">{{ $post->libelle }}</h1>
                                    <div class="hero__meta">
                                        <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                        <span>{{ $post->comments->count() }} commentaires</span>
                                        <span>{{ $post->user->organization->organization_name ?? 'E-Benin' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        @if ($heroSlides->count() > 1)
                            <button class="hero-slider__arrow hero-slider__prev" aria-label="Précédent">&#8249;</button>
                            <button class="hero-slider__arrow hero-slider__next" aria-label="Suivant">&#8250;</button>
                            <div class="hero-slider__dots">
                                @foreach ($heroSlides as $i => $post)
                                    <button class="hero-slider__dot{{ $i === 0 ? ' is-active' : '' }}" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="hero__side">
                        @foreach ($heroSide as $post)
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
                @foreach ($rubriquesWithoutPosts->take(12) as $rubrique)
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
                        <div class="ad-strip__sub">Mise en avant sur le réseau E-Benin</div>
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
                    <section>
                        <div class="section-header">
                            <h2 class="section-title">Dernières nouvelles</h2>
                            <a href="https://{{ $baseDomain }}" class="section-more">Toute l'actualité</a>
                        </div>
                        <div class="news-grid">
                            @foreach ($latestGrid as $post)
                                <a href="{{ $postUrl($post) }}" class="card">
                                    <div class="card__img-wrap">
                                        <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                        <span class="card__cat">{{ $post->rubriques->first()->name ?? 'Actualité' }}</span>
                                    </div>
                                    <div class="card__body">
                                        <h3 class="card__title">{{ $post->libelle }}</h3>
                                        <p class="card__excerpt">{{ $excerpt($post, 130) }}</p>
                                    </div>
                                    <div class="card__footer">
                                        <div class="card__meta">
                                            <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                            <span>{{ $post->comments->count() }} com.</span>
                                        </div>
                                        <div class="card__author">
                                            <div class="card__avatar"></div>
                                            {{ $post->user->organization->organization_name ?? 'Rédaction' }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    @if ($featuredGrid->isNotEmpty())
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">À la une</h2>
                            </div>
                            <div class="news-grid news-grid--2">
                                @foreach ($featuredGrid as $post)
                                    <a href="{{ $postUrl($post) }}" class="card card--lg">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">{{ $post->rubriques->first()->name ?? 'À la une' }}</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post, 170) }}</p>
                                        </div>
                                        <div class="card__footer">
                                            <div class="card__meta">
                                                <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                                                <span>{{ $post->comments->count() }} com.</span>
                                            </div>
                                            <div class="card__author">{{ $post->user->organization->organization_name ?? 'E-Benin' }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($networkSpotlight->isNotEmpty())
                        <section>
                            <div class="section-header">
                                <h2 class="section-title">Blogs du réseau</h2>
                            </div>
                            <div class="news-grid news-grid--2">
                                @foreach ($networkSpotlight as $item)
                                    @php($post = $item['post'])
                                    <a href="{{ $postUrl($post) }}" class="card card--h">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $post->user->organization->organization_name ?? 'Blog' }}</p>
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
                                    <a href="{{ $postUrl($post) }}" class="card">
                                        <div class="card__img-wrap">
                                            <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}">
                                            <span class="card__cat">Reportage</span>
                                        </div>
                                        <div class="card__body">
                                            <h3 class="card__title">{{ $post->libelle }}</h3>
                                            <p class="card__excerpt">{{ $excerpt($post, 135) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <aside class="sidebar">
                    <div class="widget newsletter-widget">
                        <div class="widget__title">Newsletter</div>
                        <div class="widget-divider"></div>
                        <p>Recevez les dernières nouvelles directement dans votre boîte mail.</p>
                        <form class="newsletter-form-compact" action="#" method="GET">
                            <input type="email" placeholder="Votre adresse e-mail">
                            <button type="submit" class="btn btn--primary">S'abonner gratuitement</button>
                        </form>
                    </div>

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
                                            <div class="popular-meta">
                                                {{ $post->rubriques->first()->name ?? 'Actualité' }} · {{ $post->comments->count() }} commentaires
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="widget">
                        <div class="widget__title">Flash info</div>
                        <div class="trending">
                            @foreach ($flashList as $index => $post)
                                <div class="trending__item">
                                    <div class="trending__num">{{ $index + 1 }}</div>
                                    <div>
                                        <a href="{{ $postUrl($post) }}" class="trending__title">{{ $post->libelle }}</a>
                                        <div class="trending__cat">{{ $post->rubriques->first()->name ?? 'Actualité' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="widget">
                        <div class="widget__title">Rubriques</div>
                        <div class="tags-cloud">
                            @foreach ($rubriquesWithoutPosts->take(16) as $rubrique)
                                <a href="{{ $categoryUrl($rubrique) }}" class="tag">{{ $rubrique->name }}</a>
                            @endforeach
                        </div>
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

                    @if (collect($footerOrgs)->isNotEmpty())
                        <div class="widget">
                            <div class="widget__title">Blogs à suivre</div>
                            <div class="network-list">
                                @foreach (collect($footerOrgs)->take(6) as $organization)
                                    <a href="https://{{ $organization->subdomain }}.{{ $baseDomain }}/blog">
                                        <span>{{ $organization->organization_name }}</span>
                                        <span>Voir</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
(function () {
    var slider = document.getElementById('heroSlider');
    if (!slider) return;
    var slides = slider.querySelectorAll('.hero-slide');
    var dots   = slider.querySelectorAll('.hero-slider__dot');
    if (slides.length < 2) return;
    var current = 0;
    var timer;

    function goTo(n) {
        slides[current].classList.remove('is-active');
        if (dots[current]) dots[current].classList.remove('is-active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('is-active');
        if (dots[current]) dots[current].classList.add('is-active');
    }

    function startTimer() { timer = setInterval(function () { goTo(current + 1); }, 5000); }
    function resetTimer()  { clearInterval(timer); startTimer(); }

    var btnNext = slider.querySelector('.hero-slider__next');
    var btnPrev = slider.querySelector('.hero-slider__prev');
    if (btnNext) btnNext.addEventListener('click', function (e) { e.preventDefault(); goTo(current + 1); resetTimer(); });
    if (btnPrev) btnPrev.addEventListener('click', function (e) { e.preventDefault(); goTo(current - 1); resetTimer(); });
    dots.forEach(function (dot) {
        dot.addEventListener('click', function () { goTo(parseInt(this.dataset.index)); resetTimer(); });
    });

    startTimer();
}());
</script>
@endpush
