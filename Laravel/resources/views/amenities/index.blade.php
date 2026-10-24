@extends('layouts.app')

@section('title', 'Зручності')

@section('content')
    <div class="toolbar">
        <h1>Зручності</h1>
        <a href="{{ route('amenities.create') }}" class="btn">Додати зручність</a>
    </div>

    @include('partials.filters', ['filter' => $filter, 'action' => route('amenities.index')])

    <div class="card">
        @if ($amenities->isEmpty())
            @include('partials.empty', ['paginator' => $amenities, 'filter' => $filter, 'message' => 'Зручностей ще немає.'])
        @else
            <table>
                <thead>
                <tr>
                    <th>Назва</th>
                    <th>Опис</th>
                    <th>Кімнат</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($amenities as $amenity)
                    <tr>
                        <td><a href="{{ route('amenities.show', $amenity) }}">{{ $amenity->name }}</a></td>
                        <td>{{ $amenity->description ?: '—' }}</td>
                        <td>{{ $amenity->rooms_count }}</td>
                        <td class="actions">
                            <a href="{{ route('amenities.show', $amenity) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            <a href="{{ route('amenities.edit', $amenity) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('amenities.destroy', $amenity), 'confirm' => 'Видалити зручність?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @include('partials.pagination', ['paginator' => $amenities])
    </div>
@endsection
