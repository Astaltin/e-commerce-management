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

// API Routes with version prefix
Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [SessionController::class, 'store']);
    Route::post('/contact', [ContactController::class, 'store']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [SessionController::class, 'destroy']);
        Route::get('/overview', [SessionController::class, 'index']);
        Route::get('/profile', [SessionController::class, 'edit']);
        Route::patch('/profile', [SessionController::class, 'update']);

        // Cart routes
        Route::get('/cart', [CartItemController::class, 'index'])->middleware('notadmin');
        Route::post('/cart', [CartItemController::class, 'store'])->middleware('notadmin');
        Route::patch('/cart/{product:slug}/{flavor:slug}/flavor', [CartItemController::class, 'updateFlavor'])->middleware('notadmin');
        Route::patch('/cart/{product:slug}/{flavor:slug}/quantity', [CartItemController::class, 'updateQuantity'])->middleware('notadmin');
        Route::patch('/cart/{product:slug}/{flavor:slug}/add', [CartItemController::class, 'incrementQuantity'])->middleware('notadmin');
        Route::patch('/cart/{product:slug}/{flavor:slug}/subtract', [CartItemController::class, 'decrementQuantity'])->middleware('notadmin');
        Route::delete('/cart/{product:slug}/{flavor:slug}', [CartItemController::class, 'destroy'])->middleware('notadmin');

        // Order routes
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order:slug}', [OrderController::class, 'show']);
        Route::patch('/orders/{order:slug}', [OrderController::class, 'update']);
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index']);

        // Admin flavors
        Route::get('/admin/flavors', [AdminFlavorController::class, 'index']);
        Route::post('/admin/flavors', [AdminFlavorController::class, 'store']);
        Route::patch('/admin/flavors/{flavor:slug}', [AdminFlavorController::class, 'update']);
        Route::delete('/admin/flavors/{flavor:slug}', [AdminFlavorController::class, 'destroy']);

        // Admin products
        Route::get('/admin/products', [AdminProductController::class, 'index']);
        Route::post('/admin/products', [AdminProductController::class, 'store']);
        Route::patch('/admin/products/{product:slug}', [AdminProductController::class, 'update']);
        Route::delete('/admin/products/{product:slug}', [AdminProductController::class, 'destroy']);

        // Admin orders
        Route::get('/admin/orders', [AdminOrderController::class, 'index']);
        Route::patch('/admin/orders/{order:slug}', [AdminOrderController::class, 'update']);
        Route::get('/admin/orders/{order:slug}', [AdminOrderController::class, 'show']);

        // Admin contacts
        Route::get('/admin/contacts', [AdminContactController::class, 'index']);
    });
});
