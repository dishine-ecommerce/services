<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('verify', [VerificationController::class, 'verify'])->name('auth.verify');
    ROute::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// User Routes
Route::middleware('auth:sanctum')->group(function() {
    Route::prefix('user')->group(function() {
        Route::apiResource('address', UserAddressController::class);
    });
});

// Product Routes
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{slug}', [ProductController::class, 'update']);
    Route::delete('products/{slug}', [ProductController::class, 'destroy']);
});