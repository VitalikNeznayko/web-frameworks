@extends('layouts.app')

@section('title', 'Гості')

@section('content')
    <div class="toolbar">
        <h1>Гості</h1>
        <a href="{{ route('guests.create') }}" class="btn">Додати гостя</a>
    </div>

    @include('partials.filters', ['filter' => $filter, 'action' => route('guests.index')])

    <div class="card">
        @if ($guests->isEmpty())
            @include('partials.empty', ['paginator' => $guests, 'filter' => $filter, 'message' => 'Гостей ще немає.'])
        @else
            <table>
                <thead>
                <tr>
                    <th>ПІБ</th>
                    <th>Email</th>
                    <th>Телефон</th>
                    <th>Країна</th>
                    <th>Бронювань</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($guests as $guest)
                    <tr>
                        <td><a href="{{ route('guests.show', $guest) }}">{{ $guest->full_name }}</a></td>
                        <td>{{ $guest->email }}</td>
                        <td>{{ $guest->phone }}</td>
                        <td>{{ $guest->country }}</td>
                        <td>{{ $guest->bookings_count }}</td>
                        <td class="actions">
                            <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            <a href="{{ route('guests.edit', $guest) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('guests.destroy', $guest), 'confirm' => 'Видалити гостя?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @include('partials.pagination', ['paginator' => $guests])
    </div>
@endsection
