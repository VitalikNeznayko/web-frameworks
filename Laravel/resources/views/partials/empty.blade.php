<p class="muted">
    @if ($paginator->total() > 0)
        На цій сторінці записів немає. <a href="{{ $paginator->url(1) }}">Перейти на першу сторінку</a>.
    @elseif ($filter->isActive())
        За заданими фільтрами нічого не знайдено.
    @else
        {{ $message }}
    @endif
</p>
