@extends('layouts.app')

@section('title', 'Доступ заборонено')

@section('content')
    <div class="card error-page">
        <p class="code">403</p>
        <h1>Доступ заборонено</h1>
        <p class="muted">У вас недостатньо прав для перегляду цієї сторінки або виконання цієї дії.</p>
        <div class="form-actions">
            <a href="{{ route('home') }}" class="btn">На головну</a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
@endsection
