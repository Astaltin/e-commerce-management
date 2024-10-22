<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::post('/products', [ProductsController::class, 'create'])->name('products.store');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');

require __DIR__ . '/auth.php';
