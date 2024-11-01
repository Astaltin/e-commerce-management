<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::apiResource('/products', ProductsController::class)
    ->only('index');
Route::apiResource('/products', ProductController::class)
    ->except(['index', 'show']);

require __DIR__ . '/auth.php';

Route::fallback(function () {
    return response(null, 403);
});
