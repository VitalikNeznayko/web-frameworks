@extends('layouts.app')

@section('title', 'Редагування гостя')

@section('content')
    <h1>Редагування гостя {{ $guest->full_name }}</h1>

    <div class="card">
        <form action="{{ route('guests.update', $guest) }}" method="POST">
            @method('PUT')
            @include('guests._form')
        </form>
    </div>
@endsection
