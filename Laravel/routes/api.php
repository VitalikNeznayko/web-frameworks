<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.refresh');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
});
