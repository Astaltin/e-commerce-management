<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/products', [ProductsController::class, 'create'])->name('products.create');
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
Route::put('/products/{id}', [ProductsController::class, 'update'])->name('products.update');

Route::get('/inventory', [ProductsController::class, 'index'])->name('inventory');

require __DIR__ . '/auth.php';
