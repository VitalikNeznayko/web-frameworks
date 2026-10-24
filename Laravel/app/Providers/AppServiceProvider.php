<?php

namespace App\Providers;

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use PHPOpenSourceSaver\JWTAuth\Http\Parser\AuthHeaders;
use PHPOpenSourceSaver\JWTAuth\Http\Parser\Cookies;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->app['tymon.jwt.parser']->setChain([
            new AuthHeaders(),
            (new Cookies(false))->setKey(AuthController::TOKEN_COOKIE),
        ]);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by(mb_strtolower((string) $request->input('email')).'|'.$request->ip());
        });
    }
}
