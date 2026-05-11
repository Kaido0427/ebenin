@if ($paginator->hasPages())
@php
    $cur  = $paginator->currentPage();
    $last = $paginator->lastPage();
    // fenêtre : page courante ± 1 (max 3 numéros au centre)
    $from = max(1, $cur - 1);
    $to   = min($last, $cur + 1);
@endphp
<nav>
    <ul class="pagination">

        {{-- Précédent --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-btn" style="width:auto;padding:0 14px;">‹ Préc.</span></li>
        @else
            <li class="page-item"><a class="page-btn" style="width:auto;padding:0 14px;" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Préc.</a></li>
        @endif

        {{-- Première page --}}
        @if ($from > 1)
            <li class="page-item"><a class="page-btn" href="{{ $paginator->url(1) }}">1</a></li>
            @if ($from > 2)
                <li class="page-item disabled"><span class="page-btn page-btn--dots">…</span></li>
            @endif
        @endif

        {{-- Fenêtre centrale --}}
        @for ($p = $from; $p <= $to; $p++)
            @if ($p == $cur)
                <li class="page-item active"><span class="page-btn active">{{ $p }}</span></li>
            @else
                <li class="page-item"><a class="page-btn" href="{{ $paginator->url($p) }}">{{ $p }}</a></li>
            @endif
        @endfor

        {{-- Dernière page --}}
        @if ($to < $last)
            @if ($to < $last - 1)
                <li class="page-item disabled"><span class="page-btn page-btn--dots">…</span></li>
            @endif
            <li class="page-item"><a class="page-btn" href="{{ $paginator->url($last) }}">{{ $last }}</a></li>
        @endif

        {{-- Suivant --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-btn" style="width:auto;padding:0 14px;" href="{{ $paginator->nextPageUrl() }}" rel="next">Suiv. ›</a></li>
        @else
            <li class="page-item disabled"><span class="page-btn" style="width:auto;padding:0 14px;">Suiv. ›</span></li>
        @endif

    </ul>
</nav>
@endif
