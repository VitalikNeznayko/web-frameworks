@extends('layouts.app')

@section('title', 'Нова кімната')

@section('content')
    <h1>Нова кімната</h1>

    <div class="card">
        <form action="{{ route('rooms.store') }}" method="POST">
            @include('rooms._form')
        </form>
    </div>
@endsection
