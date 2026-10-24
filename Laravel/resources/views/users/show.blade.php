@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="toolbar">
        <h1>{{ $user->name }}</h1>
        <div class="actions">
            @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn">Редагувати</a>
            @endcan
            @can('delete', $user)
                @include('partials.delete', ['action' => route('users.destroy', $user), 'confirm' => 'Видалити користувача?', 'size' => ''])
            @endcan
            <a href="{{ route('users.index') }}" class="btn btn-secondary">До списку</a>
        </div>
    </div>

    <div class="card">
        <dl class="details">
            <dt>ID</dt>
            <dd>{{ $user->id }}</dd>
            <dt>Email</dt>
            <dd>{{ $user->email }}</dd>
            <dt>Роль</dt>
            <dd><span class="badge role-badge role-{{ $user->role }}">{{ $user->roleLabel() }}</span></dd>
            <dt>Зареєстровано</dt>
            <dd>{{ $user->created_at?->format('d.m.Y H:i') ?? '—' }}</dd>
            <dt>Оновлено</dt>
            <dd>{{ $user->updated_at?->format('d.m.Y H:i') ?? '—' }}</dd>
        </dl>
    </div>
@endsection
