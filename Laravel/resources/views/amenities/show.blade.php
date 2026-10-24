@extends('layouts.app')

@section('title', $amenity->name)

@section('content')
    <div class="toolbar">
        <h1>{{ $amenity->name }}</h1>
        <div class="actions">
            <a href="{{ route('amenities.edit', $amenity) }}" class="btn">Редагувати</a>
            @include('partials.delete', ['action' => route('amenities.destroy', $amenity), 'confirm' => 'Видалити зручність?', 'size' => ''])
            <a href="{{ route('amenities.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>Опис</dt>
            <dd>{{ $amenity->description ?: '—' }}</dd>
        </dl>
    </div>

    <h2>Кімнати з цією зручністю ({{ $amenity->rooms->count() }})</h2>
    <div class="card">
        @if ($amenity->rooms->isEmpty())
            <p class="muted">Жодна кімната не має цієї зручності.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Тип</th>
                    <th>Поверх</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($amenity->rooms as $room)
                    <tr>
                        <td><a href="{{ route('rooms.show', $room) }}">{{ $room->number }}</a></td>
                        <td>{{ $room->typeLabel() }}</td>
                        <td>{{ $room->floor }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
