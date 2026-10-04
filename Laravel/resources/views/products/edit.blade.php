@extends('products.layout')

@section('title', 'Редагування товару')

@section('content')
    <h1>Редагування: {{ $product->name }}</h1>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        @include('products._form')
        <button type="submit">Оновити</button>
    </form>

    @include('products._delete_form')

    <a href="{{ route('products.index') }}">Назад до списку</a>
@endsection
