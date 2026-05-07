@extends('public.layouts.app')

@section('title', 'Nécrologies | E-Benin')
@section('meta_description', "Notices de décès et hommages au Bénin — En mémoire de ceux qui nous ont quittés.")

@section('content')

<div class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">🕯️ Nécrologies</h1>
        <p class="page-hero__text">En mémoire de ceux qui nous ont quittés</p>
    </div>
</div>

<main style="padding:28px 0 56px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                Notices publiées
                @if (!$necrologies->isEmpty())
                    <span style="font-size:.85rem;font-weight:400;color:var(--muted);margin-left:8px;">{{ $necrologies->total() }}</span>
                @endif
            </h2>
            <a href="{{ route('advertiser.register') }}" class="section-more">+ Publier une notice</a>
        </div>

        @if ($necrologies->isEmpty())
            <div style="text-align:center;padding:60px 0;color:var(--muted);">
                <div style="font-size:2.8rem;margin-bottom:12px;">🕊️</div>
                <p>Aucune notice publiée pour le moment.</p>
            </div>
        @else
            <div class="news-grid" style="margin-top:20px;">
                @foreach ($necrologies as $necro)
                <a href="{{ route('necrologies.show', $necro) }}" class="card">
                    <div class="card__img-wrap">
                        @if ($necro->photo)
                            <img class="card__img" src="{{ asset($necro->photo) }}" alt="{{ $necro->nom_defunt }}">
                        @else
                            <div class="card__img" style="background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:var(--muted);">🕊️</div>
                        @endif
                        <span class="card__cat">Nécrologie</span>
                        @if ($necro->video)
                            <span style="position:absolute;bottom:8px;right:8px;background:var(--primary);color:#fff;font-size:.7rem;padding:3px 8px;border-radius:4px;">▶ Vidéo</span>
                        @endif
                    </div>
                    <div class="card__body">
                        <h3 class="card__title">{{ $necro->nom_defunt }}</h3>
                        <p class="card__excerpt" style="color:var(--muted);font-size:.82rem;">
                            @if ($necro->date_naissance)
                                {{ $necro->date_naissance->format('d/m/Y') }} —
                            @endif
                            {{ $necro->date_deces->format('d/m/Y') }}
                        </p>
                        @if ($necro->message)
                            <p class="card__excerpt">{{ Str::limit($necro->message, 110) }}</p>
                        @endif
                    </div>
                    <div class="card__footer">
                        <div class="card__author">{{ $necro->advertiser->company_name ?? $necro->advertiser->name }}</div>
                        <div class="card__meta">{{ $necro->created_at->format('d/m/Y') }}</div>
                    </div>
                </a>
                @endforeach
            </div>

            <div style="margin-top:28px;display:flex;justify-content:center;">
                {{ $necrologies->links() }}
            </div>
        @endif
    </div>
</main>

@endsection
