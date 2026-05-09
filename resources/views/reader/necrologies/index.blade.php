@extends('reader.layouts.app')
@section('title', 'Nécrologies')

@section('content')
<div class="ra-page">

    <div class="ra-section-head">
        <div class="ra-section-title">Avis de décès</div>
    </div>

    @if($necrologies->isNotEmpty())
        <div class="ra-necro-list">
            @foreach($necrologies as $n)
            <a href="{{ route('reader.necrologie.show', $n) }}" class="ra-necro-card">
                @if($n->photo)
                    <img src="{{ asset($n->photo) }}" alt="{{ $n->nom_defunt }}" class="ra-necro-card__photo" loading="lazy">
                @else
                    <div class="ra-necro-card__photo-ph">🕯️</div>
                @endif
                <div class="ra-necro-card__body">
                    <div class="ra-necro-card__name">{{ $n->nom_defunt }}</div>
                    <div class="ra-necro-card__dates">
                        @if($n->date_naissance) {{ $n->date_naissance->format('d/m/Y') }} — @endif
                        {{ $n->date_deces->format('d/m/Y') }}
                    </div>
                    @if($n->message)
                        <div class="ra-necro-card__msg">{{ $n->message }}</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        @if($necrologies->hasMorePages())
            <a href="{{ $necrologies->nextPageUrl() }}" class="ra-load-more">Voir plus</a>
        @endif
    @else
        <div class="ra-empty">
            <div class="ra-empty__icon">🕯️</div>
            <div class="ra-empty__title">Aucun avis de décès</div>
            <div class="ra-empty__text">Aucun avis de décès publié pour le moment.</div>
        </div>
    @endif

</div>
@endsection
