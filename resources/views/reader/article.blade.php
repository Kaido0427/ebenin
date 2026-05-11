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
        <button class="ra-article__action-btn {{ $isFavorited ? 'active' : '' }}" id="fav-btn">
            <svg viewBox="0 0 24 24" id="fav-icon" style="{{ $isFavorited ? 'fill:#e8191e;stroke:#e8191e' : 'fill:none;stroke:currentColor' }}">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            <span id="fav-label">{{ $isFavorited ? 'Sauvegardé' : 'Sauvegarder' }}</span>
        </button>
        <button class="ra-article__action-btn" id="share-btn">
            <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            <span>Partager</span>
        </button>
        <button class="ra-article__action-btn" id="comment-btn">
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

{{-- Share bottom sheet --}}
<div id="share-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;align-items:flex-end;">
    <div id="share-sheet" style="background:#fff;width:100%;max-width:480px;margin:0 auto;border-radius:20px 20px 0 0;padding:20px 16px calc(20px + env(safe-area-inset-bottom,0px));transform:translateY(100%);transition:transform .28s ease;">
        <div style="width:40px;height:4px;background:#e5e7eb;border-radius:4px;margin:0 auto 20px;"></div>
        <p style="font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);margin-bottom:16px;">Partager via</p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;">
            <a id="share-wa" href="#" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;gap:8px;text-decoration:none;">
                <div style="width:52px;height:52px;border-radius:16px;background:#25D366;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" style="width:28px;height:28px;fill:#fff;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.557 4.118 1.529 5.845L.057 23.5l5.797-1.451A11.942 11.942 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 0 1-5.007-1.372l-.36-.214-3.716.93.992-3.62-.235-.372A9.818 9.818 0 1 1 12 21.818z"/></svg>
                </div>
                <span style="font-size:.68rem;font-weight:700;color:var(--mid);">WhatsApp</span>
            </a>
            <a id="share-fb" href="#" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;gap:8px;text-decoration:none;">
                <div style="width:52px;height:52px;border-radius:16px;background:#1877F2;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" style="width:28px;height:28px;fill:#fff;"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.269h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
                </div>
                <span style="font-size:.68rem;font-weight:700;color:var(--mid);">Facebook</span>
            </a>
            <a id="share-tw" href="#" target="_blank" rel="noopener" style="display:flex;flex-direction:column;align-items:center;gap:8px;text-decoration:none;">
                <div style="width:52px;height:52px;border-radius:16px;background:#000;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" style="width:26px;height:26px;fill:#fff;"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.63 5.905-5.63zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </div>
                <span style="font-size:.68rem;font-weight:700;color:var(--mid);">X / Twitter</span>
            </a>
            <button id="share-copy" style="display:flex;flex-direction:column;align-items:center;gap:8px;background:none;border:none;cursor:pointer;padding:0;">
                <div style="width:52px;height:52px;border-radius:16px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                    <svg viewBox="0 0 24 24" style="width:24px;height:24px;stroke:#374151;fill:none;stroke-width:2;stroke-linecap:round;"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                </div>
                <span style="font-size:.68rem;font-weight:700;color:var(--mid);">Copier lien</span>
            </button>
        </div>
        <button id="share-close" style="width:100%;padding:14px;border:none;border-radius:12px;background:#f3f4f6;font-size:.88rem;font-weight:700;color:var(--mid);cursor:pointer;">Annuler</button>
    </div>
</div>

<script>
(function() {
    var postId   = {{ $post->id }};
    var favState = {{ $isFavorited ? 'true' : 'false' }};
    var csrf     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var pageUrl  = location.href;
    var pageTitle = {{ json_encode($post->libelle) }};

    /* ── Toast ── */
    function showToast(msg, isErr) {
        var t = document.createElement('div');
        t.className = 'ra-toast' + (isErr ? ' ra-toast--error' : '');
        t.textContent = msg;
        document.body.appendChild(t);
        requestAnimationFrame(function() { t.classList.add('show'); });
        setTimeout(function() { t.classList.remove('show'); setTimeout(function() { t.remove(); }, 300); }, 2400);
    }

    /* ── Favori ── */
    function applyFavUI(state) {
        var icon = document.getElementById('fav-icon');
        var lbl  = document.getElementById('fav-label');
        var btn  = document.getElementById('fav-btn');
        if (icon) { icon.style.fill = state ? '#e8191e' : 'none'; icon.style.stroke = state ? '#e8191e' : 'currentColor'; }
        if (lbl)  lbl.textContent = state ? 'Sauvegardé' : 'Sauvegarder';
        if (btn)  btn.classList.toggle('active', state);
    }

    document.getElementById('fav-btn').addEventListener('click', function() {
        var prev = favState;
        favState = !favState;
        applyFavUI(favState);
        showToast(favState ? 'Article sauvegardé' : 'Retiré des favoris');
        fetch('/reader/article/' + postId + '/favorite', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .then(function(r) { if (!r.ok) throw r.status; return r.json(); })
        .then(function(d) { favState = d.favorited; applyFavUI(favState); })
        .catch(function() { favState = prev; applyFavUI(prev); showToast('Connexion requise', true); });
    });

    /* ── Partage ── */
    var overlay = document.getElementById('share-overlay');
    var sheet   = document.getElementById('share-sheet');

    function openShare() {
        var enc = encodeURIComponent(pageUrl);
        var txt = encodeURIComponent(pageTitle + ' — ' + pageUrl);
        document.getElementById('share-wa').href  = 'https://wa.me/?text=' + txt;
        document.getElementById('share-fb').href  = 'https://www.facebook.com/sharer/sharer.php?u=' + enc;
        document.getElementById('share-tw').href  = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(pageTitle) + '&url=' + enc;
        overlay.style.display = 'flex';
        requestAnimationFrame(function() { sheet.style.transform = 'translateY(0)'; });
    }

    function closeShare() {
        sheet.style.transform = 'translateY(100%)';
        setTimeout(function() { overlay.style.display = 'none'; }, 280);
    }

    document.getElementById('share-btn').addEventListener('click', openShare);
    document.getElementById('share-close').addEventListener('click', closeShare);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closeShare(); });

    document.getElementById('share-copy').addEventListener('click', function() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(pageUrl).then(function() { showToast('Lien copié !'); closeShare(); });
        } else {
            var inp = document.createElement('input');
            inp.value = pageUrl; inp.style.cssText = 'position:fixed;opacity:0;';
            document.body.appendChild(inp); inp.focus(); inp.select();
            document.execCommand('copy'); document.body.removeChild(inp);
            showToast('Lien copié !'); closeShare();
        }
    });

    /* ── Commentaires ── */
    document.getElementById('comment-btn').addEventListener('click', function() {
        document.getElementById('comment-form').scrollIntoView({ behavior: 'smooth' });
    });
})();
</script>
@endsection
