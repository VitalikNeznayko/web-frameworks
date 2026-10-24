@extends('layouts.app')

@section('title', 'Бронювання')

@section('content')
    <div class="toolbar">
        <h1>Бронювання</h1>
        <a href="{{ route('bookings.create') }}" class="btn">Нове бронювання</a>
    </div>

    <div class="card">
        @if ($bookings->isEmpty())
            <p class="muted">Бронювань ще немає.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Гість</th>
                    <th>Кімната / ліжко</th>
                    <th>Заїзд</th>
                    <th>Виїзд</th>
                    <th>Статус</th>
                    <th>Вартість</th>
                    <th>Сплачено</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td><a href="{{ route('bookings.show', $booking) }}">#{{ $booking->id }}</a></td>
                        <td><a href="{{ route('guests.show', $booking->guest) }}">{{ $booking->guest->full_name }}</a></td>
                        <td>{{ $booking->bed->room->number }} / <a href="{{ route('beds.show', $booking->bed) }}">{{ $booking->bed->label }}</a></td>
                        <td>{{ $booking->check_in->format('d.m.Y') }}</td>
                        <td>{{ $booking->check_out->format('d.m.Y') }}</td>
                        <td><span class="badge">{{ $booking->statusLabel() }}</span></td>
                        <td>{{ number_format((float) $booking->total_price, 2, '.', ' ') }} грн</td>
                        <td>{{ number_format((float) $booking->payments_sum_amount, 2, '.', ' ') }} грн</td>
                        <td class="actions">
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('bookings.destroy', $booking), 'confirm' => 'Видалити бронювання разом із платежами?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $bookings->links('pagination::default') }}
        @endif
    </div>
@endsection
