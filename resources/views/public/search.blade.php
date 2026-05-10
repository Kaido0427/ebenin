@php
    use Illuminate\Support\Str;

    $host       = request()->getHost();
    $baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';

    $postImageUrl = function ($post) {
        $image = trim((string) ($post->image ?? ''));
        if ($image === '') return asset('images/ebenins.png');
        if (Str::startsWith($image, ['http://', 'https://'])) return $image;
        $normalized = ltrim($image, '/');
        if (Str::startsWith($normalized, ['uploads/', 'images/', 'storage/'])) return asset($normalized);
        return asset('uploads/posts/images/' . basename($normalized));
    };

    $postUrl = function ($post) use ($organization, $baseDomain) {
        $subdomain = $organization->subdomain ?? null;
        return $subdomain ? "https://{$subdomain}.{$baseDomain}/post/{$post->id}" : '#';
    };

    $cleanText = function ($value) {
        $text = strip_tags((string) ($value ?? ''));
        for ($i = 0; $i < 2; $i++) {
            $decoded = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($decoded === $text) break;
            $text = $decoded;
        }
        return trim(preg_replace('/\s+/', ' ', $text));
    };

    $excerpt = fn($post) => Str::limit($cleanText($post->description ?? ''), 140);

    $activeFilters = collect([
        'rubrique'  => request('rubrique'),
        'date_from' => $dateFrom ?? null,
        'date_to'   => $dateTo ?? null,
        'sort'      => ($sort ?? 'recent') !== 'recent' ? ($sort ?? null) : null,
    ])->filter()->count();
@endphp

@extends('public.layouts.app')
@section('title', $query ? "Résultats pour : {$query}" : 'Recherche')
@section('meta_description', 'Recherchez des articles sur ' . ($organization->organization_name ?? 'E-Benin'))

@section('content')

<div style="background:var(--dark);padding:36px 0 0;">
    <div class="container">
        <p style="font-size:.75rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:8px;">Recherche</p>
        <h1 style="font-family:var(--font-serif);font-size:1.6rem;color:#fff;margin-bottom:20px;">{{ $organization->organization_name ?? 'E-Benin' }}</h1>

        <form action="{{ url('/search') }}" method="GET" id="search-form">
            <div style="display:flex;gap:8px;max-width:700px;">
                <div style="position:relative;flex:1;">
                    <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;stroke:#9ca3af;fill:none;stroke-width:2;stroke-linecap:round;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Rechercher un article…"
                           style="width:100%;padding:12px 14px 12px 42px;border-radius:8px;border:none;font-size:.95rem;background:#fff;">
                </div>
                <button type="submit" style="padding:12px 24px;background:var(--accent);color:#fff;border:none;border-radius:8px;font-weight:700;font-size:.9rem;cursor:pointer;white-space:nowrap;flex-shrink:0;">
                    Rechercher
                </button>
            </div>

            {{-- Filtres avancés --}}
            <div style="margin-top:14px;padding-bottom:18px;">
                <button type="button" onclick="document.getElementById('adv-filters').classList.toggle('hidden')"
                        style="background:rgba(255,255,255,.12);color:#fff;border:none;border-radius:6px;padding:7px 14px;font-size:.78rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;stroke-linecap:round;"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                    Filtres avancés
                    @if($activeFilters > 0)
                        <span style="background:var(--accent);border-radius:20px;padding:1px 7px;font-size:.68rem;">{{ $activeFilters }}</span>
                    @endif
                </button>

                <div id="adv-filters" class="{{ $activeFilters > 0 ? '' : 'hidden' }}"
                     style="margin-top:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;max-width:700px;">

                    {{-- Catégorie --}}
                    @if($rubriques->count() > 0)
                    <div>
                        <label style="display:block;font-size:.72rem;color:rgba(255,255,255,.6);font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em;">Catégorie</label>
                        <select name="rubrique" onchange="this.form.submit()"
                                style="width:100%;padding:9px 10px;border-radius:6px;border:none;font-size:.82rem;background:#fff;">
                            <option value="">Toutes les catégories</option>
                            @foreach($rubriques as $rub)
                                <option value="{{ $rub->id }}" {{ request('rubrique') == $rub->id ? 'selected' : '' }}>
                                    {{ $rub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Tri --}}
                    <div>
                        <label style="display:block;font-size:.72rem;color:rgba(255,255,255,.6);font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em;">Trier par</label>
                        <select name="sort" onchange="this.form.submit()"
                                style="width:100%;padding:9px 10px;border-radius:6px;border:none;font-size:.82rem;background:#fff;">
                            <option value="recent"  {{ ($sort ?? 'recent') === 'recent'  ? 'selected' : '' }}>Plus récents</option>
                            <option value="oldest"  {{ ($sort ?? '') === 'oldest'  ? 'selected' : '' }}>Plus anciens</option>
                            <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>Plus populaires</option>
                        </select>
                    </div>

                    {{-- Du --}}
                    <div>
                        <label style="display:block;font-size:.72rem;color:rgba(255,255,255,.6);font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em;">Du</label>
                        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" onchange="this.form.submit()"
                               style="width:100%;padding:9px 10px;border-radius:6px;border:none;font-size:.82rem;background:#fff;">
                    </div>

                    {{-- Au --}}
                    <div>
                        <label style="display:block;font-size:.72rem;color:rgba(255,255,255,.6);font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.08em;">Au</label>
                        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" onchange="this.form.submit()"
                               style="width:100%;padding:9px 10px;border-radius:6px;border:none;font-size:.82rem;background:#fff;">
                    </div>
                </div>

                {{-- Chips filtres actifs --}}
                @if($activeFilters > 0)
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:12px;padding-bottom:4px;">
                    @if(request('rubrique'))
                        @php $rubName = $rubriques->firstWhere('id', request('rubrique'))?->name ?? ''; @endphp
                        <a href="{{ request()->fullUrlWithQuery(['rubrique' => null, 'page' => null]) }}"
                           style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;">
                            {{ $rubName }} ×
                        </a>
                    @endif
                    @if($dateFrom ?? false)
                        <a href="{{ request()->fullUrlWithQuery(['date_from' => null, 'page' => null]) }}"
                           style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;">
                            Depuis {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d M Y') }} ×
                        </a>
                    @endif
                    @if($dateTo ?? false)
                        <a href="{{ request()->fullUrlWithQuery(['date_to' => null, 'page' => null]) }}"
                           style="display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.18);color:#fff;border-radius:20px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;">
                            Jusqu'au {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d M Y') }} ×
                        </a>
                    @endif
                    <a href="{{ url('/search') }}?q={{ urlencode($query) }}"
                       style="display:inline-flex;align-items:center;gap:5px;background:rgba(232,25,30,.7);color:#fff;border-radius:20px;padding:4px 12px;font-size:.72rem;font-weight:700;text-decoration:none;">
                        Tout effacer
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

