<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('verify', [VerificationController::class, 'verify'])->name('auth.verify');
    ROute::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// === Route: User
Route::prefix('user')->middleware('auth:sanctum')->group(function() {
    // == Route: User Address
    Route::apiResource('address', UserAddressController::class);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('products', [ProductController::class, 'index']);
});