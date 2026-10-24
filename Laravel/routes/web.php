<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Models\Amenity;
use App\Models\Bed;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:login');
});

Route::get('/test', [TestController::class, 'test']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function (Request $request) {
        $models = [
            'rooms' => Room::class,
            'amenities' => Amenity::class,
            'beds' => Bed::class,
            'guests' => Guest::class,
            'bookings' => Booking::class,
            'payments' => Payment::class,
            'users' => User::class,
        ];

        $counts = [];

        foreach ($models as $key => $model) {
            if ($request->user()->can('viewAny', $model)) {
                $counts[$key] = $model::count();
            }
        }

        return view('home', ['counts' => $counts]);
    })->name('home');

    Route::resource('rooms', RoomController::class);
    Route::resource('amenities', AmenityController::class);
    Route::resource('beds', BedController::class);
    Route::resource('guests', GuestController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('users', UserController::class);
});
