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
Route::get('/clear-cache', [HomeController::class, 'clearHomeCache'])->middleware('admin')->name('clear.cache');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::get('/category/{parentSlug}/{childSlug?}/{subChildSlug?}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.detail');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (OTP-based) — rate limited to prevent brute force
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'requestReset'])->middleware('throttle:3,1')->name('password.request.post');
Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.verify.form');
Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->middleware('throttle:5,1')->name('password.verify.post');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.reset.post');

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
Route::get('/cart/items', [CartController::class, 'getItems'])->name('cart.items');

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

// Review Routes (Web - Public read)
Route::get('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');

// Review Routes (Protected - Require Authentication)
Route::middleware('auth')->group(function () {
    Route::post('/reviews/{review}/helpful', [App\Http\Controllers\ReviewController::class, 'markHelpful'])->name('reviews.helpful');
    Route::post('/reviews/{review}/unhelpful', [App\Http\Controllers\ReviewController::class, 'markUnhelpful'])->name('reviews.unhelpful');
    Route::post('/products/{product}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
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
    })->middleware('admin')->name('debug');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->middleware('bootstrap.mode')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')->name('dashboard');

    // Products (literal paths before wildcards)
    Route::middleware('permission:products.create')->group(function () {
        Route::get('/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    });
    Route::middleware('permission:products.delete')->group(function () {
        Route::delete('/products/delete-all', [App\Http\Controllers\Admin\ProductController::class, 'deleteAll'])->name('products.delete-all');
        Route::delete('/products/bulk-delete', [App\Http\Controllers\Admin\ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    });
    Route::middleware('permission:products.view')->group(function () {
        Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/category-attributes/{categoryId}', [App\Http\Controllers\Admin\ProductController::class, 'getCategoryAttributes'])->name('products.category-attributes');
        Route::get('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    });
    Route::middleware('permission:products.edit')->group(function () {
        Route::get('/products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    });
    Route::delete('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])
        ->middleware('permission:products.delete')->name('products.destroy');

    // Categories (literal paths before wildcards)
    Route::middleware('permission:categories.create')->group(function () {
        Route::get('/categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    });
    Route::middleware('permission:categories.delete')->group(function () {
        Route::delete('/categories/delete-all', [App\Http\Controllers\Admin\CategoryController::class, 'deleteAll'])->name('categories.delete-all');
        Route::delete('/categories/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    });
    Route::middleware('permission:categories.view')->group(function () {
        Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/attributes', [App\Http\Controllers\Admin\CategoryAttributeController::class, 'edit'])->name('categories.attributes.edit');
    });
    Route::middleware('permission:categories.edit')->group(function () {
        Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::put('/categories/{category}/attributes', [App\Http\Controllers\Admin\CategoryAttributeController::class, 'update'])->name('categories.attributes.update');
    });
    Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])
        ->middleware('permission:categories.delete')->name('categories.destroy');

    // Brands (literal paths before wildcards)
    Route::middleware('permission:brands.create')->group(function () {
        Route::get('/brands/create', [App\Http\Controllers\Admin\BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [App\Http\Controllers\Admin\BrandController::class, 'store'])->name('brands.store');
    });
    Route::delete('/brands/delete-all', [App\Http\Controllers\Admin\BrandController::class, 'deleteAll'])
        ->middleware('permission:brands.delete')->name('brands.delete-all');
    Route::middleware('permission:brands.view')->group(function () {
        Route::get('/brands', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/{brand}', [App\Http\Controllers\Admin\BrandController::class, 'show'])->name('brands.show');
    });
    Route::middleware('permission:brands.edit')->group(function () {
        Route::get('/brands/{brand}/edit', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('brands.update');
    });
    Route::delete('/brands/{brand}', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])
        ->middleware('permission:brands.delete')->name('brands.destroy');

    // Attributes (literal paths before wildcards)
    Route::middleware('permission:attributes.create')->group(function () {
        Route::get('/attributes/create', [App\Http\Controllers\Admin\AttributeController::class, 'create'])->name('attributes.create');
        Route::post('/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'store'])->name('attributes.store');
    });
    Route::delete('/attributes/delete-all', [App\Http\Controllers\Admin\AttributeController::class, 'deleteAll'])
        ->middleware('permission:attributes.delete')->name('attributes.delete-all');
    Route::middleware('permission:attributes.view')->group(function () {
        Route::get('/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'index'])->name('attributes.index');
        Route::get('/attributes/{attribute}', [App\Http\Controllers\Admin\AttributeController::class, 'show'])->name('attributes.show');
        Route::get('/attributes/{attribute}/attribute-values', [App\Http\Controllers\Admin\AttributeValueController::class, 'index'])->name('attributes.attribute-values.index');
    });
    Route::middleware('permission:attributes.edit')->group(function () {
        Route::get('/attributes/{attribute}/edit', [App\Http\Controllers\Admin\AttributeController::class, 'edit'])->name('attributes.edit');
        Route::put('/attributes/{attribute}', [App\Http\Controllers\Admin\AttributeController::class, 'update'])->name('attributes.update');
    });
    Route::middleware('permission:attributes.create')->group(function () {
        Route::get('/attributes/{attribute}/attribute-values/create', [App\Http\Controllers\Admin\AttributeValueController::class, 'create'])->name('attributes.attribute-values.create');
        Route::post('/attributes/{attribute}/attribute-values', [App\Http\Controllers\Admin\AttributeValueController::class, 'store'])->name('attributes.attribute-values.store');
    });
    Route::middleware('permission:attributes.edit')->group(function () {
        Route::get('/attributes/{attribute}/attribute-values/{attribute_value}/edit', [App\Http\Controllers\Admin\AttributeValueController::class, 'edit'])->name('attributes.attribute-values.edit');
        Route::put('/attributes/{attribute}/attribute-values/{attribute_value}', [App\Http\Controllers\Admin\AttributeValueController::class, 'update'])->name('attributes.attribute-values.update');
    });
    Route::middleware('permission:attributes.delete')->group(function () {
        Route::delete('/attributes/{attribute}', [App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('attributes.destroy');
        Route::delete('/attributes/{attribute}/attribute-values/{attribute_value}', [App\Http\Controllers\Admin\AttributeValueController::class, 'destroy'])->name('attributes.attribute-values.destroy');
    });

    // Tags (literal paths before wildcards)
    Route::middleware('permission:tags.create')->group(function () {
        Route::get('/tags/create', [App\Http\Controllers\Admin\TagController::class, 'create'])->name('tags.create');
        Route::post('/tags', [App\Http\Controllers\Admin\TagController::class, 'store'])->name('tags.store');
    });
    Route::middleware('permission:tags.view')->group(function () {
        Route::get('/tags', [App\Http\Controllers\Admin\TagController::class, 'index'])->name('tags.index');
        Route::get('/tags/{tag}', [App\Http\Controllers\Admin\TagController::class, 'show'])->name('tags.show');
    });
    Route::middleware('permission:tags.edit')->group(function () {
        Route::get('/tags/{tag}/edit', [App\Http\Controllers\Admin\TagController::class, 'edit'])->name('tags.edit');
        Route::put('/tags/{tag}', [App\Http\Controllers\Admin\TagController::class, 'update'])->name('tags.update');
    });
    Route::delete('/tags/{tag}', [App\Http\Controllers\Admin\TagController::class, 'destroy'])
        ->middleware('permission:tags.delete')->name('tags.destroy');

    // Specification Templates (literal paths before wildcards)
    Route::middleware('permission:spec_templates.create')->group(function () {
        Route::get('/spec-templates/create', [App\Http\Controllers\Admin\SpecTemplateController::class, 'create'])->name('spec-templates.create');
        Route::post('/spec-templates', [App\Http\Controllers\Admin\SpecTemplateController::class, 'store'])->name('spec-templates.store');
    });
    Route::middleware('permission:spec_templates.view')->group(function () {
        Route::get('/spec-templates', [App\Http\Controllers\Admin\SpecTemplateController::class, 'index'])->name('spec-templates.index');
        Route::get('/spec-templates/category-fields/{categoryId}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'getCategorySpecFields'])->name('spec-templates.category-fields');
        Route::get('/spec-templates/{spec_template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'show'])->name('spec-templates.show');
    });
    Route::middleware('permission:spec_templates.create')->group(function () {
        Route::post('/spec-templates/{template}/fields', [App\Http\Controllers\Admin\SpecTemplateController::class, 'storeField'])->name('spec-templates.fields.store');
    });
    Route::middleware('permission:spec_templates.edit')->group(function () {
        Route::get('/spec-templates/{spec_template}/edit', [App\Http\Controllers\Admin\SpecTemplateController::class, 'edit'])->name('spec-templates.edit');
        Route::put('/spec-templates/{spec_template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'update'])->name('spec-templates.update');
        Route::put('/spec-templates/{template}/fields/{field}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'updateField'])->name('spec-templates.fields.update');
        Route::post('/spec-templates/{template}/reorder-fields', [App\Http\Controllers\Admin\SpecTemplateController::class, 'reorderFields'])->name('spec-templates.reorder-fields');
    });
    Route::middleware('permission:spec_templates.delete')->group(function () {
        Route::delete('/spec-templates/{spec_template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'destroy'])->name('spec-templates.destroy');
        Route::delete('/spec-templates/{template}/fields/{field}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'destroyField'])->name('spec-templates.fields.destroy');
    });

    // Orders (literal paths before wildcards)
    Route::get('/orders/export/csv', [App\Http\Controllers\Admin\OrderController::class, 'export'])
        ->middleware('permission:orders.export')->name('orders.export');
    Route::post('/orders/bulk-update', [App\Http\Controllers\Admin\OrderController::class, 'bulkUpdateStatus'])
        ->middleware('permission:orders.edit')->name('orders.bulk-update');
    Route::middleware('permission:orders.view')->group(function () {
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    });
    Route::middleware('permission:orders.edit')->group(function () {
        Route::post('/orders/{id}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{id}/update-payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    });
    Route::delete('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])
        ->middleware('permission:orders.delete')->name('orders.destroy');

    // Contact Messages (literal paths before wildcards)
    Route::post('/contacts/bulk-update-status', [App\Http\Controllers\Admin\ContactController::class, 'bulkUpdateStatus'])
        ->middleware('permission:contacts.edit')->name('contacts.bulk-update-status');
    Route::post('/contacts/bulk-delete', [App\Http\Controllers\Admin\ContactController::class, 'bulkDelete'])
        ->middleware('permission:contacts.delete')->name('contacts.bulk-delete');
    Route::middleware('permission:contacts.view')->group(function () {
        Route::get('/contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('contacts.show');
    });
    Route::patch('/contacts/{id}/update-status', [App\Http\Controllers\Admin\ContactController::class, 'updateStatus'])
        ->middleware('permission:contacts.edit')->name('contacts.update-status');
    Route::delete('/contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])
        ->middleware('permission:contacts.delete')->name('contacts.destroy');

    // Reviews (literal paths before wildcards)
    Route::post('/reviews/delete-all', [App\Http\Controllers\Admin\ReviewController::class, 'deleteAll'])
        ->middleware('permission:reviews.delete')->name('reviews.delete-all');
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])
        ->middleware('permission:reviews.view')->name('reviews.index');
    Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])
        ->middleware('permission:reviews.delete')->name('reviews.destroy');

    // Promotional Offers (literal paths before wildcards)
    Route::middleware('permission:promotional_offers.create')->group(function () {
        Route::get('/promotional-offers/create', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'create'])->name('promotional-offers.create');
        Route::post('/promotional-offers', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'store'])->name('promotional-offers.store');
    });
    Route::middleware('permission:promotional_offers.view')->group(function () {
        Route::get('/promotional-offers', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'index'])->name('promotional-offers.index');
        Route::get('/promotional-offers/{promotional_offer}', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'show'])->name('promotional-offers.show');
    });
    Route::middleware('permission:promotional_offers.edit')->group(function () {
        Route::get('/promotional-offers/{promotional_offer}/edit', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'edit'])->name('promotional-offers.edit');
        Route::put('/promotional-offers/{promotional_offer}', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'update'])->name('promotional-offers.update');
        Route::post('/promotional-offers/{promotionalOffer}/toggle-active', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'toggleActive'])->name('promotional-offers.toggle-active');
    });
    Route::delete('/promotional-offers/{promotional_offer}', [App\Http\Controllers\Admin\PromotionalOfferController::class, 'destroy'])
        ->middleware('permission:promotional_offers.delete')->name('promotional-offers.destroy');

    // Banners (literal paths before wildcards)
    Route::middleware('permission:banners.create')->group(function () {
        Route::get('/banners/create', [App\Http\Controllers\Admin\BannerController::class, 'create'])->name('banners.create');
        Route::post('/banners', [App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    });
    Route::middleware('permission:banners.view')->group(function () {
        Route::get('/banners', [App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
        Route::get('/banners/{banner}', [App\Http\Controllers\Admin\BannerController::class, 'show'])->name('banners.show');
    });
    Route::middleware('permission:banners.edit')->group(function () {
        Route::get('/banners/{banner}/edit', [App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('banners.edit');
        Route::put('/banners/{banner}', [App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    });
    Route::delete('/banners/{banner}', [App\Http\Controllers\Admin\BannerController::class, 'destroy'])
        ->middleware('permission:banners.delete')->name('banners.destroy');

    // Promotional Ads (literal paths before wildcards)
    Route::middleware('permission:promotional_ads.create')->group(function () {
        Route::get('/promotional-ads/create', [App\Http\Controllers\Admin\PromotionalAdController::class, 'create'])->name('promotional-ads.create');
        Route::post('/promotional-ads', [App\Http\Controllers\Admin\PromotionalAdController::class, 'store'])->name('promotional-ads.store');
    });
    Route::middleware('permission:promotional_ads.view')->group(function () {
        Route::get('/promotional-ads', [App\Http\Controllers\Admin\PromotionalAdController::class, 'index'])->name('promotional-ads.index');
        Route::get('/promotional-ads/{promotional_ad}', [App\Http\Controllers\Admin\PromotionalAdController::class, 'show'])->name('promotional-ads.show');
    });
    Route::middleware('permission:promotional_ads.edit')->group(function () {
        Route::get('/promotional-ads/{promotional_ad}/edit', [App\Http\Controllers\Admin\PromotionalAdController::class, 'edit'])->name('promotional-ads.edit');
        Route::put('/promotional-ads/{promotional_ad}', [App\Http\Controllers\Admin\PromotionalAdController::class, 'update'])->name('promotional-ads.update');
    });
    Route::delete('/promotional-ads/{promotional_ad}', [App\Http\Controllers\Admin\PromotionalAdController::class, 'destroy'])
        ->middleware('permission:promotional_ads.delete')->name('promotional-ads.destroy');

    // Database Backup Management
    Route::middleware('permission:backup.view')->group(function () {
        Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
        Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backup.download');
        Route::get('/backup/modules', [App\Http\Controllers\Admin\BackupController::class, 'getModules'])->name('backup.modules');
        Route::get('/backup/settings', [App\Http\Controllers\Admin\BackupSettingController::class, 'index'])->name('backup.settings');
    });
    Route::middleware('permission:backup.create')->group(function () {
        Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backup.create');
        Route::post('/backup/create-with-options', [App\Http\Controllers\Admin\BackupController::class, 'createWithOptions'])->name('backup.create-with-options');
        Route::post('/backup/settings', [App\Http\Controllers\Admin\BackupSettingController::class, 'update'])->name('backup.settings.update');
        Route::post('/backup/validate-upload', [App\Http\Controllers\Admin\BackupController::class, 'validateUpload'])->name('backup.validate-upload');
    });
    Route::middleware('permission:backup.restore')->group(function () {
        Route::post('/backup/restore', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
        Route::post('/backup/import-and-restore', [App\Http\Controllers\Admin\BackupController::class, 'importAndRestore'])->name('backup.import-and-restore');
        Route::post('/backup/purge-all-data', [App\Http\Controllers\Admin\BackupController::class, 'purgeAllData'])->name('backup.purge-all-data');
        Route::post('/backup/clear-frontend-cache', [App\Http\Controllers\Admin\BackupController::class, 'clearFrontendCache'])->name('backup.clear-frontend-cache');
    });
    Route::middleware('permission:backup.delete')->group(function () {
        Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backup.delete');
        Route::post('/backup/cleanup', [App\Http\Controllers\Admin\BackupController::class, 'cleanup'])->name('backup.cleanup');
        Route::post('/backup/cleanup-ajax', [App\Http\Controllers\Admin\BackupController::class, 'cleanupAjax'])->name('backup.cleanup-ajax');
        Route::post('/backup/cleanup-expired', [App\Http\Controllers\Admin\BackupSettingController::class, 'cleanupExpired'])->name('backup.cleanup-expired');
    });

    // Employee Roles Management (Admin Only)
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // Employee Management (Admin Only)
    Route::post('/employees/{employee}/toggle-status', [App\Http\Controllers\Admin\EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
});
