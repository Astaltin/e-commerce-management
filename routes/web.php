<?php

use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFlavorController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'pages.index')->name('home');
Route::view('/about', 'pages.about');

Route::get('/contact', [ContactController::class, 'create']);
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/menu', [ProductController::class, 'index']);
Route::get('/results', [ProductController::class, 'search']);

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [SessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('/overview', [SessionController::class, 'index'])->middleware('auth');
Route::get('/profile', [SessionController::class, 'edit'])->middleware('auth');
Route::patch('/profile', [SessionController::class, 'update'])->middleware('auth');

Route::get('/cart', [CartItemController::class, 'index'])->middleware('auth')->middleware('notadmin');
Route::post('/cart', [CartItemController::class, 'store'])->middleware('auth')->middleware('notadmin');
Route::patch('/cart/{product:slug}/{flavor:slug}/', [CartItemController::class, 'updateFlavor'])->middleware('auth')->middleware('notadmin');
Route::patch('/cart/{product:slug}/{flavor:slug}/quantity', [CartItemController::class, 'updateQuantity'])->middleware('auth')->middleware('notadmin');
Route::patch('/cart/{product:slug}/{flavor:slug}/add', [CartItemController::class, 'incrementQuantity'])->middleware('auth')->middleware('notadmin');
Route::patch('/cart/{product:slug}/{flavor:slug}/subtract', [CartItemController::class, 'decrementQuantity'])->middleware('auth')->middleware('notadmin');
Route::delete('/cart/{product:slug}/{flavor:slug}', [CartItemController::class, 'destroy'])->middleware('auth')->middleware('notadmin');

Route::post('/orders', [OrderController::class, 'store'])->middleware('auth');
Route::get('/orders/{order:slug}', [OrderController::class, 'show'])->middleware('auth');
Route::patch('/orders/{order:slug}', [OrderController::class, 'update'])->middleware('auth');

Route::get('/admin', [AdminController::class, 'index'])->middleware('admin');

Route::get('/admin/flavors', [AdminFlavorController::class, 'index'])->middleware('admin');
Route::get('/admin/flavors/create', [AdminFlavorController::class, 'create'])->middleware('admin');
Route::post('/admin/flavors', [AdminFlavorController::class, 'store'])->middleware('admin');
Route::get('/admin/flavors/{flavor:slug}/edit', [AdminFlavorController::class, 'edit'])->middleware('admin');
Route::patch('/admin/flavors/{flavor:slug}', [AdminFlavorController::class, 'update'])->middleware('admin');
Route::delete('/admin/flavors/{flavor:slug}', [AdminFlavorController::class, 'destroy'])->middleware('admin');

Route::get('/admin/products', [AdminProductController::class, 'index'])->middleware('admin');
Route::get('/admin/products/create', [AdminProductController::class, 'create'])->middleware('admin');
Route::post('/admin/products', [AdminProductController::class, 'store'])->middleware('admin');
Route::get('/admin/products/{product:slug}/edit', [AdminProductController::class, 'edit'])->middleware('admin');
Route::patch('/admin/products/{product:slug}', [AdminProductController::class, 'update'])->middleware('admin');
Route::delete('/admin/products/{product:slug}', [AdminProductController::class, 'destroy'])->middleware('admin');

Route::get('/admin/orders', [AdminOrderController::class, 'index'])->middleware('admin');
Route::patch('/admin/orders/{order:slug}', [AdminOrderController::class, 'update'])->middleware('admin');
Route::get('/admin/orders/{order:slug}', [AdminOrderController::class, 'show'])->middleware('admin');

Route::get('/admin/contacts/', [AdminContactController::class, 'index'])->middleware('admin');
