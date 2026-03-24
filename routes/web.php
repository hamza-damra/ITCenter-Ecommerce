<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Shared helper: serve a file from storage/app/public safely
if (! function_exists('serveStorageFile')) {
    function serveStorageFile(string $path): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // 1. Sanitize: strip traversal patterns, leading slashes, collapse duplicates
        $path = str_replace(['../', '..\\', '..'], '', $path);
        $path = ltrim($path, '/\\');
        $path = preg_replace('#[/\\\\]+#', '/', $path);

        // 2. Reject dangerous input
        if (empty($path) || str_contains($path, "\0") || str_contains($path, '..')) {
            abort(404);
        }

        // 3. Build full path & verify it exists
        $fullPath = storage_path('app/public/' . $path);

        if (! file_exists($fullPath) || ! is_file($fullPath)) {
            abort(404);
        }

        // 4. Allow only safe file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico', 'pdf', 'mp4', 'webm', 'mp3', 'ogg', 'zip'];
        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if (! in_array($extension, $allowedExtensions)) {
            abort(403);
        }

        // 5. Determine MIME type
        $mimeTypes = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
            'webp' => 'image/webp', 'gif' => 'image/gif', 'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon', 'pdf' => 'application/pdf',
            'mp4' => 'video/mp4', 'webm' => 'video/webm',
            'mp3' => 'audio/mpeg', 'ogg' => 'audio/ogg', 'zip' => 'application/zip',
        ];
        $mimeType = $mimeTypes[$extension] ?? (mime_content_type($fullPath) ?: 'application/octet-stream');

        // 6. Serve the file with proper headers
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=2592000',
            'Access-Control-Allow-Origin' => '*',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}

// Storage file serving route (primary)
// Serves files from storage/app/public via Laravel when symlink is broken on shared hosting
// .htaccess forces /storage/* through index.php so this route catches those requests
Route::get('/storage/{path}', function (string $path) {
    try {
        return serveStorageFile($path);
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        throw $e;
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Storage route exception', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
        ]);
        abort(500);
    }
})->where('path', '.*')->name('storage.serve');

// Media file serving route (backward compatibility - same logic)
Route::get('/media/{path}', function (string $path) {
    try {
        return serveStorageFile($path);
    } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
        throw $e;
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Media route exception', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
        ]);
        abort(500);
    }
})->where('path', '.*')->name('media.serve');

