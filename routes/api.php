<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
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
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{slug}', [ProductController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('{slug}', [ProductController::class, 'update']);
        Route::delete('{slug}', [ProductController::class, 'destroy']);
    });
    Route::post('{slug}/images', [ProductImageController::class, 'addImages']);
    Route::delete('{slug}/images/{id}', [ProductImageController::class, 'destroy']);
});