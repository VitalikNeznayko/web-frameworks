@extends('layouts.app')

@section('title', 'Редагування кімнати')

@section('content')
    <h1>Редагування кімнати {{ $room->number }}</h1>

    <div class="card">
        <form action="{{ route('rooms.update', $room) }}" method="POST">
            @method('PUT')
            @include('rooms._form')
        </form>
    </div>
@endsection
