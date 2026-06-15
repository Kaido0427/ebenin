@extends('public.layouts.app')

@section('title', 'Le réseau E-Benin — Tous les blogs')
@section('meta_description', 'Découvrez tous les médias et blogs membres du réseau E-Benin.')

@section('content')
<div class="container" style="max-width:1100px; margin:2rem auto; padding:0 1rem;">

    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.6rem; font-weight:800; margin:0 0 .4rem;">Blogs du réseau</h1>
        <p style="color:#888; margin:0;">{{ $organizations->count() }} média{{ $organizations->count() > 1 ? 's' : '' }} membres du réseau E-Benin</p>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:1rem;">
        @foreach ($organizations as $org)
            @php
                $orgUrl = 'https://' . $org->subdomain . '.' . $baseDomain . '/blog';
                $logoSrc = filled($org->organization_logo) ? asset(ltrim($org->organization_logo, '/')) : null;
            @endphp
            <a href="{{ $orgUrl }}" style="display:flex;align-items:center;gap:1rem;padding:1rem 1.2rem;background:var(--card-bg,#fff);border-radius:12px;border:1px solid var(--border,#e5e7eb);text-decoration:none;color:inherit;transition:.18s;box-shadow:0 1px 4px rgba(0,0,0,.05);"
               onmouseover="this.style.borderColor='#e30613';this.style.boxShadow='0 4px 12px rgba(227,6,19,.1)'"
               onmouseout="this.style.borderColor='var(--border,#e5e7eb)';this.style.boxShadow='0 1px 4px rgba(0,0,0,.05)'">
                @if ($logoSrc)
                    <img src="{{ $logoSrc }}" alt="{{ $org->organization_name }}"
                         style="width:56px;height:56px;object-fit:contain;border-radius:8px;flex-shrink:0;background:#f5f5f5;padding:4px;">
                @else
                    <div style="width:56px;height:56px;border-radius:8px;background:#e30613;display:grid;place-items:center;color:#fff;font-weight:700;font-size:1.3rem;flex-shrink:0;">
                        {{ strtoupper(substr($org->organization_name, 0, 1)) }}
                    </div>
                @endif
                <div style="min-width:0;">
                    <div style="font-weight:700;font-size:.95rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $org->organization_name }}</div>
                    @if ($org->organization_phone)
                        <div style="font-size:.8rem;color:#888;margin-top:.2rem;">📞 {{ $org->organization_phone }}</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div style="margin-top:2rem; text-align:center;">
        <a href="https://{{ $baseDomain }}/bloger/register"
           style="display:inline-flex;align-items:center;gap:8px;padding:12px 32px;background:#e30613;color:#fff;border-radius:8px;font-weight:700;text-decoration:none;font-size:.95rem;">
            + Rejoindre le réseau
        </a>
    </div>

</div>
@endsection
