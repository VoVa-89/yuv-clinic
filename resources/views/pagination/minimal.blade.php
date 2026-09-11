@if ($paginator->hasPages())
<nav class="pagination" style="align-items:center;" role="navigation" aria-label="Страницы">
    @if ($paginator->onFirstPage())
        <span style="opacity:0.5">← Назад</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">← Назад</a>
    @endif
    <span>Стр. {{ $paginator->currentPage() }} из {{ $paginator->lastPage() }}</span>
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">Вперёд →</a>
    @else
        <span style="opacity:0.5">Вперёд →</span>
    @endif
</nav>
@endif
