@extends('public.layouts.app')

@section('title', $necrologie->nom_defunt . ' | Nécrologies E-Benin')
@section('meta_description', Str::limit($necrologie->message ?? ('Notice de décès de ' . $necrologie->nom_defunt), 150))

@push('head')
<style>
    .nec-detail-wrap {
        background: linear-gradient(180deg, var(--dark) 0%, #12131a 180px);
        min-height: 100vh;
        padding: 32px 0 56px;
    }

    .nec-breadcrumb { font-size: .83rem; color: #666; padding-bottom: 20px; }
    .nec-breadcrumb a { color: #888; text-decoration: none; }
    .nec-breadcrumb a:hover { color: #ccc; }
    .nec-breadcrumb span { margin: 0 6px; }

    .nec-card {
        background: #0d0d1a;
        border: 1px solid rgba(255,255,255,.07);
        border-radius: 14px;
        overflow: hidden;
        max-width: 780px;
        margin: 0 auto;
    }

    .nec-photo { width: 100%; max-height: 420px; object-fit: cover; object-position: top center; display: block; }
    .nec-video { width: 100%; max-height: 420px; display: block; }

    .nec-body { padding: 32px 36px; }
    .candle-row { text-align: center; font-size: 1.3rem; margin-bottom: 18px; letter-spacing: .3em; }
    .nec-name { font-size: 1.9rem; font-weight: 700; color: #eee; margin-bottom: 7px; }
    .nec-dates { font-size: .93rem; color: #777; margin-bottom: 22px; display: flex; gap: 14px; align-items: center; flex-wrap: wrap; }
    .nec-dates .sep { color: #444; }
    .nec-divider { border: none; border-top: 1px solid rgba(255,255,255,.07); margin: 22px 0; }
    .nec-message { font-size: .97rem; color: #bbb; line-height: 1.85; white-space: pre-wrap; font-style: italic; }
    .nec-footer { margin-top: 28px; padding-top: 18px; border-top: 1px solid rgba(255,255,255,.05); display: flex; justify-content: space-between; align-items: center; font-size: .8rem; color: #555; }

    @media (max-width: 540px) {
        .nec-body { padding: 20px 16px; }
        .nec-name { font-size: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="nec-detail-wrap">
    <div class="container">
        <div class="nec-breadcrumb">
            <a href="{{ route('necrologies.index') }}">Nécrologies</a>
            <span>›</span>
            {{ $necrologie->nom_defunt }}
        </div>

        <div class="nec-card">
            @if ($necrologie->video)
                <video class="nec-video" controls>
                    <source src="{{ asset($necrologie->video) }}">
                </video>
            @elseif ($necrologie->photo)
                <img class="nec-photo" src="{{ asset($necrologie->photo) }}" alt="{{ $necrologie->nom_defunt }}">
            @endif

            <div class="nec-body">
                <div class="candle-row">🕯️ 🕊️ 🕯️</div>

                <div class="nec-name">{{ $necrologie->nom_defunt }}</div>

                <div class="nec-dates">
                    @if ($necrologie->date_naissance)
                        <span>Né(e) le {{ $necrologie->date_naissance->format('d/m/Y') }}</span>
                        <span class="sep">•</span>
                    @endif
                    <span>Décédé(e) le {{ $necrologie->date_deces->format('d/m/Y') }}</span>
                    @if ($necrologie->date_naissance)
                        @php $age = $necrologie->date_naissance->diffInYears($necrologie->date_deces); @endphp
                        <span class="sep">•</span>
                        <span>{{ $age }} ans</span>
                    @endif
                </div>

                @if ($necrologie->message)
                    <hr class="nec-divider">
                    <div class="nec-message">{{ $necrologie->message }}</div>
                @endif

                <div class="nec-footer">
                    <span>Publié par <strong>{{ $necrologie->advertiser->company_name ?? $necrologie->advertiser->name }}</strong></span>
                    <span>{{ $necrologie->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