<main>
    <div class="container" style="padding-top:28px;padding-bottom:48px;">

        @if($query || $activeFilters > 0)
        <p style="margin-bottom:20px;color:var(--mid);font-size:.88rem;">
            <strong style="color:var(--dark);">{{ $posts->total() }}</strong> résultat(s)
            @if($query) pour « <strong>{{ $query }}</strong> » @endif
        </p>
        @endif

        @if($posts->count() > 0)
            <div class="news-grid">
                @foreach($posts as $post)
                <a href="{{ $postUrl($post) }}" class="card">
                    <div class="card__img-wrap">
                        <img class="card__img" src="{{ $postImageUrl($post) }}" alt="{{ $post->libelle }}" loading="lazy">
                        <span class="card__cat">{{ $post->rubriques->first()->name ?? 'Actualité' }}</span>
                    </div>
                    <div class="card__body">
                        <h3 class="card__title">{{ $post->libelle }}</h3>
                        <p class="card__excerpt">{{ $excerpt($post) }}</p>
                    </div>
                    <div class="card__footer">
                        <div class="card__meta">
                            <span>{{ optional($post->created_at)->diffForHumans() }}</span>
                            <span>{{ $post->comments->count() }} com.</span>
                        </div>
                        <div class="card__author">{{ $post->user->name ?? 'Rédaction' }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="margin-top:32px;">{{ $posts->links('vendor.pagination.bootstrap-4') }}</div>

        @elseif($query || $activeFilters > 0)
            <div style="text-align:center;padding:56px 0;color:var(--muted);">
                <svg style="width:48px;height:48px;stroke:var(--border);fill:none;stroke-width:1.5;margin:0 auto 16px;display:block;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p style="font-size:1rem;font-weight:700;color:var(--mid);margin-bottom:6px;">Aucun résultat</p>
                <p style="font-size:.84rem;">Essayez d'autres mots-clés ou modifiez vos filtres.</p>
            </div>
        @else
            <div style="text-align:center;padding:56px 0;color:var(--muted);">
                <svg style="width:48px;height:48px;stroke:var(--border);fill:none;stroke-width:1.5;margin:0 auto 16px;display:block;" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p style="font-size:.9rem;">Saisissez un terme pour commencer la recherche.</p>
            </div>
        @endif
    </div>
</main>

<style>
#adv-filters.hidden { display: none !important; }
@media (max-width: 640px) {
    #adv-filters { grid-template-columns: 1fr 1fr !important; }
}
</style>
@endsection
