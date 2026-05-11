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

@push('header_actions')
    <button class="ra-header__btn {{ $isFavorited ? 'active' : '' }}" id="header-fav-btn" data-id="{{ $post->id }}" aria-label="Favori">
        <svg viewBox="0 0 24 24">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
    </button>
    <button class="ra-header__btn" id="header-share-btn" aria-label="Partager">
        <svg viewBox="0 0 24 24">
            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
            <polyline points="16 6 12 2 8 6"/>
            <line x1="12" y1="2" x2="12" y2="15"/>
        </svg>
    </button>
@endpush

@section('content')
<div class="ra-article">

    {{-- Hero image --}}
    <div class="ra-article__hero">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $post->libelle }}" class="ra-article__hero-img">
        @else
            <div class="ra-article__hero-img" style="background:linear-gradient(135deg,#001a3e,#003f7f);"></div>
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
        <button class="ra-article__action-btn {{ $isFavorited ? 'active' : '' }}" id="fav-btn" data-id="{{ $post->id }}">
            <svg viewBox="0 0 24 24">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span id="fav-text">{{ $isFavorited ? 'Favori' : 'Enregistrer' }}</span>
        </button>

        <button class="ra-article__action-btn" id="share-btn">
            <svg viewBox="0 0 24 24">
                <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                <polyline points="16 6 12 2 8 6"/>
                <line x1="12" y1="2" x2="12" y2="15"/>
            </svg>
            <span>Partager</span>
        </button>

        <button class="ra-article__action-btn" id="comment-btn">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>{{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}</span>
        </button>
    </div>

    {{-- Toast for feedback --}}
    <div id="ra-toast" class="ra-toast"></div>

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

        @if(session('success'))
            <div class="ra-alert ra-alert--success" style="margin:0 16px 12px;">{{ session('success') }}</div>
        @endif

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

<script>
const toast = document.getElementById('ra-toast');
function showToast(msg, isError = false) {
    toast.innerText = msg;
    toast.classList.toggle('ra-toast--error', isError);
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Favoris (Header + Bottom)
const favBtnIds = ['fav-btn', 'header-fav-btn'];
favBtnIds.forEach(id => {
    const btn = document.getElementById(id);
    if (!btn) return;
    
    btn.addEventListener('click', function() {
        const postId = this.getAttribute('data-id');
        const text = document.getElementById('fav-text');

        fetch(`/reader/article/${postId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (r.status === 401) {
                window.location.href = "{{ route('reader.login') }}";
                return;
            }
            return r.json();
        })
        .then(data => {
            if (!data) return;
            
            // Update all favorite buttons on the page
            favBtnIds.forEach(fid => {
                const fbtn = document.getElementById(fid);
                if (fbtn) fbtn.classList.toggle('active', data.favorited);
            });
            
            if (text) text.innerText = data.favorited ? 'Favori' : 'Enregistrer';
            showToast(data.favorited ? 'Ajouté aux favoris' : 'Retiré des favoris');
        })
        .catch(err => {
            showToast('Erreur lors de l’enregistrement', true);
        });
    });
});

// Partage (Header + Bottom)
['share-btn', 'header-share-btn'].forEach(id => {
    const btn = document.getElementById(id);
    if (!btn) return;
    
    btn.addEventListener('click', async function() {
        const shareData = {
            title: "{{ $post->libelle }}",
            text: "{{ $post->sous_titre ?? 'Découvrez cet article sur E-Benin' }}",
            url: window.location.href
        };

        if (navigator.share) {
            try {
                await navigator.share(shareData);
            } catch (err) {}
        } else {
            try {
                await navigator.clipboard.writeText(window.location.href);
                showToast('Lien copié dans le presse-papier');
            } catch (err) {
                showToast('Erreur lors du partage', true);
            }
        }
    });
});

// Scroll vers commentaires
document.getElementById('comment-btn').addEventListener('click', function() {
    document.getElementById('comments-section').scrollIntoView({ behavior: 'smooth' });
});
</script>
@endsection
