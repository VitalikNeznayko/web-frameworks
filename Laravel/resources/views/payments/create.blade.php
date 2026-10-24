@extends('layouts.app')

@section('title', 'Новий платіж')

@section('content')
    <h1>Новий платіж</h1>

    <div class="card">
        @if ($bookings->isEmpty())
            <p class="muted">Спочатку <a href="{{ route('bookings.create') }}">створіть бронювання</a>.</p>
        @else
            <form action="{{ route('payments.store') }}" method="POST">
                @include('payments._form')
            </form>
        @endif
    </div>
@endsection
