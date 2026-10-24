@extends('layouts.app')

@section('title', 'Платежі')

@section('content')
    <div class="toolbar">
        <h1>Платежі</h1>
        <a href="{{ route('payments.create') }}" class="btn">Додати платіж</a>
    </div>

    <div class="card">
        @if ($payments->isEmpty())
            <p class="muted">Платежів ще немає.</p>
        @else
            <table>
                <thead>
                <tr>
                    <th>№</th>
                    <th>Бронювання</th>
                    <th>Гість</th>
                    <th>Сума</th>
                    <th>Спосіб</th>
                    <th>Статус</th>
                    <th>Дата оплати</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td><a href="{{ route('payments.show', $payment) }}">#{{ $payment->id }}</a></td>
                        <td><a href="{{ route('bookings.show', $payment->booking) }}">#{{ $payment->booking->id }}</a></td>
                        <td>{{ $payment->booking->guest->full_name }}</td>
                        <td>{{ number_format((float) $payment->amount, 2, '.', ' ') }} грн</td>
                        <td>{{ $payment->methodLabel() }}</td>
                        <td><span class="badge">{{ $payment->statusLabel() }}</span></td>
                        <td>{{ $payment->paid_at?->format('d.m.Y H:i') ?? '—' }}</td>
                        <td class="actions">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @include('partials.delete', ['action' => route('payments.destroy', $payment), 'confirm' => 'Видалити платіж?'])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $payments->links('pagination::default') }}
        @endif
    </div>
@endsection
