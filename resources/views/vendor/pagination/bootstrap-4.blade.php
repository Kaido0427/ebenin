@if ($paginator->hasPages())
<nav>
    <ul class="pagination">
        {{-- Précédent --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-btn" style="width:auto;padding:0 14px;">‹ Précédent</span></li>
        @else
            <li class="page-item"><a class="page-btn" style="width:auto;padding:0 14px;" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Précédent</a></li>
        @endif

        {{-- Numéros --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-btn page-btn--dots">{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-btn active">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-btn" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Suivant --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-btn" style="width:auto;padding:0 14px;" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant ›</a></li>
        @else
            <li class="page-item disabled"><span class="page-btn" style="width:auto;padding:0 14px;">Suivant ›</span></li>
        @endif
    </ul>
</nav>
@endif
