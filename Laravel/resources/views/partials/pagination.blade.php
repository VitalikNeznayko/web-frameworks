@if ($paginator->total() > 0)
    <div class="pager">
        <div class="pager-info">
            @if ($paginator->count() > 0)
                Показано {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} з {{ $paginator->total() }}
            @else
                На сторінці {{ $paginator->currentPage() }} записів немає (усього {{ $paginator->total() }})
            @endif
        </div>

        {{ $paginator->links('partials.pagination-links') }}

        <form method="GET" class="per-page">
            @foreach (request()->except(['page', 'itemsPerPage']) as $key => $value)
                @if (is_string($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <label for="itemsPerPage">На сторінці:</label>
            <select id="itemsPerPage" name="itemsPerPage" onchange="this.form.submit()">
                @foreach (\App\Http\Controllers\Controller::PER_PAGE_OPTIONS as $option)
                    <option value="{{ $option }}" @selected($paginator->perPage() === $option)>{{ $option }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-secondary btn-sm">OK</button></noscript>
        </form>
    </div>
@endif
