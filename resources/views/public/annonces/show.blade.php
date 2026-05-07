@extends('public.layouts.app')

@section('title', $annonce->title . ' | E-Benin Annonces')
@section('meta_description', Str::limit($annonce->description, 150))

@section('content')

<div style="background:var(--white);border-bottom:1px solid var(--border);padding:10px 0;">
    <div class="container">
        <nav style="font-size:.82rem;color:var(--muted);">
            <a href="{{ route('annonces.index') }}" style="color:var(--primary);text-decoration:none;">Annonces</a>
            <span style="margin:0 6px;">›</span>
            <a href="{{ route('annonces.index', ['category' => $annonce->category]) }}" style="color:var(--primary);text-decoration:none;">{{ $annonce->category_label }}</a>
            <span style="margin:0 6px;">›</span>
            {{ Str::limit($annonce->title, 55) }}
        </nav>
    </div>
</div>

<main style="padding:28px 0 56px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 290px;gap:24px;align-items:start;">

            {{-- Contenu principal --}}
            <div>
                <div style="background:var(--white);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">

                    @if ($annonce->images && count($annonce->images) > 0)
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:2px;">
                            @foreach ($annonce->images as $img)
                                <img src="{{ asset($img) }}" alt="{{ $annonce->title }}" style="width:100%;height:220px;object-fit:cover;object-position:top;display:block;">
                            @endforeach
                        </div>
                    @endif

                    <div style="padding:28px 28px 24px;">
                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:14px;">
                            <span class="card__cat" style="position:static;font-size:.78rem;">{{ $annonce->category_label }}</span>
                            @if ($annonce->location)
                                <span style="font-size:.83rem;color:var(--muted);">📍 {{ $annonce->location }}</span>
                            @endif
                            <span style="font-size:.78rem;color:var(--muted);margin-left:auto;">{{ $annonce->created_at->diffForHumans() }}</span>
                        </div>

                        <h1 style="font-size:1.5rem;font-weight:700;color:var(--dark);margin:0 0 12px;line-height:1.3;">{{ $annonce->title }}</h1>

                        @if ($annonce->price)
                            <div style="font-size:1.4rem;font-weight:800;color:var(--primary);margin-bottom:20px;">
                                {{ number_format($annonce->price, 0, ',', ' ') }} FCFA
                            </div>
                        @endif

                        <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">

                        <div style="font-size:.93rem;color:var(--mid);line-height:1.8;white-space:pre-wrap;">{{ $annonce->description }}</div>
                    </div>
                </div>
            </div>

            {{-- Carte contact --}}
            <div style="position:sticky;top:80px;">
                <div style="background:var(--bg);border:1px solid var(--border);border-radius:var(--radius);padding:22px;">
                    <h3 style="font-size:.97rem;font-weight:700;margin:0 0 16px;color:var(--dark);">Contacter l'annonceur</h3>

                    @if ($annonce->contact_phone)
                        <a href="tel:{{ $annonce->contact_phone }}" class="btn btn--primary" style="width:100%;justify-content:center;margin-bottom:10px;">
                            📞 {{ $annonce->contact_phone }}
                        </a>
                    @endif
                    @if ($annonce->contact_email)
                        <a href="mailto:{{ $annonce->contact_email }}" class="btn btn--outline" style="width:100%;justify-content:center;">
                            ✉️ {{ $annonce->contact_email }}
                        </a>
                    @endif
                    @if (!$annonce->contact_phone && !$annonce->contact_email)
                        <p style="color:var(--muted);font-size:.84rem;margin:0;">Aucune coordonnée renseignée.</p>
                    @endif

                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);font-size:.82rem;color:var(--muted);">
                        Publié par <strong style="color:var(--dark);">{{ $annonce->advertiser->company_name ?? $annonce->advertiser->name }}</strong>
                    </div>
                </div>

                <div style="margin-top:14px;background:var(--white);border:1px solid var(--border);border-radius:var(--radius);padding:16px;text-align:center;">
                    <p style="font-size:.82rem;color:var(--muted);margin:0 0 10px;">Vous aussi, publiez sur E-Benin</p>
                    <a href="{{ route('advertiser.register') }}" class="btn btn--outline" style="width:100%;justify-content:center;font-size:.83rem;">+ Publier une annonce</a>
                </div>
            </div>

        </div>
    </div>
</main>

@push('head')
<style>
@media (max-width: 700px) {
    main > .container > div { grid-template-columns: 1fr !important; }
    main > .container > div > div:last-child { position: static !important; }
}
</style>
@endpush

@endsection
