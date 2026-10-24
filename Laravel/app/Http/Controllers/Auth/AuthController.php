<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public const TOKEN_COOKIE = 'token';

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $token = Auth::guard('api')->attempt($request->validated());

        if (! $token) {
            return back()
                ->withErrors(['email' => 'Невірний email або пароль.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->withCookie($this->tokenCookie($request, $token))
            ->with('success', 'Ви увійшли до системи.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            ...$request->safe()->only(['name', 'email', 'password']),
            'role' => User::ROLE_CLIENT,
        ]);

        $token = Auth::guard('api')->login($user);

        $request->session()->regenerate();

        return redirect()->route('home')
            ->withCookie($this->tokenCookie($request, $token))
            ->with('success', 'Реєстрація успішна. Ласкаво просимо!');
    }

    public function logout(Request $request): RedirectResponse
    {
        try {
            Auth::guard('api')->logout();
        } catch (JWTException) {
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withoutCookie(self::TOKEN_COOKIE)
            ->with('success', 'Ви вийшли з системи.');
    }

    private function tokenCookie(Request $request, string $token): Cookie
    {
        return cookie(
            self::TOKEN_COOKIE,
            $token,
            Auth::guard('api')->factory()->getTTL(),
            '/',
            null,
            $request->isSecure(),
            true,
            false,
            Cookie::SAMESITE_LAX,
        );
    }
}
