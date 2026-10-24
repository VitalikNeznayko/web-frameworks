@extends('layouts.app')

@section('title', 'Кімната '.$room->number)

@section('content')
    <div class="toolbar">
        <h1>Кімната {{ $room->number }}</h1>
        <div class="actions">
            <a href="{{ route('rooms.edit', $room) }}" class="btn">Редагувати</a>
            @include('partials.delete', ['action' => route('rooms.destroy', $room), 'confirm' => 'Видалити кімнату?', 'size' => ''])
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>Тип</dt>
            <dd>{{ $room->typeLabel() }}</dd>
            <dt>Поверх</dt>
            <dd>{{ $room->floor }}</dd>
            <dt>Ціна за ніч</dt>
            <dd>{{ number_format((float) $room->price_per_night, 2, '.', ' ') }} грн</dd>
            <dt>Опис</dt>
            <dd>{{ $room->description ?: '—' }}</dd>
            <dt>Зручності</dt>
            <dd>
                @forelse ($room->amenities as $amenity)
                    <a href="{{ route('amenities.show', $amenity) }}" class="badge">{{ $amenity->name }}</a>
                @empty
                    <span class="muted">Немає</span>
                @endforelse
            </dd>
        </dl>
    </div>

    <div class="toolbar">
        <h2>Ліжка ({{ $room->beds->count() }})</h2>
        <a href="{{ route('beds.create') }}" class="btn btn-secondary btn-sm">Додати ліжко</a>
    </div>
    <div class="card">
        @if ($room->beds->isEmpty())
            <p class="muted">У цій кімнаті ще немає ліжок.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Позначка</th>
                    <th>Тип</th>
                    <th>Стан</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($room->beds as $bed)
                    <tr>
                        <td><a href="{{ route('beds.show', $bed) }}">{{ $bed->label }}</a></td>
                        <td>{{ $bed->typeLabel() }}</td>
                        <td>
                            @if ($bed->is_active)
                                <span class="badge badge-green">Активне</span>
                            @else
                                <span class="badge badge-red">Неактивне</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
