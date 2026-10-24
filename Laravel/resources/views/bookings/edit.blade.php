@extends('layouts.app')

@section('title', 'Редагування бронювання')

@section('content')
    <h1>Редагування бронювання #{{ $booking->id }}</h1>

    <div class="card">
        <form action="{{ route('bookings.update', $booking) }}" method="POST">
            @method('PUT')
            @include('bookings._form')
        </form>
    </div>
@endsection
