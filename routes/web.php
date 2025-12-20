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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;

// Language Routes
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, config('app.available_locales', ['en', 'ar', 'he']))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Banner Image Route (for serving database-stored images)
Route::get('/banner-image/{banner}', [App\Http\Controllers\BannerImageController::class, 'show'])->name('banner.image');

// Promotional Ad Image Route (for serving database-stored images)
Route::get('/promotional-ad-image/{promotionalAd}', [App\Http\Controllers\PromotionalAdImageController::class, 'show'])->name('promotional-ad.image');

Route::get('/test-home', [HomeController::class, 'index'])->name('test.home');
Route::get('/clear-cache', [HomeController::class, 'clearHomeCache'])->name('clear.cache');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{parentSlug}/{childSlug?}/{subChildSlug?}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.detail');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (OTP-based)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestReset'])->name('password.request.post');
Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.post');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.post');

// Favorites Routes
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::get('/favorites/count', [FavoriteController::class, 'getCount'])->name('favorites.count');
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

// Checkout Routes (Protected - Must be authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'processOrder'])->name('checkout.process');
});

// Order Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
});

// Profile Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bootstrap Mode Routes (DB-less admin access when database is missing)
// These routes should work even when database is missing, so minimal middleware
Route::prefix('admin/bootstrap')->name('admin.bootstrap.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\BootstrapController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\BootstrapController::class, 'login'])->name('login.post');
    Route::get('/logout', [App\Http\Controllers\Admin\BootstrapController::class, 'logout'])->name('logout');
    Route::post('/logout', [App\Http\Controllers\Admin\BootstrapController::class, 'logout'])->name('logout.post');
    Route::get('/setup', [App\Http\Controllers\Admin\BootstrapController::class, 'setup'])->name('setup');
    Route::get('/status', [App\Http\Controllers\Admin\BootstrapController::class, 'status'])->name('status');
    Route::post('/create-database', [App\Http\Controllers\Admin\BootstrapController::class, 'createDatabase'])->name('create-database');
    Route::post('/import-sql', [App\Http\Controllers\Admin\BootstrapController::class, 'importSql'])->name('import-sql');
    Route::post('/restore-backup', [App\Http\Controllers\Admin\BootstrapController::class, 'restoreBackup'])->name('restore-backup');
    Route::post('/validate-database', [App\Http\Controllers\Admin\BootstrapController::class, 'validateDatabase'])->name('validate-database');
    Route::get('/debug', function() {
        $state = \App\Services\DatabaseStateService::detectState();
        $stateInfo = \App\Services\DatabaseStateService::getStateInfo();
        return response()->json([
            'state' => $state,
            'state_info' => $stateInfo,
            'bootstrap_enabled' => config('bootstrap.enabled', true),
            'session_driver' => config('session.driver'),
            'cache_driver' => config('cache.default'),
        ]);
    })->name('debug');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->middleware('bootstrap.mode')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::delete('/products/delete-all', [App\Http\Controllers\Admin\ProductController::class, 'deleteAll'])->name('products.delete-all');
    Route::delete('/products/bulk-delete', [App\Http\Controllers\Admin\ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::get('/products/category-attributes/{categoryId}', [App\Http\Controllers\Admin\ProductController::class, 'getCategoryAttributes'])->name('products.category-attributes');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Categories
    Route::delete('/categories/delete-all', [App\Http\Controllers\Admin\CategoryController::class, 'deleteAll'])->name('categories.delete-all');
    Route::delete('/categories/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Category Attributes
    Route::get('/categories/{category}/attributes', [App\Http\Controllers\Admin\CategoryAttributeController::class, 'edit'])->name('categories.attributes.edit');
    Route::put('/categories/{category}/attributes', [App\Http\Controllers\Admin\CategoryAttributeController::class, 'update'])->name('categories.attributes.update');

    // Brands
    Route::delete('/brands/delete-all', [App\Http\Controllers\Admin\BrandController::class, 'deleteAll'])->name('brands.delete-all');
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);

    // Attributes
    Route::delete('/attributes/delete-all', [App\Http\Controllers\Admin\AttributeController::class, 'deleteAll'])->name('attributes.delete-all');
    Route::resource('attributes', App\Http\Controllers\Admin\AttributeController::class);

    // Tags
    Route::resource('tags', App\Http\Controllers\Admin\TagController::class);

    // Attribute Values
    Route::resource('attributes.attribute-values', App\Http\Controllers\Admin\AttributeValueController::class)
        ->except(['show']);

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/update-payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    Route::delete('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/bulk-update', [App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update');
    Route::get('/orders/export/csv', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');

    // Contact Messages
    Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{id}/update-status', [App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])->name('contacts.update-status');
    Route::delete('/contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/bulk-update-status', [App\Http\Controllers\Admin\ContactController::class, 'bulkUpdateStatus'])->name('contacts.bulk-update-status');
    Route::post('/contacts/bulk-delete', [App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])->name('contacts.bulk-delete');


        // Reviews
        Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::post('/reviews/delete-all', [App\Http\Controllers\Admin\ReviewController::class, 'deleteAll'])->name('reviews.delete-all');


    // Promotional Offers
    Route::resource('promotional-offers', App\Http\Controllers\Admin\PromotionalOfferController::class);
    Route::post('/promotional-offers/{promotionalOffer}/toggle-active', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'toggleActive'])->name('promotional-offers.toggle-active');

    // Banners
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

    // Promotional Ads
    Route::resource('promotional-ads', App\Http\Controllers\Admin\PromotionalAdController::class);

    // Database Backup Management
    Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/create-with-options', [App\Http\Controllers\Admin\BackupController::class, 'createWithOptions'])->name('backup.create-with-options');
    Route::post('/backup/restore', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
    Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backup.delete');
    Route::post('/backup/cleanup', [App\Http\Controllers\Admin\BackupController::class, 'cleanup'])->name('backup.cleanup');
    Route::post('/backup/cleanup-ajax', [App\Http\Controllers\Admin\BackupController::class, 'cleanupAjax'])->name('backup.cleanup-ajax');
    Route::post('/backup/validate-upload', [App\Http\Controllers\Admin\BackupController::class, 'validateUpload'])->name('backup.validate-upload');
    Route::post('/backup/import-and-restore', [App\Http\Controllers\Admin\BackupController::class, 'importAndRestore'])->name('backup.import-and-restore');
    Route::get('/backup/modules', [App\Http\Controllers\Admin\BackupController::class, 'getModules'])->name('backup.modules');
    Route::post('/backup/purge-all-data', [App\Http\Controllers\Admin\BackupController::class, 'purgeAllData'])->name('backup.purge-all-data');
    Route::post('/backup/clear-frontend-cache', [App\Http\Controllers\Admin\BackupController::class, 'clearFrontendCache'])->name('backup.clear-frontend-cache');

    // Backup Settings
    Route::get('/backup/settings', [App\Http\Controllers\Admin\BackupSettingController::class, 'index'])->name('backup.settings');
    Route::post('/backup/settings', [App\Http\Controllers\Admin\BackupSettingController::class, 'update'])->name('backup.settings.update');
});
