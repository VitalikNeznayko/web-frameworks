@extends('layouts.app')

@section('title', 'Користувачі')

@section('content')
    <div class="toolbar">
        <h1>Користувачі</h1>
        @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn">Додати користувача</a>
        @endcan
    </div>

    @include('partials.filters', ['filter' => $filter, 'action' => route('users.index')])

    <div class="card">
        @if ($users->isEmpty())
            @include('partials.empty', ['paginator' => $users, 'filter' => $filter, 'message' => 'Користувачів ще немає.'])
        @else
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ім'я</th>
                    <th>Email</th>
                    <th>Роль</th>
                    <th>Зареєстровано</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge role-badge role-{{ $user->role }}">{{ $user->roleLabel() }}</span></td>
                        <td>{{ $user->created_at?->format('d.m.Y') ?? '—' }}</td>
                        <td class="actions">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm">Переглянути</a>
                            @can('update', $user)
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">Редагувати</a>
                            @endcan
                            @can('delete', $user)
                                @include('partials.delete', ['action' => route('users.destroy', $user), 'confirm' => 'Видалити користувача?'])
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        @include('partials.pagination', ['paginator' => $users])
    </div>
@endsection
