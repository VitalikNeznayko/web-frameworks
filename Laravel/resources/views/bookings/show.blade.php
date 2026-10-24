@extends('layouts.app')

@section('title', 'Бронювання #'.$booking->id)

@section('content')
    <div class="toolbar">
        <h1>Бронювання #{{ $booking->id }}</h1>
        <div class="actions">
            @can('update', $booking)
                <a href="{{ route('bookings.edit', $booking) }}" class="btn">Редагувати</a>
            @endcan
            @can('delete', $booking)
                @include('partials.delete', ['action' => route('bookings.destroy', $booking), 'confirm' => 'Видалити бронювання разом із платежами?', 'size' => ''])
            @endcan
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    @php $paid = $booking->payments->where('status', 'paid')->sum('amount'); @endphp

    <div class="card">
        <dl class="details">
            <dt>Гість</dt>
            <dd><a href="{{ route('guests.show', $booking->guest) }}">{{ $booking->guest->full_name }}</a> ({{ $booking->guest->email }})</dd>
            <dt>Кімната / ліжко</dt>
            <dd><a href="{{ route('rooms.show', $booking->bed->room) }}">{{ $booking->bed->room->number }}</a> / <a href="{{ route('beds.show', $booking->bed) }}">{{ $booking->bed->label }}</a></dd>
            <dt>Заїзд</dt>
            <dd>{{ $booking->check_in->format('d.m.Y') }}</dd>
            <dt>Виїзд</dt>
            <dd>{{ $booking->check_out->format('d.m.Y') }} ({{ $booking->check_in->diffInDays($booking->check_out) }} ноч.)</dd>
            <dt>Статус</dt>
            <dd><span class="badge">{{ $booking->statusLabel() }}</span></dd>
            <dt>Загальна вартість</dt>
            <dd>{{ number_format((float) $booking->total_price, 2, '.', ' ') }} грн</dd>
            <dt>Сплачено</dt>
            <dd>{{ number_format((float) $paid, 2, '.', ' ') }} грн</dd>
            <dt>Примітки</dt>
            <dd>{{ $booking->notes ?: '—' }}</dd>
        </dl>
    </div>

    <div class="toolbar">
        <h2>Платежі ({{ $booking->payments->count() }})</h2>
        @can('create', App\Models\Payment::class)
            <a href="{{ route('payments.create') }}" class="btn btn-secondary btn-sm">Додати платіж</a>
        @endcan
    </div>
    <div class="card">
        @if ($booking->payments->isEmpty())
            <p class="muted">Платежів ще немає.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Сума</th>
                    <th>Спосіб</th>
                    <th>Статус</th>
                    <th>Дата оплати</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($booking->payments as $payment)
                    <tr>
                        <td><a href="{{ route('payments.show', $payment) }}">#{{ $payment->id }}</a></td>
                        <td>{{ number_format((float) $payment->amount, 2, '.', ' ') }} грн</td>
                        <td>{{ $payment->methodLabel() }}</td>
                        <td><span class="badge">{{ $payment->statusLabel() }}</span></td>
                        <td>{{ $payment->paid_at?->format('d.m.Y H:i') ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
