<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1 Routes
Route::prefix('v1')->group(function () {
    
    // Authentication Routes (Public)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        
        // Protected Authentication Routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/logout-all', [AuthController::class, 'logoutAll']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::get('/sessions', [AuthController::class, 'sessions']);
            Route::delete('/sessions/{tokenId}', [AuthController::class, 'revokeSession']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
        });
    });
    
    // Home
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/home/{section}', [HomeController::class, 'section']);
    
    // Brands
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{slug}', [BrandController::class, 'show']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    
    // Offers
    Route::get('/offers', [OfferController::class, 'index']);
    Route::get('/offers/{slug}', [OfferController::class, 'show']);
    
    // Reviews
    Route::get('/products/{slug}/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews/{review}/helpful', [ReviewController::class, 'markHelpful']);
    Route::post('/reviews/{review}/unhelpful', [ReviewController::class, 'markUnhelpful']);
    
    // Public routes with optional auth
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/{product}', [FavoriteController::class, 'toggle']);
    Route::get('/favorites/check/{product}', [FavoriteController::class, 'check']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'add']);
    Route::put('/cart/{product}', [CartController::class, 'update']);
    Route::delete('/cart/{product}', [CartController::class, 'remove']);
    Route::get('/cart/check/{product}', [CartController::class, 'check']);
    
    // Protected Routes (Require Authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // User Profile
        Route::get('/user/profile', [UserController::class, 'profile']);
        Route::put('/user/profile', [UserController::class, 'updateProfile']);
        Route::post('/user/change-password', [UserController::class, 'changePassword']);
        Route::get('/user/reviews', [UserController::class, 'reviews']);
        Route::get('/user/stats', [UserController::class, 'stats']);
        
        // Reviews (Authenticated)
        Route::post('/products/{slug}/reviews', [ReviewController::class, 'store']);
        Route::put('/reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
        
        // Admin Routes (Require Admin Authentication)
        Route::prefix('admin')->group(function () {
            // Dashboard
            Route::get('/dashboard/stats', [App\Http\Controllers\Api\Admin\DashboardController::class, 'stats']);
            
            // Products Management
            Route::apiResource('products', App\Http\Controllers\Api\Admin\ProductController::class);
            Route::post('/products/{product}/toggle-status', [App\Http\Controllers\Api\Admin\ProductController::class, 'toggleStatus']);
            Route::post('/products/{product}/toggle-featured', [App\Http\Controllers\Api\Admin\ProductController::class, 'toggleFeatured']);
            
            // Categories Management
            Route::apiResource('categories', App\Http\Controllers\Api\Admin\CategoryController::class);
            Route::post('/categories/{category}/toggle-status', [App\Http\Controllers\Api\Admin\CategoryController::class, 'toggleStatus']);
            
            // Brands Management
            Route::apiResource('brands', App\Http\Controllers\Api\Admin\BrandController::class);
            Route::post('/brands/{brand}/toggle-status', [App\Http\Controllers\Api\Admin\BrandController::class, 'toggleStatus']);
            Route::post('/brands/{brand}/toggle-featured', [App\Http\Controllers\Api\Admin\BrandController::class, 'toggleFeatured']);
        });
    });
});
