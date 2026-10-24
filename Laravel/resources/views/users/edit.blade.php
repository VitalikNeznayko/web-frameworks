@extends('layouts.app')

@section('title', 'Редагування користувача')

@section('content')
    <h1>Редагування користувача {{ $user->name }}</h1>

    <div class="card">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @method('PUT')
            @include('users._form')
        </form>
    </div>
@endsection
