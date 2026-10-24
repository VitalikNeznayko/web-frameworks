@extends('layouts.app')

@section('title', 'Новий користувач')

@section('content')
    <h1>Новий користувач</h1>

    <div class="card">
        <form action="{{ route('users.store') }}" method="POST">
            @include('users._form')
        </form>
    </div>
@endsection
