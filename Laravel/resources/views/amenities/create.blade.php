@extends('layouts.app')

@section('title', 'Нова зручність')

@section('content')
    <h1>Нова зручність</h1>

    <div class="card">
        <form action="{{ route('amenities.store') }}" method="POST">
            @include('amenities._form')
        </form>
    </div>
@endsection
