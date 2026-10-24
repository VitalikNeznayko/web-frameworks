@extends('layouts.app')

@section('title', 'Кімнати')

@section('content')
    <div class="toolbar">
        <h1>Кімнати</h1>
        @can('create', App\Models\Room::class)
            <a href="{{ route('rooms.create') }}" class="btn">Додати кімнату</a>
        @endcan
    </div>

    @include('partials.filters', ['filter' => $filter, 'action' => route('rooms.index')])

    <div class="card">
        @if ($rooms->isEmpty())
            @include('partials.empty', ['paginator' => $rooms, 'filter' => $filter, 'message' => 'Кімнат ще немає.'])
        @else
            <table>
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Тип</th>
                    <th>Поверх</th>
                    <th>Ціна за ніч</th>
                    <th>Ліжок</th>
                    <th>Зручності</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td><a href="{{ route('rooms.show', $room) }}">{{ $room->number }}</a></td>
                        <td>{{ $room->typeLabel() }}</td>
                        <td>{{ $room->floor }}</td>
                        <td>{{ number_format((float) $room->price_per_night, 2, '.', ' ') }} грн</td>
                        <td>{{ $room->beds_count }}</td>
                        <td>
                            @forelse ($room->amenities as $amenity)
                                <span class="badge">{{ $amenity->name }}</span>
                            @empty
                                <span class="muted">—</span>
                            @endforelse
                        </td>
                        <td class="actions">
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            @can('update', $room)
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @endcan
                            @can('delete', $room)
                                @include('partials.delete', ['action' => route('rooms.destroy', $room), 'confirm' => 'Видалити кімнату?'])
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @include('partials.pagination', ['paginator' => $rooms])
    </div>
@endsection
