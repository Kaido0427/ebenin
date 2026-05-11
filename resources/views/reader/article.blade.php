@extends('reader.layouts.app')
@section('title', $post->libelle)
@section('body_class', 'body--article')

@php
    $imgUrl  = $post->image ? asset($post->image) : ($post->image_url ?? null);
    $org     = $post->user?->organization;
    $orgLogo = $org?->organization_logo ? asset($org->organization_logo) : null;
    $catIdx  = $post->rubriques->isNotEmpty() ? ($post->rubriques->first()->id % 12) : 0;
    $authUser = Auth::guard('reader')->user()
             ?? Auth::guard('web')->user()
             ?? Auth::guard('advertiser')->user()
             ?? Auth::guard('admin')->user();
@endphp

@section('content')
<div class="ra-article">

    {{-- Fixed topbar --}}
    <div class="ra-article__topbar">
        <button class="ra-article__topbar-btn" onclick="history.back()" aria-label="Retour">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <div class="ra-article__topbar-actions">
            {{-- Favori --}}
            <button class="ra-article__topbar-btn" id="fav-btn" onclick="toggleFav()" aria-label="Favori">
                <svg id="fav-icon" viewBox="0 0 24 24"
                     style="{{ $isFavorited ? 'fill:#e8191e;stroke:#e8191e' : 'fill:none;stroke:currentColor' }}">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                </svg>
            </button>
            {{-- Partager --}}
            <button class="ra-article__topbar-btn" onclick="shareArticle(event)" aria-label="Partager">
                <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>
    </div>

    {{-- Hero image --}}
    <div class="ra-article__hero">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $post->libelle }}" class="ra-article__hero-img">
        @else
            <div class="ra-article__hero-img" style="background:linear-gradient(135deg,#003f7f,#0057b3);"></div>
        @endif
    </div>

    {{-- Body --}}
    <div class="ra-article__body">

        @if($post->rubriques->isNotEmpty())
        <div class="ra-article__cat cat-color-{{ $catIdx }}">
            @foreach($post->rubriques as $rub)
                {{ $rub->name }}{{ !$loop->last ? ' · ' : '' }}
            @endforeach
        </div>
        @endif

        <h1 class="ra-article__title">{{ $post->libelle }}</h1>

        <div class="ra-article__byline">
            @if($orgLogo)
                <img src="{{ $orgLogo }}" alt="{{ $org->organization_name }}"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
                <div style="width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;font-weight:900;font-size:.7rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    {{ strtoupper(substr($org?->organization_name ?? 'E', 0, 1)) }}
                </div>
            @endif
            <span>{{ $org?->organization_name ?? 'E-Benin' }}</span>
            <span class="ra-article__byline-sep">·</span>
            <span>{{ $post->created_at->translatedFormat('d F Y') }}</span>
        </div>

        @if($post->sous_titre)
            <p style="font-size:.95rem;font-weight:600;color:var(--mid);margin-bottom:18px;line-height:1.55;">{{ $post->sous_titre }}</p>
        @endif

        <div class="ra-article__content">
            {!! $post->description !!}
        </div>

        @if($post->video)
        <div style="margin-top:20px">
            <video controls style="width:100%;border-radius:var(--radius)">
                <source src="{{ asset($post->video) }}">
            </video>
        </div>
        @elseif($post->video_url)
        <div style="margin-top:20px;position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:var(--radius)">
            <iframe src="{{ $post->video_url }}"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;border:0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
        @endif

    </div>

    {{-- Action bar --}}
    <div class="ra-article__actions">
        <button class="ra-article__action-btn {{ $isFavorited ? 'active' : '' }}" id="fav-action-btn" onclick="toggleFav()">
            <svg viewBox="0 0 24 24" id="fav-action-icon" style="{{ $isFavorited ? 'fill:#e8191e;stroke:#e8191e' : 'fill:none;stroke:currentColor' }}">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            <span id="fav-label">{{ $isFavorited ? 'Sauvegardé' : 'Sauvegarder' }}</span>
        </button>
        <button class="ra-article__action-btn" onclick="shareArticle(event)">
            <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            <span>Partager</span>
        </button>
        <button class="ra-article__action-btn" onclick="document.getElementById('comment-form').scrollIntoView({behavior:'smooth'})">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>{{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}</span>
        </button>
    </div>

    {{-- À lire aussi --}}
    @if($related->isNotEmpty())
    <div class="ra-read-also">
        <div class="ra-read-also__title">À lire aussi</div>
        <div class="ra-related__grid">
            @foreach($related as $r)
            @php $rImg = $r->image ? asset($r->image) : ($r->image_url ?? null); @endphp
            <a href="/reader/article/{{ $r->id }}" class="ra-related__card">
                @if($rImg)
                    <img src="{{ $rImg }}" alt="{{ $r->libelle }}" class="ra-related__card-img" loading="lazy">
                @else
                    <div class="ra-related__card-img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:1.2rem;height:90px;">📰</div>
                @endif
                <div class="ra-related__card-body">
                    <div class="ra-related__card-title">{{ $r->libelle }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Commentaires --}}
    <div class="ra-comments" id="comments-section">
        <div class="ra-comments__header">
            <span class="ra-comments__title">Commentaires</span>
            <span class="ra-comments__count">{{ $post->comments->count() }}</span>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="ra-alert ra-alert--success" style="margin:0 16px 12px;">{{ session('success') }}</div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="/reader/article/{{ $post->id }}/comment" class="ra-comment-form" id="comment-form">
            @csrf
            <div class="ra-comment-form__user">
                <div class="ra-comment-form__avatar">
                    {{ strtoupper(substr($authUser?->name ?? $authUser?->organization_name ?? 'A', 0, 1)) }}
                </div>
                <div style="flex:1">
                    <textarea name="comments" class="ra-comment-form__input"
                              placeholder="Écrire un commentaire…" rows="3"
                              required maxlength="1000">{{ old('comments') }}</textarea>
                    @error('comments')
                        <div style="font-size:.75rem;color:var(--accent);margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;padding:0 16px 16px;">
                <button type="submit" class="ra-comment-form__submit">Publier</button>
            </div>
        </form>

        {{-- Liste commentaires --}}
        @if($post->comments->isNotEmpty())
        <div class="ra-comment-list">
            @foreach($post->comments->sortByDesc('created_at') as $comment)
            <div class="ra-comment-item">
                <div class="ra-comment-item__avatar">
                    {{ strtoupper(substr($comment->reader_name, 0, 1)) }}
                </div>
                <div class="ra-comment-item__body">
                    <div class="ra-comment-item__name">{{ $comment->reader_name }}</div>
                    <div class="ra-comment-item__time">{{ $comment->created_at->diffForHumans() }}</div>
                    <p class="ra-comment-item__text">{{ $comment->comments }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <div style="text-align:center;padding:20px;font-size:.84rem;color:var(--muted);">
                Soyez le premier à commenter
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
var postId   = {{ $post->id }};
var favState = {{ $isFavorited ? 'true' : 'false' }};
var csrf     = '{{ csrf_token() }}';

/* ── Toast ── */
function showToast(msg, type) {
    var t = document.createElement('div');
    t.className = 'ra-toast' + (type === 'error' ? ' ra-toast--error' : '');
    t.textContent = msg;
    document.body.appendChild(t);
    requestAnimationFrame(function() { t.classList.add('show'); });
    setTimeout(function() {
        t.classList.remove('show');
        setTimeout(function() { t.remove(); }, 300);
    }, 2200);
}

/* ── Favori ── */
function toggleFav() {
    fetch('/reader/article/' + postId + '/favorite', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(function(r) {
        if (!r.ok) throw new Error(r.status);
        return r.json();
    })
    .then(function(data) {
        favState = data.favorited;
        var color = favState ? '#e8191e' : 'currentColor';
        var fill  = favState ? '#e8191e' : 'none';
        ['fav-icon', 'fav-action-icon'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) { el.style.stroke = color; el.style.fill = fill; }
        });
        var label = document.getElementById('fav-label');
        if (label) label.textContent = favState ? 'Sauvegardé' : 'Sauvegarder';
        showToast(favState ? '🔖 Article sauvegardé' : 'Retiré des favoris');
    })
    .catch(function(err) {
        showToast('Connexion requise pour sauvegarder', 'error');
    });
}

/* ── Partager ── */
function shareArticle(e) {
    if (e) e.preventDefault();
    var title = {{ json_encode($post->libelle) }};
    var url   = location.href;

    function copyFallback() {
        try {
            var inp = document.createElement('input');
            inp.value = url;
            inp.style.position = 'fixed';
            inp.style.opacity  = '0';
            document.body.appendChild(inp);
            inp.focus(); inp.select();
            document.execCommand('copy');
            document.body.removeChild(inp);
            showToast('🔗 Lien copié !');
        } catch(err) {
            showToast('Partagez ce lien : ' + url);
        }
    }

    if (navigator.share) {
        navigator.share({ title: title, url: url })
            .then(function() { showToast('✅ Partagé !'); })
            .catch(function(err) {
                if (err.name !== 'AbortError') copyFallback();
            });
    } else if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url)
            .then(function() { showToast('🔗 Lien copié !'); })
            .catch(copyFallback);
    } else {
        copyFallback();
    }
}
</script>
@endpush
