<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;

// rutas públicas (catálogo y auth)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// rutas protegidas (requieren token)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/checkout', [CheckoutController::class, 'createSession']);
    Route::post('/checkout/verify', [CheckoutController::class, 'verifySession']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
