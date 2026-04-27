@extends('public.layouts.app')

@section('title', $query ? "Résultats pour : {$query}" : 'Recherche')
@section('description', 'Recherchez des articles sur ' . ($organization->organization_name ?? 'E-Benin'))

@section('content')
<section class="search-section">
    <div class="container">
        <div class="search-header">
            <h1>Recherche</h1>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <div class="search-input-wrap">
                    <input type="text" name="q" value="{{ $query }}" placeholder="Rechercher un article..." class="search-input" autofocus>
                    <button type="submit" class="search-btn">🔍</button>
                </div>
                @if($rubriques->count() > 0)
                <select name="rubrique" class="search-filter" onchange="this.form.submit()">
                    <option value="">Toutes les rubriques</option>
                    @foreach($rubriques as $rubrique)
                        <option value="{{ $rubrique->id }}" {{ request('rubrique') == $rubrique->id ? 'selected' : '' }}>
                            {{ $rubrique->name }}
                        </option>
                    @endforeach
                </select>
                @endif
            </form>
        </div>

        @if($query)
            <div class="search-results-info">
                <p>{{ $posts->total() }} résultat(s) pour "<strong>{{ $query }}</strong>"</p>
            </div>
        @endif

        @if($posts->count() > 0)
            <div class="posts-grid">
                @foreach($posts as $post)
                    <article class="post-card">
                        @if($post->image)
                            <a href="{{ url('/post/' . $post->id) }}" class="post-image">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->libelle }}" loading="lazy">
                            </a>
                        @endif
                        <div class="post-content">
                            <div class="post-meta">
                                @foreach($post->rubriques->take(2) as $rubrique)
                                    <span class="post-category">{{ $rubrique->name }}</span>
                                @endforeach
                                <span class="post-date">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                            <h2 class="post-title">
                                <a href="{{ url('/post/' . $post->id) }}">{{ $post->libelle }}</a>
                            </h2>
                            <p class="post-excerpt">{{ Str::limit(strip_tags($post->description), 150) }}</p>
                            <div class="post-footer">
                                <span class="post-author">{{ $post->user->name ?? 'Auteur inconnu' }}</span>
                                <span class="post-comments">{{ $post->comments_count ?? 0 }} commentaire(s)</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $posts->withQueryString()->links('vendor.pagination.bootstrap-4') }}
            </div>
        @else
            @if($query)
                <div class="empty-state">
                    <p>Aucun article ne correspond à votre recherche.</p>
                    <p>Essayez avec d'autres mots-clés ou consultez les <a href="{{ url('/') }}">derniers articles</a>.</p>
                </div>
            @else
                <div class="empty-state">
                    <p>Saisissez un terme de recherche pour trouver des articles.</p>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
