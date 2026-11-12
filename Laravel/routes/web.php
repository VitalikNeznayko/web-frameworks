<?php

use App\Http\Controllers\BedController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'test']);

Route::get('/rooms', [RoomController::class, 'getRooms']);
Route::get('/rooms/{id}', [RoomController::class, 'getRoomById']);
Route::post('/rooms', [RoomController::class, 'createRoom']);
Route::patch('/rooms/{id}', [RoomController::class, 'updateRoomById']);
Route::delete('/rooms/{id}', [RoomController::class, 'deleteRoomById']);

Route::get('/beds', [BedController::class, 'getBeds']);
Route::get('/beds/{id}', [BedController::class, 'getBedById']);
Route::post('/beds', [BedController::class, 'createBed']);
Route::patch('/beds/{id}', [BedController::class, 'updateBedById']);
Route::delete('/beds/{id}', [BedController::class, 'deleteBedById']);

Route::get('/guests', [GuestController::class, 'getGuests']);
Route::get('/guests/{id}', [GuestController::class, 'getGuestById']);
Route::post('/guests', [GuestController::class, 'createGuest']);
Route::patch('/guests/{id}', [GuestController::class, 'updateGuestById']);
Route::delete('/guests/{id}', [GuestController::class, 'deleteGuestById']);

Route::get('/bookings', [BookingController::class, 'getBookings']);
Route::get('/bookings/{id}', [BookingController::class, 'getBookingById']);
Route::post('/bookings', [BookingController::class, 'createBooking']);
Route::patch('/bookings/{id}', [BookingController::class, 'updateBookingById']);
Route::delete('/bookings/{id}', [BookingController::class, 'deleteBookingById']);

Route::get('/payments', [PaymentController::class, 'getPayments']);
Route::get('/payments/{id}', [PaymentController::class, 'getPaymentById']);
Route::post('/payments', [PaymentController::class, 'createPayment']);
Route::patch('/payments/{id}', [PaymentController::class, 'updatePaymentById']);
Route::delete('/payments/{id}', [PaymentController::class, 'deletePaymentById']);
