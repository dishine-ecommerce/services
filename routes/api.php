<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProductVariantImageController;
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
    // Product Variants by product_id
    Route::get('{slug}/variants', [ProductVariantController::class, 'byProduct']);
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('{slug}', [ProductController::class, 'update']);
        Route::delete('{slug}', [ProductController::class, 'destroy']);
        Route::post('{slug}/images', [ProductImageController::class, 'addImages']);
        Route::delete('{slug}/images/{id}', [ProductImageController::class, 'destroy']);
    });
});

// Product Variant Routes
Route::prefix('product-variants')->group(function () {
    Route::get('/', [ProductVariantController::class, 'index']);
    Route::get('{id}', [ProductVariantController::class, 'show']);
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/', [ProductVariantController::class, 'store']);
        Route::put('{id}', [ProductVariantController::class, 'update']);
        Route::delete('{id}', [ProductVariantController::class, 'destroy']);
        Route::post('{id}/images', [ProductVariantImageController::class, 'addImages']);
    });
});

// Variant Images delete
Route::delete('product-variant-images/{id}', [ProductVariantImageController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);