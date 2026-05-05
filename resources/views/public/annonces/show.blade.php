@extends('public.layouts.app')

@section('title', $annonce->title . ' | E-Benin Annonces')
@section('meta_description', Str::limit($annonce->description, 150))

@push('head')
<style>
    .ann-breadcrumb { padding: 14px 0; font-size: .83rem; color: var(--muted); }
    .ann-breadcrumb a { color: var(--primary); text-decoration: none; }
    .ann-breadcrumb a:hover { text-decoration: underline; }
    .ann-breadcrumb span { margin: 0 6px; }

    .ann-detail-wrap { padding: 0 0 48px; }
    .ann-detail { background: var(--white); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }

    .ann-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 3px; }
    .ann-gallery img { width: 100%; height: 220px; object-fit: cover; object-position: top center; display: block; }

    .ann-body { padding: 28px 32px; }
    .ann-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; align-items: center; }
    .badge { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: .76rem; font-weight: 700; }
    .badge-emploi { background: #e3f2fd; color: #1565c0; }
    .badge-immobilier { background: #fce4ec; color: #880e4f; }
    .badge-vente_services { background: #f3e5f5; color: #6a1b9a; }
    .badge-evenements { background: #fff8e1; color: #f57f17; }

    .ann-title { font-size: 1.55rem; font-weight: 700; color: var(--dark); margin-bottom: 8px; line-height: 1.3; }
    .ann-price { font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 20px; }
    .ann-layout { display: grid; grid-template-columns: 1fr 270px; gap: 24px; margin-top: 24px; }

    .ann-desc { font-size: .93rem; color: var(--mid); line-height: 1.75; white-space: pre-wrap; }
    .ann-date { font-size: .78rem; color: var(--muted); margin-top: 14px; }

    .ann-contact-card {
        background: var(--bg);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        padding: 20px;
        position: sticky;
        top: 80px;
    }
    .ann-contact-card h3 { font-size: .97rem; font-weight: 700; margin-bottom: 14px; color: var(--dark); }
    .ann-contact-item { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; font-size: .88rem; color: var(--mid); }
    .ann-contact-item a { color: var(--primary); text-decoration: none; font-weight: 600; }
    .ann-contact-item a:hover { text-decoration: underline; }
    .ann-advertiser { margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border); font-size: .83rem; color: var(--muted); }

    @media (max-width: 680px) {
        .ann-layout { grid-template-columns: 1fr; }
        .ann-body { padding: 20px 16px; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="ann-breadcrumb">
        <a href="{{ route('annonces.index') }}">Annonces</a>
        <span>›</span>
        <a href="{{ route('annonces.index', ['category' => $annonce->category]) }}">{{ $annonce->category_label }}</a>
        <span>›</span>
        {{ Str::limit($annonce->title, 60) }}
    </div>
</div>

<div class="ann-detail-wrap">
    <div class="container">
        <div class="ann-detail">
            @if ($annonce->images && count($annonce->images) > 0)
                <div class="ann-gallery">
                    @foreach ($annonce->images as $img)
                        <img src="{{ asset($img) }}" alt="{{ $annonce->title }}">
                    @endforeach
                </div>
            @endif

            <div class="ann-body">
                <div class="ann-meta">
                    <span class="badge badge-{{ $annonce->category }}">{{ $annonce->category_label }}</span>
                    @if ($annonce->location)
                        <span style="color:var(--muted);font-size:.84rem;">📍 {{ $annonce->location }}</span>
                    @endif
                </div>

                <div class="ann-title">{{ $annonce->title }}</div>

                @if ($annonce->price)
                    <div class="ann-price">{{ number_format($annonce->price, 0, ',', ' ') }} FCFA</div>
                @endif

                <div class="ann-layout">
                    <div>
                        <div class="ann-desc">{{ $annonce->description }}</div>
                        <div class="ann-date">Publiée le {{ $annonce->created_at->format('d/m/Y') }}</div>
                    </div>

                    <div>
                        <div class="ann-contact-card">
                            <h3>Contacter l'annonceur</h3>
                            @if ($annonce->contact_phone)
                                <div class="ann-contact-item">
                                    📞 <a href="tel:{{ $annonce->contact_phone }}">{{ $annonce->contact_phone }}</a>
                                </div>
                            @endif
                            @if ($annonce->contact_email)
                                <div class="ann-contact-item">
                                    ✉️ <a href="mailto:{{ $annonce->contact_email }}">{{ $annonce->contact_email }}</a>
                                </div>
                            @endif
                            @if (!$annonce->contact_phone && !$annonce->contact_email)
                                <p style="color:var(--muted);font-size:.84rem;">Aucune coordonnée renseignée.</p>
                            @endif
                            <div class="ann-advertiser">
                                Publié par <strong>{{ $annonce->advertiser->company_name ?? $annonce->advertiser->name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
