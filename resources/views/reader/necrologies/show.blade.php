@extends('reader.layouts.app')
@section('title', $necrologie->nom_defunt)

@section('content')
<div class="ra-necro-detail">

    <button class="ra-back-btn" onclick="history.back()">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </button>

    <div class="ra-necro-detail__header">
        @if($necrologie->photo)
            <img src="{{ asset($necrologie->photo) }}" alt="{{ $necrologie->nom_defunt }}" class="ra-necro-detail__photo">
        @else
            <div class="ra-necro-detail__photo-ph">🕯️</div>
        @endif
        <div class="ra-necro-detail__name">{{ $necrologie->nom_defunt }}</div>
        <div class="ra-necro-detail__dates">
            @if($necrologie->date_naissance)
                {{ $necrologie->date_naissance->translatedFormat('d F Y') }} —
            @endif
            {{ $necrologie->date_deces->translatedFormat('d F Y') }}
        </div>
    </div>

    @if($necrologie->message)
        <blockquote class="ra-necro-detail__msg">
            {{ $necrologie->message }}
        </blockquote>
    @endif

    @if($necrologie->advertiser)
        <p style="font-size:.8rem;color:var(--muted);text-align:center;margin-top:8px;">
            Publié par {{ $necrologie->advertiser->name }}
        </p>
    @endif

    @if($necrologie->video)
        <video controls class="ra-necro-detail__video">
            <source src="{{ asset($necrologie->video) }}">
        </video>
    @endif

</div>
@endsection
