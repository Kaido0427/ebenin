@extends('public.layouts.app')

@section('title', $necrologie->nom_defunt . ' | Nécrologies E-Benin')
@section('meta_description', Str::limit($necrologie->message ?? ('Notice de décès de ' . $necrologie->nom_defunt), 150))

@section('content')

<div style="background:var(--white);border-bottom:1px solid var(--border);padding:10px 0;">
    <div class="container">
        <nav style="font-size:.82rem;color:var(--muted);">
            <a href="{{ route('necrologies.index') }}" style="color:var(--primary);text-decoration:none;">Nécrologies</a>
            <span style="margin:0 6px;">›</span>
            {{ $necrologie->nom_defunt }}
        </nav>
    </div>
</div>

<main style="padding:28px 0 56px;">
    <div class="container">
        <div style="max-width:740px;margin:0 auto;">
            <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">

                @if ($necrologie->video)
                    <video style="width:100%;max-height:420px;display:block;" controls>
                        <source src="{{ asset($necrologie->video) }}">
                    </video>
                @elseif ($necrologie->photo)
                    <img src="{{ asset($necrologie->photo) }}" alt="{{ $necrologie->nom_defunt }}" style="width:100%;max-height:420px;object-fit:cover;object-position:top center;display:block;">
                @endif

                <div style="padding:32px 36px;">
                    <div style="text-align:center;font-size:1.4rem;margin-bottom:20px;letter-spacing:.3em;">🕯️ 🕊️ 🕯️</div>

                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:20px;">
                        <div>
                            <h1 style="font-size:1.8rem;font-weight:800;color:var(--dark);margin:0 0 8px;line-height:1.2;">{{ $necrologie->nom_defunt }}</h1>
                            <div style="font-size:.88rem;color:var(--muted);display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                                @if ($necrologie->date_naissance)
                                    <span>Né(e) le {{ $necrologie->date_naissance->format('d/m/Y') }}</span>
                                    <span style="color:var(--border);">•</span>
                                @endif
                                <span>Décédé(e) le {{ $necrologie->date_deces->format('d/m/Y') }}</span>
                                @if ($necrologie->date_naissance)
                                    @php $age = $necrologie->date_naissance->diffInYears($necrologie->date_deces); @endphp
                                    <span style="color:var(--border);">•</span>
                                    <span>{{ $age }} ans</span>
                                @endif
                            </div>
                        </div>
                        <span class="card__cat" style="position:static;flex-shrink:0;">Nécrologie</span>
                    </div>

                    @if ($necrologie->message)
                        <hr style="border:none;border-top:1px solid var(--border);margin:22px 0;">
                        <blockquote style="font-size:.97rem;color:var(--mid);line-height:1.9;white-space:pre-wrap;font-style:italic;margin:0;border-left:3px solid var(--primary);padding-left:18px;">{{ $necrologie->message }}</blockquote>
                    @endif

                    <div style="margin-top:28px;padding-top:16px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:.82rem;color:var(--muted);">
                        <span>Publié par <strong style="color:var(--dark);">{{ $necrologie->advertiser->company_name ?? $necrologie->advertiser->name }}</strong></span>
                        <span>{{ $necrologie->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;text-align:center;">
                <a href="{{ route('necrologies.index') }}" class="btn btn--outline">← Toutes les nécrologies</a>
            </div>
        </div>
    </div>
</main>

@push('head')
<style>
@media (max-width:540px) {
    main .container > div > div > div:nth-child(2) { padding: 20px 16px !important; }
}
</style>
@endpush

@endsection
