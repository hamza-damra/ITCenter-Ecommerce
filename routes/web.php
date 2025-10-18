<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;

// Language Routes
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales', ['en', 'ar', 'he']))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Favorites Routes
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::get('/favorites/ids', [FavoriteController::class, 'getIds'])->name('favorites.ids');
Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/check/{product}', [CartController::class, 'check'])->name('cart.check');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
Route::get('/cart/products', [CartController::class, 'getProductIds'])->name('cart.products');


// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Brands
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);
});
