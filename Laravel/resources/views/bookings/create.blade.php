@extends('layouts.app')

@section('title', 'Нове бронювання')

@section('content')
    <h1>Нове бронювання</h1>

    <div class="card">
        @if ($guests->isEmpty() || $beds->isEmpty())
            <p class="muted">Для бронювання потрібні хоча б один <a href="{{ route('guests.create') }}">гість</a> і одне <a href="{{ route('beds.create') }}">ліжко</a>.</p>
        @else
            <form action="{{ route('bookings.store') }}" method="POST">
                @include('bookings._form')
            </form>
        @endif
    </div>
@endsection
