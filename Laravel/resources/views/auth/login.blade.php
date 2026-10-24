@extends('layouts.app')

@section('title', 'Вхід')

@section('content')
    <div class="card auth-card">
        <h1>Вхід до системи</h1>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required autofocus autocomplete="username">
                    @error('email') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror" required autocomplete="current-password">
                    @error('password') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Увійти</button>
                <a href="{{ route('register') }}">Немає облікового запису? Зареєструватися</a>
            </div>
        </form>
    </div>
@endsection
