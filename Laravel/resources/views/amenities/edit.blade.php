@extends('layouts.app')

@section('title', 'Редагування зручності')

@section('content')
    <h1>Редагування зручності «{{ $amenity->name }}»</h1>

    <div class="card">
        <form action="{{ route('amenities.update', $amenity) }}" method="POST">
            @method('PUT')
            @include('amenities._form')
        </form>
    </div>
@endsection
