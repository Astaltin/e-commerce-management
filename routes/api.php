<?php

use App\Http\Controllers\Api\AdminContactController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AdminFlavorController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\AdminProductController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [ProductController::class, 'index']);
Route::get('/about', function() { return response()->json(['message' => 'About Page']); });

Route::get('/contact', [ContactController::class, 'create']);
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/menu', [ProductController::class, 'index']);
Route::get('/results', [ProductController::class, 'search']);

// Authentication Routes
Route::group([], function () { 
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [SessionController::class, 'store']);
});

// User Routes
Route::group([], function () { 
    Route::post('/logout', [SessionController::class, 'destroy']);
    Route::get('/overview', [SessionController::class, 'index']);
    Route::get('/profile', [SessionController::class, 'edit']);
    Route::patch('/profile', [SessionController::class, 'update']);

    // Cart Routes
    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartItemController::class, 'index']);
        Route::post('/', [CartItemController::class, 'store']);
        Route::patch('{product:slug}/{flavor:slug}/', [CartItemController::class, 'updateFlavor']);
        Route::patch('{product:slug}/{flavor:slug}/quantity', [CartItemController::class, 'updateQuantity']);
        Route::patch('{product:slug}/{flavor:slug}/add', [CartItemController::class, 'incrementQuantity']);
        Route::patch('{product:slug}/{flavor:slug}/subtract', [CartItemController::class, 'decrementQuantity']);
        Route::delete('{product:slug}/{flavor:slug}', [CartItemController::class, 'destroy']);
    });

    // Order Routes
    Route::prefix('/orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('{order:slug}', [OrderController::class, 'show']);
        Route::patch('{order:slug}', [OrderController::class, 'update']);
    });
});

// Admin Routes
Route::group([], function () { 
    Route::get('/admin', [AdminController::class, 'index']);

    // Flavor Routes
    Route::prefix('/admin/flavors')->group(function () {
        Route::get('/', [AdminFlavorController::class, 'index']);
        Route::post('/', [AdminFlavorController::class, 'store']);
        Route::get('{flavor:slug}', [AdminFlavorController::class, 'edit']);
        Route::patch('{flavor:slug}', [AdminFlavorController::class, 'update']);
        Route::delete('{flavor:slug}', [AdminFlavorController::class, 'destroy']);
    });

    // Product Routes
    Route::prefix('/admin/products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index']);
        Route::post('/', [AdminProductController::class, 'store']);
        Route::get('{product:slug}/edit', [AdminProductController::class, 'edit']);
        Route::patch('{product:slug}', [AdminProductController::class, 'update']);
        Route::delete('{product:slug}', [AdminProductController::class, 'destroy']);
    });

    // Order Routes
    Route::prefix('/admin/orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index']);
        Route::get('{order:slug}', [AdminOrderController::class, 'show']);
        Route::patch('{order:slug}', [AdminOrderController::class, 'update']);
    });

    // Contact Routes
    Route::get('/admin/contacts', [AdminContactController::class, 'index']);
});
