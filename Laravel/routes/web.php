<?php

use App\Http\Controllers\AmenityController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TestController;
use App\Models\Amenity;
use App\Models\Bed;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'counts' => [
            'rooms' => Room::count(),
            'amenities' => Amenity::count(),
            'beds' => Bed::count(),
            'guests' => Guest::count(),
            'bookings' => Booking::count(),
            'payments' => Payment::count(),
        ],
    ]);
})->name('home');

Route::get('/test', [TestController::class, 'test']);

Route::resource('rooms', RoomController::class);
Route::resource('amenities', AmenityController::class);
Route::resource('beds', BedController::class);
Route::resource('guests', GuestController::class);
Route::resource('bookings', BookingController::class);
Route::resource('payments', PaymentController::class);
