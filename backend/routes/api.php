<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\V2;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::apiResource('productos', ProductoController::class);
        Route::apiResource('categorias', CategoriaController::class);
        Route::apiResource('pedidos', PedidoController::class)->only(['index', 'store', 'show']);
    });
});

Route::prefix('v2')->name('v2.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('productos', V2\ProductoController::class);
    });
});
