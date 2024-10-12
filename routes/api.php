<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TripController;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('users')->group(function (){
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (){
        Route::get('/trips/filter', [TripController::class, 'filter']);

        Route::get('/bookings', [BookingController::class, 'getOwnBookings']);
        Route::post('/trips/{trip}/booking', [BookingController::class, 'create']);
        Route::put('/bookings/{booking}', [BookingController::class, 'update']);
        Route::delete('/bookings/{booking}', [BookingController::class, 'delete']);
    });
});

Route::prefix('admins')->middleware(['auth:sanctum', 'role:admin'])->group(function (){
    Route::post('/trips', [TripController::class, 'create']);
    Route::put('/trips/{trip}', [TripController::class, 'update']);
    Route::delete('/trips/{trip}', [TripController::class, 'delete']);
});

Route::get('/test', function (){
    //for testing apis
});
