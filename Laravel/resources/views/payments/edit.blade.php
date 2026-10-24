@extends('layouts.app')

@section('title', 'Редагування платежу')

@section('content')
    <h1>Редагування платежу #{{ $payment->id }}</h1>

    <div class="card">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @method('PUT')
            @include('payments._form')
        </form>
    </div>
@endsection
