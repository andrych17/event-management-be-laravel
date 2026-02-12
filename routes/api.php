<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Auth\AuthController;

// Public routes (no authentication required)
Route::get('/events/today', [EventController::class, 'today']);
Route::get('/events/floor-availability', [EventController::class, 'floorAvailability']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::get('/promos', [PromoController::class, 'index']);

// Auth routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Config Management (admin only)
    Route::get('/configs', [ConfigController::class, 'index']);
    Route::get('/configs/active', [ConfigController::class, 'active']);
    Route::post('/configs', [ConfigController::class, 'store']);
    Route::get('/configs/{config}', [ConfigController::class, 'show']);
    Route::put('/configs/{config}', [ConfigController::class, 'update']);
    Route::delete('/configs/{config}', [ConfigController::class, 'destroy']);

    // Event CRUD (admin only)
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);
});
