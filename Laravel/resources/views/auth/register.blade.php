@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
    <div class="card auth-card">
        <h1>Реєстрація</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Імʼя</label>
                    <input type="text" id="name" name="name" maxlength="255" value="{{ old('name') }}" class="@error('name') is-invalid @enderror" required autofocus autocomplete="name">
                    @error('name') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="255" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" required autocomplete="username">
                    @error('email') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" minlength="8" class="@error('password') is-invalid @enderror" required autocomplete="new-password">
                    @error('password') <span class="error-text">{{ $message }}</span> @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Підтвердження пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Зареєструватися</button>
                <a href="{{ route('login') }}">Вже маєте обліковий запис? Увійти</a>
            </div>
        </form>
    </div>
@endsection
