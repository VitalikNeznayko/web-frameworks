@extends('layouts.app')

@section('title', $guest->full_name)

@section('content')
    <div class="toolbar">
        <h1>{{ $guest->full_name }}</h1>
        <div class="actions">
            @can('update', $guest)
                <a href="{{ route('guests.edit', $guest) }}" class="btn">Редагувати</a>
            @endcan
            @can('delete', $guest)
                @include('partials.delete', ['action' => route('guests.destroy', $guest), 'confirm' => 'Видалити гостя?', 'size' => ''])
            @endcan
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>Email</dt>
            <dd>{{ $guest->email }}</dd>
            <dt>Телефон</dt>
            <dd>{{ $guest->phone }}</dd>
            <dt>Номер паспорта</dt>
            <dd>{{ $guest->passport_number }}</dd>
            <dt>Країна</dt>
            <dd>{{ $guest->country }}</dd>
            <dt>Дата народження</dt>
            <dd>{{ $guest->birth_date?->format('d.m.Y') ?? '—' }}</dd>
        </dl>
    </div>

    <div class="toolbar">
        <h2>Бронювання ({{ $guest->bookings->count() }})</h2>
        @can('create', App\Models\Booking::class)
            <a href="{{ route('bookings.create') }}" class="btn btn-secondary btn-sm">Нове бронювання</a>
        @endcan
    </div>
    <div class="card">
        @if ($guest->bookings->isEmpty())
            <p class="muted">У гостя ще немає бронювань.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Кімната / ліжко</th>
                    <th>Заїзд</th>
                    <th>Виїзд</th>
                    <th>Статус</th>
                    <th>Вартість</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($guest->bookings as $booking)
                    <tr>
                        <td><a href="{{ route('bookings.show', $booking) }}">#{{ $booking->id }}</a></td>
                        <td>{{ $booking->bed->room->number }} / <a href="{{ route('beds.show', $booking->bed) }}">{{ $booking->bed->label }}</a></td>
                        <td>{{ $booking->check_in->format('d.m.Y') }}</td>
                        <td>{{ $booking->check_out->format('d.m.Y') }}</td>
                        <td><span class="badge">{{ $booking->statusLabel() }}</span></td>
                        <td>{{ number_format((float) $booking->total_price, 2, '.', ' ') }} грн</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
