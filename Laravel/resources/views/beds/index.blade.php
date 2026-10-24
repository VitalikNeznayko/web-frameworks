@extends('layouts.app')

@section('title', 'Ліжка')

@section('content')
    <div class="toolbar">
        <h1>Ліжка</h1>
        <a href="{{ route('beds.create') }}" class="btn">Додати ліжко</a>
    </div>

    <div class="card">
        @if ($beds->isEmpty())
            <p class="muted">Ліжок ще немає.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Кімната</th>
                    <th>Позначка</th>
                    <th>Тип</th>
                    <th>Стан</th>
                    <th>Бронювань</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($beds as $bed)
                    <tr>
                        <td><a href="{{ route('rooms.show', $bed->room) }}">{{ $bed->room->number }}</a></td>
                        <td><a href="{{ route('beds.show', $bed) }}">{{ $bed->label }}</a></td>
                        <td>{{ $bed->typeLabel() }}</td>
                        <td>
                            @if ($bed->is_active)
                                <span class="badge badge-green">Активне</span>
                            @else
                                <span class="badge badge-red">Неактивне</span>
                            @endif
                        </td>
                        <td>{{ $bed->bookings_count }}</td>
                        <td class="actions">
                            <a href="{{ route('beds.show', $bed) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            <a href="{{ route('beds.edit', $bed) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('beds.destroy', $bed), 'confirm' => 'Видалити ліжко?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $beds->links('pagination::default') }}
        @endif
    </div>
@endsection
