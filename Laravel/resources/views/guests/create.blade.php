@extends('layouts.app')

@section('title', 'Новий гість')

@section('content')
    <h1>Новий гість</h1>

    <div class="card">
        <form action="{{ route('guests.store') }}" method="POST">
            @include('guests._form')
        </form>
    </div>
@endsection