// Favicon route - serves the current favicon as a 48x48 PNG for browser compatibility
Route::get('/site-favicon', function () {
    $faviconSize = 48;
    $path = \App\Models\SiteSetting::getValue('site_favicon');

    if (empty($path)) {
        $defaultFavicon = public_path('favicon.ico');
        if (file_exists($defaultFavicon)) {
            return response()->file($defaultFavicon, ['Content-Type' => 'image/x-icon', 'Cache-Control' => 'public, max-age=86400']);
        }
        abort(404);
    }

    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404);
    }

    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

    // ICO files served as-is (already proper favicon format)
    if ($ext === 'ico') {
        return response()->file($fullPath, ['Content-Type' => 'image/x-icon', 'Cache-Control' => 'public, max-age=86400']);
    }

    // All other formats: load, crop to square, resize to 48x48, output as PNG
    $image = @imagecreatefromstring(file_get_contents($fullPath));
    if (!$image) {
        abort(404);
    }

    $srcW = imagesx($image);
    $srcH = imagesy($image);

    // Crop source to a square from the left-center (preserves logo icon on the left)
    $squareSize = min($srcW, $srcH);
    $cropX = 0; // Start from left side to preserve logo/icon portion
    $cropY = (int) round(($srcH - $squareSize) / 2);

    // Create 48x48 true-color image with alpha support
    $favicon = imagecreatetruecolor($faviconSize, $faviconSize);
    imagealphablending($favicon, false);
    imagesavealpha($favicon, true);
    $transparent = imagecolorallocatealpha($favicon, 0, 0, 0, 127);
    imagefill($favicon, 0, 0, $transparent);

    // Resize the square-cropped source into 48x48
    imagecopyresampled($favicon, $image, 0, 0, $cropX, $cropY, $faviconSize, $faviconSize, $squareSize, $squareSize);
    imagedestroy($image);

    ob_start();
    imagepng($favicon, null, 9);
    $pngData = ob_get_clean();
    imagedestroy($favicon);

    return response($pngData, 200, [
        'Content-Type' => 'image/png',
        'Content-Length' => strlen($pngData),
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('site-favicon');

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

Route::get('/privacy-policy', [PolicyController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/refund-policy', [PolicyController::class, 'refundPolicy'])->name('refund-policy');

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
    Route::get('/orders/{orderNumber}/confirmation', [App\Http\Controllers\OrderController::class, 'confirmation'])->name('orders.confirmation');
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
    Route::get('/debug', function () {
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
        Route::get('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
    });
    Route::middleware('permission:products.edit')->group(function () {
        Route::get('/products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}/delete-image', [App\Http\Controllers\Admin\ProductController::class, 'deleteProductImage'])->name('products.delete-image');
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
    });
    Route::middleware('permission:categories.edit')->group(function () {
        Route::get('/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}/delete-image', [App\Http\Controllers\Admin\CategoryController::class, 'deleteImage'])->name('categories.delete-image');
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
        Route::delete('/brands/{brand}/delete-image', [App\Http\Controllers\Admin\BrandController::class, 'deleteImage'])->name('brands.delete-image');
    });
    Route::delete('/brands/{brand}', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])
        ->middleware('permission:brands.delete')->name('brands.destroy');

    // Filters (literal paths before wildcards)
    Route::middleware('permission:filters.create')->group(function () {
        Route::get('/filters/create', [App\Http\Controllers\Admin\FilterController::class, 'create'])->name('filters.create');
        Route::post('/filters', [App\Http\Controllers\Admin\FilterController::class, 'store'])->name('filters.store');
    });
    Route::middleware('permission:filters.view')->group(function () {
        Route::get('/filters', [App\Http\Controllers\Admin\FilterController::class, 'index'])->name('filters.index');
        Route::get('/filters/category-filters/{categoryId}', [App\Http\Controllers\Admin\FilterController::class, 'getCategoryFilters'])->name('filters.category-filters');
    });
    Route::middleware('permission:filters.edit')->group(function () {
        Route::get('/filters/{filter}/edit', [App\Http\Controllers\Admin\FilterController::class, 'edit'])->name('filters.edit');
        Route::put('/filters/{filter}', [App\Http\Controllers\Admin\FilterController::class, 'update'])->name('filters.update');
        Route::post('/filters/{filter}/toggle-status', [App\Http\Controllers\Admin\FilterController::class, 'toggleStatus'])->name('filters.toggle-status');
        Route::post('/filters/section-settings', [App\Http\Controllers\Admin\FilterController::class, 'updateSectionSettings'])->name('filters.section-settings');
    });
    Route::delete('/filters/{filter}', [App\Http\Controllers\Admin\FilterController::class, 'destroy'])
        ->middleware('permission:filters.delete')->name('filters.destroy');

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
        Route::get('/spec-templates/{template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'show'])->name('spec-templates.show');
    });
    Route::middleware('permission:spec_templates.create')->group(function () {
        Route::post('/spec-templates/{template}/fields', [App\Http\Controllers\Admin\SpecTemplateController::class, 'storeField'])->name('spec-templates.fields.store');
    });
    Route::middleware('permission:spec_templates.edit')->group(function () {
        Route::get('/spec-templates/{template}/edit', [App\Http\Controllers\Admin\SpecTemplateController::class, 'edit'])->name('spec-templates.edit');
        Route::put('/spec-templates/{template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'update'])->name('spec-templates.update');
        Route::put('/spec-templates/{template}/fields/{field}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'updateField'])->name('spec-templates.fields.update');
        Route::post('/spec-templates/{template}/reorder-fields', [App\Http\Controllers\Admin\SpecTemplateController::class, 'reorderFields'])->name('spec-templates.reorder-fields');
    });
    Route::middleware('permission:spec_templates.delete')->group(function () {
        Route::delete('/spec-templates/{template}', [App\Http\Controllers\Admin\SpecTemplateController::class, 'destroy'])->name('spec-templates.destroy');
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

    // Home Page Sections Management
    Route::middleware('permission:home_sections.create')->group(function () {
        Route::get('/home-sections/create', [App\Http\Controllers\Admin\HomeSectionController::class, 'create'])->name('home-sections.create');
        Route::post('/home-sections', [App\Http\Controllers\Admin\HomeSectionController::class, 'store'])->name('home-sections.store');
    });
    Route::middleware('permission:home_sections.view')->group(function () {
        Route::get('/home-sections', [App\Http\Controllers\Admin\HomeSectionController::class, 'index'])->name('home-sections.index');
    });
    Route::middleware('permission:home_sections.edit')->group(function () {
        Route::get('/home-sections/{home_section}/edit', [App\Http\Controllers\Admin\HomeSectionController::class, 'edit'])->name('home-sections.edit');
        Route::put('/home-sections/{home_section}', [App\Http\Controllers\Admin\HomeSectionController::class, 'update'])->name('home-sections.update');
        Route::post('/home-sections/reorder', [App\Http\Controllers\Admin\HomeSectionController::class, 'reorder'])->name('home-sections.reorder');
        Route::post('/home-sections/{home_section}/toggle', [App\Http\Controllers\Admin\HomeSectionController::class, 'toggleActive'])->name('home-sections.toggle');
    });
    Route::delete('/home-sections/{home_section}', [App\Http\Controllers\Admin\HomeSectionController::class, 'destroy'])
        ->middleware('permission:home_sections.delete')->name('home-sections.destroy');

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

    // Shipping Management
    Route::middleware('permission:shipping.view')->group(function () {
        Route::get('/shipping', [App\Http\Controllers\Admin\ShippingManagementController::class, 'index'])->name('shipping.index');
    });
    Route::middleware('permission:shipping.create')->group(function () {
        Route::post('/shipping/regions', [App\Http\Controllers\Admin\ShippingManagementController::class, 'storeRegion'])->name('shipping.regions.store');
        Route::post('/shipping/cities', [App\Http\Controllers\Admin\ShippingManagementController::class, 'storeCity'])->name('shipping.cities.store');
        Route::post('/shipping/blocked-ranges', [App\Http\Controllers\Admin\ShippingManagementController::class, 'storeBlockedRange'])->name('shipping.blocked-ranges.store');
    });
    Route::middleware('permission:shipping.edit')->group(function () {
        Route::put('/shipping/regions/{region}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'updateRegion'])->name('shipping.regions.update');
        Route::put('/shipping/cities/{city}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'updateCity'])->name('shipping.cities.update');
        Route::put('/shipping/blocked-ranges/{blocked_range}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'updateBlockedRange'])->name('shipping.blocked-ranges.update');
        Route::put('/shipping/settings', [App\Http\Controllers\Admin\ShippingManagementController::class, 'updateSettings'])->name('shipping.settings.update');
        Route::post('/shipping/regions/{region}/toggle-status', [App\Http\Controllers\Admin\ShippingManagementController::class, 'toggleRegionStatus'])->name('shipping.regions.toggle-status');
        Route::post('/shipping/citys/{city}/toggle-status', [App\Http\Controllers\Admin\ShippingManagementController::class, 'toggleCityStatus'])->name('shipping.cities.toggle-status');
        Route::post('/shipping/blocked-ranges/{blocked_range}/toggle-status', [App\Http\Controllers\Admin\ShippingManagementController::class, 'toggleBlockedRangeStatus'])->name('shipping.blocked-ranges.toggle-status');
    });
    Route::middleware('permission:shipping.delete')->group(function () {
        Route::delete('/shipping/regions/{region}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'destroyRegion'])->name('shipping.regions.destroy');
        Route::delete('/shipping/cities/{city}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'destroyCity'])->name('shipping.cities.destroy');
        Route::delete('/shipping/blocked-ranges/{blocked_range}', [App\Http\Controllers\Admin\ShippingManagementController::class, 'destroyBlockedRange'])->name('shipping.blocked-ranges.destroy');
    });

    // Image Upload (AJAX endpoints for admin forms)
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::post('/product-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'uploadProductImage'])->name('product-image');
        Route::post('/category-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'uploadCategoryImage'])->name('category-image');
        Route::post('/brand-logo', [App\Http\Controllers\Admin\ImageUploadController::class, 'uploadBrandLogo'])->name('brand-logo');
        Route::post('/banner-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'uploadBannerImage'])->name('banner-image');
        Route::delete('/delete-image', [App\Http\Controllers\Admin\ImageUploadController::class, 'deleteImage'])->name('delete-image');
        Route::get('/check-storage', [App\Http\Controllers\Admin\ImageUploadController::class, 'checkStorage'])->name('check-storage');
    });

    // Site Settings (Admin Only)
    Route::get('/site-settings', [App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('site-settings.index');
    Route::put('/site-settings/images', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updateImageSettings'])->name('site-settings.update-images');
    Route::put('/site-settings/password', [App\Http\Controllers\Admin\SiteSettingsController::class, 'changePassword'])->name('site-settings.change-password');
    Route::put('/site-settings/privacy-policy', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updatePrivacyPolicy'])->name('site-settings.update-privacy-policy');
    Route::put('/site-settings/refund-policy', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updateRefundPolicy'])->name('site-settings.update-refund-policy');
    Route::put('/site-settings/social-links', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updateSocialLinks'])->name('site-settings.update-social-links');
    Route::put('/site-settings/favicon', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updateFavicon'])->name('site-settings.update-favicon');
    Route::delete('/site-settings/favicon', [App\Http\Controllers\Admin\SiteSettingsController::class, 'deleteFavicon'])->name('site-settings.delete-favicon');
    Route::put('/site-settings/logo', [App\Http\Controllers\Admin\SiteSettingsController::class, 'updateLogo'])->name('site-settings.update-logo');
    Route::delete('/site-settings/logo', [App\Http\Controllers\Admin\SiteSettingsController::class, 'deleteLogo'])->name('site-settings.delete-logo');

    // Employee Roles Management (Admin Only)
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // Employee Management (Admin Only)
    Route::post('/employees/{employee}/toggle-status', [App\Http\Controllers\Admin\EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::resource('employees', App\Http\Controllers\Admin\EmployeeController::class);
});

// ============================================================
// TEMPORARY: Cron Job Debug Route — DELETE AFTER DEBUGGING!
// ============================================================
Route::get('/cron-debug', function () {
    $html = '<html><head><title>Cron Debug</title><style>';
    $html .= 'body{font-family:monospace;background:#1a1a2e;color:#e0e0e0;padding:20px;line-height:1.6}';
    $html .= 'h2{color:#00d4ff;border-bottom:1px solid #333;padding-bottom:8px}';
    $html .= '.ok{color:#00ff88}.fail{color:#ff4444}.warn{color:#ffaa00}';
    $html .= 'pre{background:#16213e;padding:12px;border-radius:6px;overflow-x:auto;white-space:pre-wrap;max-height:300px;overflow-y:auto}';
    $html .= 'a.btn{color:#fff;background:#e74c3c;padding:10px 20px;text-decoration:none;border-radius:6px;display:inline-block;margin:5px}';
    $html .= 'a.btn-green{background:#27ae60}a.btn-blue{background:#2980b9}';
    $html .= '</style></head><body>';

    $html .= '<h1>🔧 Cron Job Debugger v2</h1>';
    $html .= '<p>Server Time: '.now()->format('Y-m-d H:i:s T').'</p>';

    // ── 1. Correct Cron Command ──
    $artisanPath = base_path('artisan');
    $html .= '<h2>1. ✅ Your CPanel Cron Job MUST be exactly this:</h2>';
    $html .= '<pre style="font-size:16px;color:#00ff88">* * * * * /usr/local/bin/php '.$artisanPath.' schedule:run >> /dev/null 2>&1</pre>';
    $html .= "<p class='warn'>⚠️ Make sure: artisan path = <b>{$artisanPath}</b> (NOT /home/itcentre/artisan)</p>";

    // ── 2. Cron Heartbeat Check ──
    $html .= '<h2>2. Cron Heartbeat (is cron actually running?)</h2>';
    $heartbeatFile = storage_path('app/cron-heartbeat.txt');
    if (file_exists($heartbeatFile)) {
        $lastBeat = file_get_contents($heartbeatFile);
        $lastBeatTime = \Carbon\Carbon::parse(trim($lastBeat));
        $minutesAgo = now()->diffInMinutes($lastBeatTime);
        if ($minutesAgo <= 2) {
            $html .= "<p class='ok'>✅ Cron IS running! Last heartbeat: {$lastBeat} ({$minutesAgo} min ago)</p>";
        } else {
            $html .= "<p class='fail'>❌ Cron is NOT running! Last heartbeat: {$lastBeat} ({$minutesAgo} minutes ago)</p>";
        }
    } else {
        $html .= "<p class='fail'>❌ No heartbeat file found — cron has NEVER run successfully.</p>";
        $html .= "<p>The heartbeat file should be created at: <code>{$heartbeatFile}</code></p>";
    }

    // ── 3. Schedule List ──
    $html .= '<h2>3. Scheduled Commands</h2>';
    try {
        \Illuminate\Support\Facades\Artisan::call('schedule:list');
        $html .= '<pre>'.htmlspecialchars(\Illuminate\Support\Facades\Artisan::output()).'</pre>';
    } catch (\Exception $e) {
        $html .= "<p class='fail'>Error: ".htmlspecialchars($e->getMessage()).'</p>';
    }

    // ── 4. Mutex / Overlapping Lock Check ──
    $html .= '<h2>4. Mutex Locks (withoutOverlapping check)</h2>';
    $cacheDir = storage_path('framework/cache/data');
    $scheduleMutexFound = false;
    if (is_dir($cacheDir)) {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($cacheDir));
        $mutexFiles = [];
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $content = @file_get_contents($file->getPathname());
                if ($content && (str_contains($content, 'schedule-') || str_contains($content, 'backup'))) {
                    $mutexFiles[] = [
                        'path' => $file->getPathname(),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        'content' => substr($content, 0, 200),
                    ];
                    $scheduleMutexFound = true;
                }
            }
        }
        if ($scheduleMutexFound) {
            $html .= "<p class='warn'>⚠️ Found ".count($mutexFiles).' mutex/schedule cache entries:</p>';
            foreach ($mutexFiles as $mf) {
                $html .= '<p>File: <code>'.basename($mf['path'])."</code> | Modified: {$mf['modified']}</p>";
                $html .= '<pre>'.htmlspecialchars($mf['content']).'</pre>';
            }
        } else {
            $html .= "<p class='ok'>✅ No stale mutex locks found</p>";
        }
    } else {
        $html .= "<p class='warn'>⚠️ Cache directory not found at: {$cacheDir}</p>";
        // Check if using database cache
        $html .= '<p>Cache driver: <b>'.config('cache.default').'</b></p>';
    }

    // ── 5. Backup Storage Check ──
    $html .= '<h2>5. Backup Storage</h2>';
    $backupPath = config('backup.path', storage_path('app/backups'));
    $html .= "<p>Backup path: <code>{$backupPath}</code></p>";
    if (is_dir($backupPath)) {
        $html .= "<p class='ok'>✅ Directory exists</p>";
        $html .= '<p>Writable: '.(is_writable($backupPath) ? "<span class='ok'>YES</span>" : "<span class='fail'>NO</span>").'</p>';
        $files = glob($backupPath.'/*');
        $html .= '<p>Files in backup dir: <b>'.count($files).'</b></p>';
        foreach (array_slice($files, -5) as $f) {
            $html .= '<p>  → '.basename($f).' ('.round(filesize($f) / 1024, 1).' KB)</p>';
        }
    } else {
        $html .= "<p class='warn'>⚠️ Directory does NOT exist — will be created on first backup</p>";
    }

    // ── 6. Direct Backup Test ──
    $html .= '<h2>6. 🔴 Direct Backup Test (bypass scheduler)</h2>';
    if (request()->has('test_backup')) {
        $html .= "<p class='warn'>Running backup:create directly...</p>";
        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call('backup:create');
            $output = \Illuminate\Support\Facades\Artisan::output();
            $html .= "<p>Exit code: <b>{$exitCode}</b></p>";
            $html .= '<pre>'.htmlspecialchars($output).'</pre>';
            if ($exitCode === 0) {
                $html .= "<p class='ok'>✅ Backup command completed!</p>";
            } else {
                $html .= "<p class='fail'>❌ Backup command failed with exit code {$exitCode}</p>";
            }
        } catch (\Exception $e) {
            $html .= "<p class='fail'>❌ Exception: ".htmlspecialchars($e->getMessage()).'</p>';
            $html .= '<pre>'.htmlspecialchars($e->getTraceAsString()).'</pre>';
        }
    } else {
        $html .= "<p><a class='btn' href='?test_backup=1'>⚡ Run backup:create NOW (direct test)</a></p>";
        $html .= '<p>This runs the backup command directly, bypassing the scheduler completely.</p>';
    }

    // ── 7. Schedule:run Test ──
    $html .= '<h2>7. 🟡 Run Scheduler</h2>';
    if (request()->has('run_schedule')) {
        $html .= "<p class='warn'>Running schedule:run...</p>";
        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call('schedule:run');
            $output = \Illuminate\Support\Facades\Artisan::output();
            $html .= "<p>Exit code: <b>{$exitCode}</b></p>";
            $html .= '<pre>'.htmlspecialchars($output).'</pre>';
        } catch (\Exception $e) {
            $html .= "<p class='fail'>❌ Exception: ".htmlspecialchars($e->getMessage()).'</p>';
            $html .= '<pre>'.htmlspecialchars($e->getTraceAsString()).'</pre>';
        }
    } else {
        $html .= "<p><a class='btn btn-blue' href='?run_schedule=1'>🕐 Run schedule:run</a></p>";
    }

    // ── 8. Clear Mutex Locks ──
    $html .= '<h2>8. 🧹 Clear Overlapping Locks</h2>';
    if (request()->has('clear_mutex')) {
        try {
            \Illuminate\Support\Facades\Cache::flush();
            $html .= "<p class='ok'>✅ Cache flushed — all mutex locks cleared!</p>";
        } catch (\Exception $e) {
            $html .= "<p class='fail'>❌ Error: ".htmlspecialchars($e->getMessage()).'</p>';
        }
    } else {
        $html .= "<p><a class='btn btn-green' href='?clear_mutex=1'>🧹 Clear all mutex/cache locks</a></p>";
        $html .= '<p>Use this if withoutOverlapping() is blocking commands from running.</p>';
    }

    // ── 9. Recent Logs ──
    $html .= '<h2>9. Recent Logs (last 30 lines)</h2>';
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $html .= '<p>Size: '.round(filesize($logFile) / 1024, 1).' KB</p>';
        $lines = file($logFile);
        $lastLines = array_slice($lines, -30);
        $html .= '<pre>'.htmlspecialchars(implode('', $lastLines)).'</pre>';
    } else {
        $html .= "<p class='warn'>No log file found</p>";
    }

    $html .= '<hr><p class="fail"><b>⚠️ DELETE THIS ROUTE FROM routes/web.php AFTER DEBUGGING!</b></p>';
    $html .= '</body></html>';

    return response($html);
})->name('cron-debug');
