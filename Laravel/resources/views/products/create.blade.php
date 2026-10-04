@extends('products.layout')

@section('title', 'Новий товар')

@section('content')
    <h1>Новий товар</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        @include('products._form')
        <button type="submit">Зберегти</button>
    </form>

    <a href="{{ route('products.index') }}">Назад до списку</a>
@endsection
