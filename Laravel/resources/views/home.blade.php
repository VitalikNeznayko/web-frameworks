@extends('layouts.app')

@section('title', 'Головна')

@section('content')
    <h1>Система управління хостелом</h1>
    <p class="muted">Вітаємо, {{ auth()->user()->name }}! Ваша роль: {{ auth()->user()->roleLabel() }}. Оберіть розділ для перегляду даних.</p>

    @php
        $sections = [
            'rooms' => 'Кімнати',
            'amenities' => 'Зручності',
            'beds' => 'Ліжка',
            'guests' => 'Гості',
            'bookings' => 'Бронювання',
            'payments' => 'Платежі',
            'users' => 'Користувачі',
        ];
    @endphp

    <div class="stats">
        @foreach ($sections as $key => $label)
            @isset($counts[$key])
                <a class="stat" href="{{ route($key.'.index') }}">
                    <div class="num">{{ $counts[$key] }}</div>
                    <div class="lbl">{{ $label }}</div>
                </a>
            @endisset
        @endforeach
    </div>
@endsection
