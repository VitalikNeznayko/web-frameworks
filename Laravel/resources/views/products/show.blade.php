@extends('products.layout')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>

    <table border="1" cellpadding="6">
        <tr><th>ID</th><td>{{ $product->id }}</td></tr>
        <tr><th>Назва</th><td>{{ $product->name }}</td></tr>
        <tr><th>Опис</th><td>{{ $product->description }}</td></tr>
        <tr><th>Ціна</th><td>{{ $product->price }} грн</td></tr>
        <tr><th>Створено</th><td>{{ $product->created_at }}</td></tr>
    </table>

    <a href="{{ route('products.edit', $product) }}">Редагувати</a>
    @include('products._delete_form')
    <a href="{{ route('products.index') }}">Назад до списку</a>
@endsection
