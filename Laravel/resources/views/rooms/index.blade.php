@extends('layouts.app')

@section('title', 'Кімнати')

@section('content')
    <div class="toolbar">
        <h1>Кімнати</h1>
        <a href="{{ route('rooms.create') }}" class="btn">Додати кімнату</a>
    </div>

    <div class="card">
        @if ($rooms->isEmpty())
            <p class="muted">Кімнат ще немає.</p>
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
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('rooms.destroy', $room), 'confirm' => 'Видалити кімнату?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $rooms->links('pagination::default') }}
        @endif
    </div>
@endsection
