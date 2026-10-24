<form method="GET" action="{{ $action }}" class="card filters">
    <div class="filter-grid">
        @foreach ($filter->definitions() as $name => $field)
            @php($keys = $filter->inputKeys($name, $field['type']))
            <div class="field">
                <label for="filter-{{ $keys[0] }}">{{ $field['label'] }}</label>
                @if ($field['type'] === 'exact')
                    <input type="number" min="1" step="1" id="filter-{{ $name }}" name="{{ $name }}" value="{{ $filter->value($name) }}">
                @elseif ($field['type'] === 'text')
                    <input type="text" id="filter-{{ $name }}" name="{{ $name }}" value="{{ $filter->value($name) }}" placeholder="містить…">
                @elseif (in_array($field['type'], ['enum', 'select', 'relation'], true))
                    <select id="filter-{{ $name }}" name="{{ $name }}">
                        <option value="">Усі</option>
                        @foreach ($field['options'] as $key => $optionLabel)
                            <option value="{{ $key }}" @selected($filter->value($name) === (string) $key)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                @elseif ($field['type'] === 'boolean')
                    <select id="filter-{{ $name }}" name="{{ $name }}">
                        <option value="">Усі</option>
                        <option value="1" @selected($filter->value($name) === '1')>Так</option>
                        <option value="0" @selected($filter->value($name) === '0')>Ні</option>
                    </select>
                @elseif ($field['type'] === 'range')
                    <div class="range">
                        <input type="number" step="{{ $field['step'] ?? 'any' }}" id="filter-{{ $keys[0] }}" name="{{ $keys[0] }}" value="{{ $filter->value($keys[0]) }}" placeholder="від">
                        <input type="number" step="{{ $field['step'] ?? 'any' }}" name="{{ $keys[1] }}" value="{{ $filter->value($keys[1]) }}" placeholder="до" aria-label="{{ $field['label'] }} до">
                    </div>
                @elseif ($field['type'] === 'date')
                    <div class="range">
                        <input type="date" id="filter-{{ $keys[0] }}" name="{{ $keys[0] }}" value="{{ $filter->value($keys[0]) }}" title="від">
                        <input type="date" name="{{ $keys[1] }}" value="{{ $filter->value($keys[1]) }}" title="до" aria-label="{{ $field['label'] }} до">
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if (request()->query('itemsPerPage') !== null)
        <input type="hidden" name="itemsPerPage" value="{{ \App\Http\Controllers\Controller::perPage(request()) }}">
    @endif

    @if ($filter->invalid())
        <p class="error-text">Некоректні значення фільтрів проігноровано: {{ implode(', ', $filter->invalid()) }}.</p>
    @endif

    <div class="filter-actions">
        <button type="submit" class="btn">Фільтрувати</button>
        <a href="{{ $action }}" class="btn btn-secondary">Скинути</a>
    </div>
</form>
