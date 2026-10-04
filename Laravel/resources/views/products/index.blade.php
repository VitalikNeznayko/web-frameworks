@extends('products.layout')

@section('title', 'Товари')

@section('content')
    <h1>Товари</h1>

    <a href="{{ route('products.create') }}">Додати товар</a>

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Ціна</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }} грн</td>
                <td>
                    <a href="{{ route('products.show', $product) }}">Переглянути</a>
                    <a href="{{ route('products.edit', $product) }}">Редагувати</a>
                    @include('products._delete_form')
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Записів немає</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
