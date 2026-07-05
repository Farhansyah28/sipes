<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// V1 API Routes
Route::prefix('v1')->group(function () {
    
    // Santri API
    Route::prefix('santri')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\V1\Santri\AuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [\App\Http\Controllers\Api\V1\Santri\AuthController::class, 'logout']);
            Route::get('profile', [\App\Http\Controllers\Api\V1\Santri\ProfileController::class, 'index']);
        });
    });

    // Pengurus API
    Route::prefix('pengurus')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\V1\Pengurus\AuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [\App\Http\Controllers\Api\V1\Pengurus\AuthController::class, 'logout']);
        });
    });

});

// Payment Webhook
Route::post('/webhooks/payment/{provider}', [\App\Http\Controllers\Api\WebhookController::class, 'payment']);
