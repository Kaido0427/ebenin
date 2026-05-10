@extends('reader.layouts.app')
@section('title', 'Catégories')
@section('body_class', 'body--no-tabs')

@section('content')
<div class="ra-page">

    <div class="ra-section-head" style="padding-top:18px;">
        <div class="ra-section-title" style="font-size:1rem;">Toutes les catégories</div>
    </div>

    <div class="ra-cat-list">
        @foreach($categories as $i => $cat)
        @php
            $colorIdx = $cat->id % 12;
            // Icônes SVG selon mots-clés du nom de catégorie
            $name = strtolower($cat->name);
            if (str_contains($name, 'polit'))       $icon = '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>';
            elseif (str_contains($name, 'écon') || str_contains($name, 'econ') || str_contains($name, 'financ') || str_contains($name, 'business')) $icon = '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>';
            elseif (str_contains($name, 'sport'))   $icon = '<circle cx="12" cy="12" r="10"/><path d="M4.93 4.93l4.24 4.24"/><path d="M14.83 9.17l4.24-4.24"/><path d="M14.83 14.83l4.24 4.24"/><path d="M9.17 14.83l-4.24 4.24"/><circle cx="12" cy="12" r="4"/>';
            elseif (str_contains($name, 'cultu'))   $icon = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>';
            elseif (str_contains($name, 'socié') || str_contains($name, 'socie')) $icon = '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>';
            elseif (str_contains($name, 'intern') || str_contains($name, 'monde') || str_contains($name, 'afrique')) $icon = '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>';
            elseif (str_contains($name, 'tech') || str_contains($name, 'numéri') || str_contains($name, 'numeri')) $icon = '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>';
            elseif (str_contains($name, 'santé') || str_contains($name, 'sante') || str_contains($name, 'méd') || str_contains($name, 'med')) $icon = '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>';
            elseif (str_contains($name, 'éduc') || str_contains($name, 'educ') || str_contains($name, 'école') || str_contains($name, 'ecole')) $icon = '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>';
            elseif (str_contains($name, 'envir') || str_contains($name, 'nature') || str_contains($name, 'agri')) $icon = '<path d="M12 22V12"/><path d="M5 12H2a10 10 0 0 0 20 0h-3"/><path d="M8 6l4-4 4 4"/><path d="M2 18s4 2 10 2 10-2 10-2"/>';
            elseif (str_contains($name, 'people') || str_contains($name, 'celeb') || str_contains($name, 'entert')) $icon = '<circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/>';
            elseif (str_contains($name, 'sécur') || str_contains($name, 'secur') || str_contains($name, 'défense') || str_contains($name, 'defense')) $icon = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>';
            else $icon = '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';
        @endphp
        <a href="/reader?cat={{ $cat->id }}" class="ra-cat-list-item">
            <div class="ra-cat-list-icon cat-bg-{{ $colorIdx }}">
                <svg viewBox="0 0 24 24">{!! $icon !!}</svg>
            </div>
            <div class="ra-cat-list-name">{{ $cat->name }}</div>
            @if($cat->posts_count)
                <div style="font-size:.7rem;color:var(--muted);margin-right:6px;">{{ $cat->posts_count }}</div>
            @endif
            <div class="ra-cat-list-arrow">
                <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
