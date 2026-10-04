<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', [TestController::class, 'test']);

// 7 CRUD-маршрутів: index, create, store, show, edit, update, destroy
Route::resource('products', ProductController::class);