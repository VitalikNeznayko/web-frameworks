@extends('layouts.app')

@section('title', 'Головна')

@section('content')
    <h1>Система управління хостелом</h1>
    <p class="muted">Оберіть розділ для перегляду та редагування даних.</p>

    @php
        $sections = [
            'rooms' => 'Кімнати',
            'amenities' => 'Зручності',
            'beds' => 'Ліжка',
            'guests' => 'Гості',
            'bookings' => 'Бронювання',
            'payments' => 'Платежі',
        ];
    @endphp

    <div class="stats">
        @foreach ($sections as $key => $label)
            <a class="stat" href="{{ route($key.'.index') }}">
                <div class="num">{{ $counts[$key] }}</div>
                <div class="lbl">{{ $label }}</div>
            </a>
        @endforeach
    </div>
@endsection
