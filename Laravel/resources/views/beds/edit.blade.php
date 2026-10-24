@extends('layouts.app')

@section('title', 'Редагування ліжка')

@section('content')
    <h1>Редагування ліжка {{ $bed->label }}</h1>

    <div class="card">
        <form action="{{ route('beds.update', $bed) }}" method="POST">
            @method('PUT')
            @include('beds._form')
        </form>
    </div>
@endsection
