<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'test']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:client')->group(function () {
        Route::get('/rooms', [RoomController::class, 'getRooms']);
        Route::get('/rooms/{id}', [RoomController::class, 'getRoomById']);

        Route::get('/beds', [BedController::class, 'getBeds']);
        Route::get('/beds/{id}', [BedController::class, 'getBedById']);

        Route::post('/bookings', [BookingController::class, 'createBooking']);
    });

    Route::middleware('role:manager')->group(function () {
        Route::post('/rooms', [RoomController::class, 'createRoom']);
        Route::patch('/rooms/{id}', [RoomController::class, 'updateRoomById']);

        Route::post('/beds', [BedController::class, 'createBed']);
        Route::patch('/beds/{id}', [BedController::class, 'updateBedById']);

        Route::get('/guests', [GuestController::class, 'getGuests']);
        Route::get('/guests/{id}', [GuestController::class, 'getGuestById']);
        Route::post('/guests', [GuestController::class, 'createGuest']);
        Route::patch('/guests/{id}', [GuestController::class, 'updateGuestById']);

        Route::get('/bookings', [BookingController::class, 'getBookings']);
        Route::get('/bookings/{id}', [BookingController::class, 'getBookingById']);
        Route::patch('/bookings/{id}', [BookingController::class, 'updateBookingById']);

        Route::get('/payments', [PaymentController::class, 'getPayments']);
        Route::get('/payments/{id}', [PaymentController::class, 'getPaymentById']);
        Route::post('/payments', [PaymentController::class, 'createPayment']);
        Route::patch('/payments/{id}', [PaymentController::class, 'updatePaymentById']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::delete('/rooms/{id}', [RoomController::class, 'deleteRoomById']);
        Route::delete('/beds/{id}', [BedController::class, 'deleteBedById']);
        Route::delete('/guests/{id}', [GuestController::class, 'deleteGuestById']);
        Route::delete('/bookings/{id}', [BookingController::class, 'deleteBookingById']);
        Route::delete('/payments/{id}', [PaymentController::class, 'deletePaymentById']);

        Route::get('/users', [UserController::class, 'getUsers']);
        Route::patch('/users/{id}/role', [UserController::class, 'updateUserRole']);
        Route::delete('/users/{id}', [UserController::class, 'deleteUserById']);
    });
});
