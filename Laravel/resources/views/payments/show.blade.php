@extends('layouts.app')

@section('title', 'Платіж #'.$payment->id)

@section('content')
    <div class="toolbar">
        <h1>Платіж #{{ $payment->id }}</h1>
        <div class="actions">
            @can('update', $payment)
                <a href="{{ route('payments.edit', $payment) }}" class="btn">Редагувати</a>
            @endcan
            @can('delete', $payment)
                @include('partials.delete', ['action' => route('payments.destroy', $payment), 'confirm' => 'Видалити платіж?', 'size' => ''])
            @endcan
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>Бронювання</dt>
            <dd>
                <a href="{{ route('bookings.show', $payment->booking) }}">#{{ $payment->booking->id }}</a>,
                {{ $payment->booking->check_in->format('d.m.Y') }} – {{ $payment->booking->check_out->format('d.m.Y') }},
                кімната {{ $payment->booking->bed->room->number }}, ліжко {{ $payment->booking->bed->label }}
            </dd>
            <dt>Гість</dt>
            <dd><a href="{{ route('guests.show', $payment->booking->guest) }}">{{ $payment->booking->guest->full_name }}</a></dd>
            <dt>Сума</dt>
            <dd>{{ number_format((float) $payment->amount, 2, '.', ' ') }} грн</dd>
            <dt>Спосіб оплати</dt>
            <dd>{{ $payment->methodLabel() }}</dd>
            <dt>Статус</dt>
            <dd><span class="badge">{{ $payment->statusLabel() }}</span></dd>
            <dt>Дата оплати</dt>
            <dd>{{ $payment->paid_at?->format('d.m.Y H:i') ?? '—' }}</dd>
        </dl>
    </div>
@endsection
