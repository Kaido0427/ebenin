@extends('public.layouts.app')

@section('title', 'Nécrologies | E-Benin')
@section('meta_description', "Notices de décès et hommages au Bénin — En mémoire de ceux qui nous ont quittés.")

@push('head')
<style>
    .nec-hero {
        background: linear-gradient(180deg, var(--dark) 0%, #1a1d2e 100%);
        color: #fff;
        padding: 44px 0;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .nec-hero h1 { font-size: 1.9rem; font-weight: 700; margin: 0 0 6px; }
    .nec-hero p { color: #888; margin: 0; font-size: .93rem; }
    .nec-hero .candle { font-size: 1.7rem; margin-bottom: 10px; }

    .nec-section { padding: 36px 0 56px; background: #12131a; min-height: 60vh; }
    .nec-section__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .nec-section__head h2 { font-size: 1.15rem; font-weight: 700; color: #ccc; }

    .nec-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(272px, 1fr)); gap: 22px; }

    .nec-card {
        background: #0d0d1a;
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: border-color .2s, transform .2s;
        display: flex;
        flex-direction: column;
    }
    .nec-card:hover { border-color: rgba(201,168,76,.35); transform: translateY(-2px); }

    .nec-card__photo {
        height: 200px;
        overflow: hidden;
        position: relative;
        background: #0a0a12;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        font-size: 3rem;
    }
    .nec-card__photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; }

    .nec-card__body { padding: 18px 20px; flex: 1; }
    .nec-card__name { font-size: 1.08rem; font-weight: 700; color: #eee; margin-bottom: 5px; }
    .nec-card__dates { font-size: .8rem; color: #666; margin-bottom: 10px; }
    .nec-card__msg { font-size: .84rem; color: #888; line-height: 1.6; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }

    .nec-card__footer {
        padding: 10px 20px;
        border-top: 1px solid rgba(255,255,255,.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .74rem;
    }
    .nec-card__by { color: #555; }
    .nec-card__date { color: #444; }
    .has-video { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,.7); color: #eee; font-size: .7rem; padding: 3px 8px; border-radius: 4px; }

    .empty-state { text-align: center; padding: 60px 0; color: #555; }
    .empty-state .icon { font-size: 2.8rem; margin-bottom: 10px; }

    .pagination-wrap { margin-top: 36px; display: flex; justify-content: center; }
    .pagination-wrap .page-link { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.08); color: #888; }
    .pagination-wrap .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }

    .nec-publish-cta {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: var(--radius);
        padding: 8px 16px;
        font-size: .83rem;
        color: #888;
        text-decoration: none;
        transition: all .2s;
    }
    .nec-publish-cta:hover { border-color: rgba(201,168,76,.4); color: #c9a84c; }
</style>
@endpush

@section('content')

<div class="nec-hero">
    <div class="candle">🕯️</div>
    <h1>Nécrologies</h1>
    <p>En mémoire de ceux qui nous ont quittés</p>
</div>

<div class="nec-section">
    <div class="container">
        @if ($necrologies->isEmpty())
            <div class="empty-state">
                <div class="icon">🕊️</div>
                <p>Aucune notice publiée pour le moment.</p>
            </div>
        @else
            <div class="nec-section__head">
                <h2>{{ $necrologies->total() }} notice(s) publiée(s)</h2>
                <a href="{{ route('advertiser.register') }}" class="nec-publish-cta">+ Publier une notice</a>
            </div>
            <div class="nec-grid">
                @foreach ($necrologies as $necro)
                <a href="{{ route('necrologies.show', $necro) }}" class="nec-card">
                    <div class="nec-card__photo">
                        @if ($necro->photo)
                            <img src="{{ asset($necro->photo) }}" alt="{{ $necro->nom_defunt }}">
                        @else
                            🕊️
                        @endif
                        @if ($necro->video)
                            <span class="has-video">▶ Vidéo</span>
                        @endif
                    </div>
                    <div class="nec-card__body">
                        <div class="nec-card__name">{{ $necro->nom_defunt }}</div>
                        <div class="nec-card__dates">
                            @if ($necro->date_naissance)
                                {{ $necro->date_naissance->format('d/m/Y') }} —
                            @endif
                            {{ $necro->date_deces->format('d/m/Y') }}
                        </div>
                        @if ($necro->message)
                            <div class="nec-card__msg">{{ $necro->message }}</div>
                        @endif
                    </div>
                    <div class="nec-card__footer">
                        <span class="nec-card__by">{{ $necro->advertiser->company_name ?? $necro->advertiser->name }}</span>
                        <span class="nec-card__date">{{ $necro->created_at->format('d/m/Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $necrologies->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
