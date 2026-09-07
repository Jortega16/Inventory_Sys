<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleOrderController;
use App\Http\Controllers\Api\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API routes — viven en el dominio de cada tenant (empresa.localhost/api/...)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::get('/stock-levels', [StockController::class, 'index']);

    Route::get('/sales', [SaleOrderController::class, 'index']);
    Route::post('/sales', [SaleOrderController::class, 'store']);
    Route::get('/sales/{saleOrder}', [SaleOrderController::class, 'show']);
    Route::post('/sales/{saleOrder}/confirm', [SaleOrderController::class, 'confirm']);
});
