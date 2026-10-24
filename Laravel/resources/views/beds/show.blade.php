@extends('layouts.app')

@section('title', 'Ліжко '.$bed->label)

@section('content')
    <div class="toolbar">
        <h1>Ліжко {{ $bed->label }} (кімната {{ $bed->room->number }})</h1>
        <div class="actions">
            @can('update', $bed)
                <a href="{{ route('beds.edit', $bed) }}" class="btn">Редагувати</a>
            @endcan
            @can('delete', $bed)
                @include('partials.delete', ['action' => route('beds.destroy', $bed), 'confirm' => 'Видалити ліжко?', 'size' => ''])
            @endcan
            <a href="{{ route('beds.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>Кімната</dt>
            <dd><a href="{{ route('rooms.show', $bed->room) }}">{{ $bed->room->number }}</a> ({{ $bed->room->typeLabel() }})</dd>
            <dt>Тип ліжка</dt>
            <dd>{{ $bed->typeLabel() }}</dd>
            <dt>Стан</dt>
            <dd>{{ $bed->is_active ? 'Активне' : 'Неактивне' }}</dd>
        </dl>
    </div>

    @can('viewAny', App\Models\Booking::class)
        <h2>Бронювання ({{ $bed->bookings->count() }})</h2>
        <div class="card">
            @if ($bed->bookings->isEmpty())
                <p class="muted">Для цього ліжка ще немає бронювань.</p>
            @else
                <table>
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Гість</th>
                        <th>Заїзд</th>
                        <th>Виїзд</th>
                        <th>Статус</th>
                        <th>Вартість</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($bed->bookings as $booking)
                        <tr>
                            <td><a href="{{ route('bookings.show', $booking) }}">#{{ $booking->id }}</a></td>
                            <td><a href="{{ route('guests.show', $booking->guest) }}">{{ $booking->guest->full_name }}</a></td>
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
    @endcan
@endsection
