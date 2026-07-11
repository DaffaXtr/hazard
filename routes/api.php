<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreTestController;
use App\Http\Controllers\SimulationController;

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/stats', [SimulationController::class, 'stats']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', [AuthController::class, 'me']); // Alias for frontend compatibility

    // Pre-test endpoints (only store & index)
    Route::apiResource('/pre-tests', PreTestController::class)->only(['index', 'store']);

    // Simulation endpoints (only store & index)
    Route::apiResource('/simulations', SimulationController::class)->only(['index', 'store']);
});
