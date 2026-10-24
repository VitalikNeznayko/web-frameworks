@extends('layouts.app')

@section('title', 'Нове ліжко')

@section('content')
    <h1>Нове ліжко</h1>

    <div class="card">
        @if ($rooms->isEmpty())
            <p class="muted">Спочатку <a href="{{ route('rooms.create') }}">створіть кімнату</a>.</p>
        @else
            <form action="{{ route('beds.store') }}" method="POST">
                @include('beds._form')
            </form>
        @endif
    </div>
@endsection
