<?php

namespace App\Http\Controllers;

use App\Filters\UserFilter;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return self::resourceAuthorization(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $filter = new UserFilter($request->query());

        $users = $this->paginate(
            User::filter($filter)->orderBy('name')->orderBy('id'),
            $request,
        );

        return view('users.index', compact('users', 'filter'));
    }

    public function create(): View
    {
        return view('users.create', ['user' => new User()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        return redirect()->route('users.show', $user)->with('success', 'Користувача створено.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.show', $user)->with('success', 'Дані користувача оновлено.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Користувача видалено.');
    }
}
