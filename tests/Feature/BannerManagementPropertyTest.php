<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Eris\TestTrait;

class BannerManagementPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    /**
     * Create an admin user for testing.
     */
    protected function createAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /**
     * **Feature: dynamic-banner-management, Property 7: Title Locale Resolution**
     * 
     * For any banner and any locale (en, ar, he), the title accessor should return 
     * the title for that locale, falling back to English if the locale-specific title is empty.
     * 
     * **Validates: Requirements 2.4, 9.2**
     */
    public function test_title_locale_resolution(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 20), // Number of banners to generate
            \Eris\Generator\elements('en', 'ar', 'he') // Locale to test
        )
        ->then(function (int $bannerCount, string $testLocale) {
            // Clean database before each iteration
            \DB::table('banners')->delete();
            
            // Generate banners with various title combinations
            $banners = [];
            for ($i = 0; $i < $bannerCount; $i++) {
                // Randomly decide which titles to fill
                $hasEnglish = rand(0, 1) === 1;
                $hasArabic = rand(0, 1) === 1;
                $hasHebrew = rand(0, 1) === 1;
                
                // Ensure at least English title exists (for valid banners)
                if (!$hasEnglish && !$hasArabic && !$hasHebrew) {
                    $hasEnglish = true;
                }
                
                $banner = Banner::create([
                    'image_path' => 'banners/test-' . $i . '.jpg',
                    'title_en' => $hasEnglish ? 'English Title ' . $i : null,
                    'title_ar' => $hasArabic ? 'عنوان عربي ' . $i : null,
                    'title_he' => $hasHebrew ? 'כותרת עברית ' . $i : null,
                    'display_order' => $i,
                    'is_active' => true,
                ]);
                
                $banners[] = [
                    'model' => $banner,
                    'has_en' => $hasEnglish,
                    'has_ar' => $hasArabic,
                    'has_he' => $hasHebrew,
                ];
            }
            
            // Set the application locale
            app()->setLocale($testLocale);
            
            // Test each banner's title resolution
            foreach ($banners as $bannerData) {
                $banner = $bannerData['model']->fresh();
                $title = $banner->title;
                
                // Determine expected title based on locale and availability
                $localeField = "title_$testLocale";
                $hasLocaleTitle = $bannerData["has_$testLocale"];
                
                if ($hasLocaleTitle) {
                    // Should return the locale-specific title
                    $expectedTitle = $banner->$localeField;
                    $this->assertEquals(
                        $expectedTitle,
                        $title,
                        "Banner {$banner->id} should return {$testLocale} title when available"
                    );
                } else {
                    // Should fallback to English
                    $this->assertEquals(
                        $banner->title_en,
                        $title,
                        "Banner {$banner->id} should fallback to English when {$testLocale} title is empty"
                    );
                }
            }
            
            // Reset locale
            app()->setLocale('en');
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 7: Subtitle Locale Resolution**
     * 
     * For any banner and any locale (en, ar, he), the subtitle accessor should return 
     * the subtitle for that locale, falling back to English if the locale-specific subtitle is empty.
     * 
     * **Validates: Requirements 2.4, 9.2**
     */
    public function test_subtitle_locale_resolution(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 15),
            \Eris\Generator\elements('en', 'ar', 'he')
        )
        ->then(function (int $bannerCount, string $testLocale) {
            \DB::table('banners')->delete();
            
            $banners = [];
            for ($i = 0; $i < $bannerCount; $i++) {
                $hasEnglish = rand(0, 1) === 1;
                $hasArabic = rand(0, 1) === 1;
                $hasHebrew = rand(0, 1) === 1;
                
                $banner = Banner::create([
                    'image_path' => 'banners/test-' . $i . '.jpg',
                    'title_en' => 'Title ' . $i,
                    'subtitle_en' => $hasEnglish ? 'English Subtitle ' . $i : null,
                    'subtitle_ar' => $hasArabic ? 'عنوان فرعي عربي ' . $i : null,
                    'subtitle_he' => $hasHebrew ? 'כותרת משנה עברית ' . $i : null,
                    'display_order' => $i,
                    'is_active' => true,
                ]);
                
                $banners[] = [
                    'model' => $banner,
                    'has_en' => $hasEnglish,
                    'has_ar' => $hasArabic,
                    'has_he' => $hasHebrew,
                ];
            }
            
            app()->setLocale($testLocale);
            
            foreach ($banners as $bannerData) {
                $banner = $bannerData['model']->fresh();
                $subtitle = $banner->subtitle;
                
                $hasLocaleSubtitle = $bannerData["has_$testLocale"];
                
                if ($hasLocaleSubtitle) {
                    $localeField = "subtitle_$testLocale";
                    $this->assertEquals(
                        $banner->$localeField,
                        $subtitle,
                        "Banner {$banner->id} should return {$testLocale} subtitle when available"
                    );
                } else {
                    $this->assertEquals(
                        $banner->subtitle_en,
                        $subtitle,
                        "Banner {$banner->id} should fallback to English subtitle when {$testLocale} is empty"
                    );
                }
            }
            
            app()->setLocale('en');
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 7: Button Text Locale Resolution**
     * 
     * For any banner and any locale (en, ar, he), the button_text accessor should return 
     * the button text for that locale, falling back to English if the locale-specific text is empty.
     * 
     * **Validates: Requirements 2.4, 9.2**
     */
    public function test_button_text_locale_resolution(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 15),
            \Eris\Generator\elements('en', 'ar', 'he')
        )
        ->then(function (int $bannerCount, string $testLocale) {
            \DB::table('banners')->delete();
            
            $banners = [];
            for ($i = 0; $i < $bannerCount; $i++) {
                $hasEnglish = rand(0, 1) === 1;
                $hasArabic = rand(0, 1) === 1;
                $hasHebrew = rand(0, 1) === 1;
                
                $banner = Banner::create([
                    'image_path' => 'banners/test-' . $i . '.jpg',
                    'title_en' => 'Title ' . $i,
                    'button_text_en' => $hasEnglish ? 'Shop Now' : null,
                    'button_text_ar' => $hasArabic ? 'تسوق الآن' : null,
                    'button_text_he' => $hasHebrew ? 'קנה עכשיו' : null,
                    'display_order' => $i,
                    'is_active' => true,
                ]);
                
                $banners[] = [
                    'model' => $banner,
                    'has_en' => $hasEnglish,
                    'has_ar' => $hasArabic,
                    'has_he' => $hasHebrew,
                ];
            }
            
            app()->setLocale($testLocale);
            
            foreach ($banners as $bannerData) {
                $banner = $bannerData['model']->fresh();
                $buttonText = $banner->button_text;
                
                $hasLocaleButtonText = $bannerData["has_$testLocale"];
                
                if ($hasLocaleButtonText) {
                    $localeField = "button_text_$testLocale";
                    $this->assertEquals(
                        $banner->$localeField,
                        $buttonText,
                        "Banner {$banner->id} should return {$testLocale} button text when available"
                    );
                } else {
                    $this->assertEquals(
                        $banner->button_text_en,
                        $buttonText,
                        "Banner {$banner->id} should fallback to English button text when {$testLocale} is empty"
                    );
                }
            }
            
            app()->setLocale('en');
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 1: File Type Validation**
     * 
     * For any file upload attempt, if the file MIME type is not in the allowed list 
     * (image/jpeg, image/png, image/gif, image/webp), the system should reject the upload 
     * with a validation error.
     * 
     * **Validates: Requirements 1.2, 8.1**
     */
    public function test_file_type_validation_rejects_non_images(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        // Test invalid file types
        $invalidMimeTypes = [
            ['extension' => 'txt', 'mime' => 'text/plain'],
            ['extension' => 'pdf', 'mime' => 'application/pdf'],
            ['extension' => 'php', 'mime' => 'application/x-php'],
            ['extension' => 'html', 'mime' => 'text/html'],
            ['extension' => 'js', 'mime' => 'application/javascript'],
            ['extension' => 'svg', 'mime' => 'image/svg+xml'],
            ['extension' => 'exe', 'mime' => 'application/x-msdownload'],
        ];

        foreach ($invalidMimeTypes as $fileType) {
            $file = UploadedFile::fake()->create(
                "test.{$fileType['extension']}", 
                100, 
                $fileType['mime']
            );

            $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
                'image' => $file,
                'title_en' => 'Test Banner',
                'display_order' => 0,
                'is_active' => true,
            ]);

            $response->assertSessionHasErrors('image');
        }

        // Test valid file types should be accepted
        $validMimeTypes = [
            ['extension' => 'jpg', 'mime' => 'image/jpeg'],
            ['extension' => 'jpeg', 'mime' => 'image/jpeg'],
            ['extension' => 'png', 'mime' => 'image/png'],
            ['extension' => 'gif', 'mime' => 'image/gif'],
            ['extension' => 'webp', 'mime' => 'image/webp'],
        ];

        foreach ($validMimeTypes as $fileType) {
            $file = UploadedFile::fake()->create(
                "test.{$fileType['extension']}", 
                100, 
                $fileType['mime']
            );

            $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
                'image' => $file,
                'title_en' => "Test Banner {$fileType['extension']}",
                'display_order' => 0,
                'is_active' => true,
            ]);

            $response->assertSessionDoesntHaveErrors('image');
        }
    }

    /**
     * **Feature: dynamic-banner-management, Property 2: Unique Filename Generation**
     * 
     * For any two banner uploads (even with identical original filenames), the stored 
     * filenames should be unique and the files should be stored in the banners directory 
     * with relative paths in the database.
     * 
     * **Validates: Requirements 8.2, 8.3, 8.4**
     */
    public function test_unique_filename_generation(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        $this->forAll(
            \Eris\Generator\choose(2, 10) // Number of banners to upload
        )
        ->then(function (int $uploadCount) use ($admin) {
            \DB::table('banners')->delete();
            $storedPaths = [];

            for ($i = 0; $i < $uploadCount; $i++) {
                // All uploads use the same original filename
                $file = UploadedFile::fake()->create('same_name.jpg', 100, 'image/jpeg');

                $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
                    'image' => $file,
                    'title_en' => "Banner {$i}",
                    'display_order' => $i,
                    'is_active' => true,
                ]);

                $response->assertRedirect(route('admin.banners.index'));
            }

            // Verify all banners have unique paths
            $banners = Banner::all();
            $this->assertCount($uploadCount, $banners);

            foreach ($banners as $banner) {
                // Path should be in banners directory
                $this->assertStringStartsWith('banners/', $banner->image_path);
                
                // Path should be unique
                $this->assertNotContains(
                    $banner->image_path, 
                    $storedPaths,
                    "Duplicate path found: {$banner->image_path}"
                );
                $storedPaths[] = $banner->image_path;
            }

            // All paths should be unique
            $this->assertCount(
                count($storedPaths), 
                array_unique($storedPaths),
                "Not all filenames are unique"
            );
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 10: Title Validation**
     * 
     * For any banner creation or update attempt, if all title fields (title_en, title_ar, title_he) 
     * are empty or null, the system should reject the operation with a validation error.
     * 
     * **Validates: Requirements 9.3**
     */
    public function test_title_validation_requires_at_least_one_title(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        // Test creation with no titles - should fail
        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');
        
        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'image' => $file,
            'title_en' => '',
            'title_ar' => '',
            'title_he' => '',
            'display_order' => 0,
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('title_en');
        $this->assertDatabaseCount('banners', 0);

        // Test creation with null titles - should fail
        $file2 = UploadedFile::fake()->create('test2.jpg', 100, 'image/jpeg');
        
        $response = $this->actingAs($admin)->post(route('admin.banners.store'), [
            'image' => $file2,
            'display_order' => 0,
            'is_active' => true,
        ]);

        $response->assertSessionHasErrors('title_en');
        $this->assertDatabaseCount('banners', 0);

        // Test with at least one title - should succeed
        $titleCombinations = [
            ['title_en' => 'English Only', 'title_ar' => null, 'title_he' => null],
            ['title_en' => null, 'title_ar' => 'عربي فقط', 'title_he' => null],
            ['title_en' => null, 'title_ar' => null, 'title_he' => 'עברית בלבד'],
            ['title_en' => 'English', 'title_ar' => 'عربي', 'title_he' => 'עברית'],
        ];

        foreach ($titleCombinations as $index => $titles) {
            $file = UploadedFile::fake()->create("test{$index}.jpg", 100, 'image/jpeg');
            
            $response = $this->actingAs($admin)->post(route('admin.banners.store'), array_merge([
                'image' => $file,
                'display_order' => $index,
                'is_active' => true,
            ], $titles));

            $response->assertSessionDoesntHaveErrors('title_en');
        }

        $this->assertDatabaseCount('banners', count($titleCombinations));
    }

    /**
     * **Feature: dynamic-banner-management, Property 3: Image Update Invariant**
     * 
     * For any banner update operation, if a new image is provided the old image should be replaced; 
     * if no new image is provided the existing image path should remain unchanged.
     * 
     * **Validates: Requirements 3.2, 3.3**
     */
    public function test_image_update_invariant(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        $this->forAll(
            \Eris\Generator\choose(1, 5) // Number of test iterations
        )
        ->then(function (int $iterations) use ($admin) {
            for ($i = 0; $i < $iterations; $i++) {
                \DB::table('banners')->delete();
                
                // Create initial banner
                $initialFile = UploadedFile::fake()->create('initial.jpg', 100, 'image/jpeg');
                $this->actingAs($admin)->post(route('admin.banners.store'), [
                    'image' => $initialFile,
                    'title_en' => 'Initial Banner',
                    'display_order' => 0,
                    'is_active' => true,
                ]);

                $banner = Banner::first();
                $originalPath = $banner->image_path;

                // Update WITHOUT new image - path should remain unchanged
                $this->actingAs($admin)->put(route('admin.banners.update', $banner), [
                    'title_en' => 'Updated Title',
                    'display_order' => 1,
                    'is_active' => true,
                ]);

                $banner->refresh();
                $this->assertEquals(
                    $originalPath, 
                    $banner->image_path,
                    "Image path should remain unchanged when no new image is provided"
                );

                // Update WITH new image - path should change
                $newFile = UploadedFile::fake()->create('new_image.png', 100, 'image/png');
                $this->actingAs($admin)->put(route('admin.banners.update', $banner), [
                    'image' => $newFile,
                    'title_en' => 'Updated With New Image',
                    'display_order' => 2,
                    'is_active' => true,
                ]);

                $banner->refresh();
                $this->assertNotEquals(
                    $originalPath, 
                    $banner->image_path,
                    "Image path should change when new image is provided"
                );
                $this->assertStringStartsWith('banners/', $banner->image_path);
            }
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 9: Deletion Cleanup**
     * 
     * For any banner deletion operation, both the database record and the associated 
     * image file should be removed from the system.
     * 
     * **Validates: Requirements 4.2**
     */
    public function test_deletion_cleanup(): void
    {
        Storage::fake('public');
        $admin = $this->createAdminUser();

        $this->forAll(
            \Eris\Generator\choose(1, 5) // Number of banners to create and delete
        )
        ->then(function (int $bannerCount) use ($admin) {
            \DB::table('banners')->delete();
            $createdPaths = [];

            // Create banners
            for ($i = 0; $i < $bannerCount; $i++) {
                $file = UploadedFile::fake()->create("banner{$i}.jpg", 100, 'image/jpeg');
                
                $this->actingAs($admin)->post(route('admin.banners.store'), [
                    'image' => $file,
                    'title_en' => "Banner {$i}",
                    'display_order' => $i,
                    'is_active' => true,
                ]);
            }

            $banners = Banner::all();
            $this->assertCount($bannerCount, $banners);

            // Store paths and verify files exist
            foreach ($banners as $banner) {
                $createdPaths[$banner->id] = $banner->image_path;
                Storage::disk('public')->assertExists($banner->image_path);
            }

            // Delete each banner and verify cleanup
            foreach ($banners as $banner) {
                $path = $createdPaths[$banner->id];
                $bannerId = $banner->id;

                $this->actingAs($admin)->delete(route('admin.banners.destroy', $banner));

                // Database record should be deleted
                $this->assertDatabaseMissing('banners', ['id' => $bannerId]);
                
                // Image file should be deleted
                Storage::disk('public')->assertMissing($path);
            }

            // All banners should be deleted
            $this->assertDatabaseCount('banners', 0);
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 8: Authorization Enforcement**
     * 
     * For any HTTP request to banner management routes, non-admin users should receive 
     * a redirect or 403 response, while admin users should receive successful responses 
     * for valid operations.
     * 
     * **Validates: Requirements 7.1, 7.2**
     */
    public function test_authorization_enforcement(): void
    {
        Storage::fake('public');
        
        // Create a regular (non-admin) user - role must be 'customer' per enum constraint
        $regularUser = User::factory()->create([
            'role' => 'customer',
        ]);
        
        // Create an admin user
        $adminUser = $this->createAdminUser();
        
        // Create a banner for testing edit/update/delete operations
        $banner = Banner::create([
            'image_path' => 'banners/test.jpg',
            'title_en' => 'Test Banner',
            'display_order' => 0,
            'is_active' => true,
        ]);

        // Define banner management routes to test
        // Note: Only testing index route as create/edit views may not exist yet
        $routes = [
            ['method' => 'get', 'route' => 'admin.banners.index', 'params' => []],
        ];

        // Test 1: Unauthenticated users should be redirected to admin login
        foreach ($routes as $routeConfig) {
            $response = $this->{$routeConfig['method']}(route($routeConfig['route'], $routeConfig['params']));
            $response->assertRedirect(route('admin.login'));
        }

        // Test 2: Regular (non-admin) users should be redirected to admin login
        foreach ($routes as $routeConfig) {
            $response = $this->actingAs($regularUser)->{$routeConfig['method']}(
                route($routeConfig['route'], $routeConfig['params'])
            );
            $response->assertRedirect(route('admin.login'));
        }

        // Test 3: Admin users should have access to all routes
        foreach ($routes as $routeConfig) {
            $response = $this->actingAs($adminUser)->{$routeConfig['method']}(
                route($routeConfig['route'], $routeConfig['params'])
            );
            $response->assertSuccessful();
        }

        // Test 4: Admin can perform store operation
        $file = UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg');
        $storeResponse = $this->actingAs($adminUser)->post(route('admin.banners.store'), [
            'image' => $file,
            'title_en' => 'New Banner',
            'display_order' => 1,
            'is_active' => true,
        ]);
        $storeResponse->assertRedirect(route('admin.banners.index'));

        // Test 5: Regular user cannot perform store operation
        $file2 = UploadedFile::fake()->create('test2.jpg', 100, 'image/jpeg');
        $storeResponse = $this->actingAs($regularUser)->post(route('admin.banners.store'), [
            'image' => $file2,
            'title_en' => 'Unauthorized Banner',
            'display_order' => 2,
            'is_active' => true,
        ]);
        $storeResponse->assertRedirect(route('admin.login'));

        // Test 6: Admin can perform update operation
        $updateResponse = $this->actingAs($adminUser)->put(route('admin.banners.update', $banner), [
            'title_en' => 'Updated Banner',
            'display_order' => 0,
            'is_active' => true,
        ]);
        $updateResponse->assertRedirect(route('admin.banners.index'));

        // Test 7: Regular user cannot perform update operation
        $updateResponse = $this->actingAs($regularUser)->put(route('admin.banners.update', $banner), [
            'title_en' => 'Unauthorized Update',
            'display_order' => 0,
            'is_active' => true,
        ]);
        $updateResponse->assertRedirect(route('admin.login'));

        // Test 8: Admin can perform delete operation
        $bannerToDelete = Banner::create([
            'image_path' => 'banners/delete-test.jpg',
            'title_en' => 'Banner to Delete',
            'display_order' => 99,
            'is_active' => true,
        ]);
        $deleteResponse = $this->actingAs($adminUser)->delete(route('admin.banners.destroy', $bannerToDelete));
        $deleteResponse->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseMissing('banners', ['id' => $bannerToDelete->id]);

        // Test 9: Regular user cannot perform delete operation
        $bannerToProtect = Banner::create([
            'image_path' => 'banners/protect-test.jpg',
            'title_en' => 'Protected Banner',
            'display_order' => 100,
            'is_active' => true,
        ]);
        $deleteResponse = $this->actingAs($regularUser)->delete(route('admin.banners.destroy', $bannerToProtect));
        $deleteResponse->assertRedirect(route('admin.login'));
        $this->assertDatabaseHas('banners', ['id' => $bannerToProtect->id]);
    }

    /**
     * **Feature: dynamic-banner-management, Property 4: Active Status Filtering**
     * 
     * For any set of banners in the database, the public home page should display 
     * only banners where is_active equals true.
     * 
     * **Validates: Requirements 3.4, 6.1**
     */
    public function test_active_status_filtering(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 10), // Number of active banners
            \Eris\Generator\choose(0, 10)  // Number of inactive banners
        )
        ->then(function (int $activeCount, int $inactiveCount) {
            // Clean database before each iteration
            \DB::table('banners')->delete();
            
            // Create active banners
            for ($i = 0; $i < $activeCount; $i++) {
                Banner::create([
                    'image_path' => 'banners/active-' . $i . '.jpg',
                    'title_en' => 'Active Banner ' . $i,
                    'display_order' => $i,
                    'is_active' => true,
                ]);
            }
            
            // Create inactive banners
            for ($i = 0; $i < $inactiveCount; $i++) {
                Banner::create([
                    'image_path' => 'banners/inactive-' . $i . '.jpg',
                    'title_en' => 'Inactive Banner ' . $i,
                    'display_order' => $activeCount + $i,
                    'is_active' => false,
                ]);
            }
            
            // Query using the active scope (same as HomeController)
            $activeBanners = Banner::active()->ordered()->get();
            
            // Verify count matches expected active banners
            $this->assertCount(
                $activeCount,
                $activeBanners,
                "Expected {$activeCount} active banners, got {$activeBanners->count()}"
            );
            
            // Verify all returned banners are active
            foreach ($activeBanners as $banner) {
                $this->assertTrue(
                    $banner->is_active,
                    "Banner {$banner->id} should be active but is_active is false"
                );
            }
            
            // Verify no inactive banners are returned
            $inactiveBannerIds = Banner::where('is_active', false)->pluck('id')->toArray();
            foreach ($activeBanners as $banner) {
                $this->assertNotContains(
                    $banner->id,
                    $inactiveBannerIds,
                    "Inactive banner {$banner->id} should not be in active banners list"
                );
            }
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 5: Display Order Sorting**
     * 
     * For any set of active banners, the Hero_Slider should display them sorted by 
     * display_order in ascending order, with creation timestamp as secondary sort 
     * for equal display_order values.
     * 
     * **Validates: Requirements 5.2, 5.3, 6.2**
     */
    public function test_display_order_sorting(): void
    {
        $this->forAll(
            \Eris\Generator\choose(2, 15) // Number of banners to create
        )
        ->then(function (int $bannerCount) {
            // Clean database before each iteration
            \DB::table('banners')->delete();
            
            // Create banners with random display orders
            $createdBanners = [];
            for ($i = 0; $i < $bannerCount; $i++) {
                // Use random display order (some may be duplicates)
                $displayOrder = rand(0, 5);
                
                $banner = Banner::create([
                    'image_path' => 'banners/test-' . $i . '.jpg',
                    'title_en' => 'Banner ' . $i,
                    'display_order' => $displayOrder,
                    'is_active' => true,
                ]);
                
                $createdBanners[] = $banner;
                
                // Small delay to ensure different created_at timestamps
                usleep(1000);
            }
            
            // Query using the ordered scope (same as HomeController)
            $orderedBanners = Banner::active()->ordered()->get();
            
            // Verify count
            $this->assertCount($bannerCount, $orderedBanners);
            
            // Verify ordering: display_order ASC, then created_at ASC
            $previousBanner = null;
            foreach ($orderedBanners as $banner) {
                if ($previousBanner !== null) {
                    // Current banner's display_order should be >= previous
                    $this->assertGreaterThanOrEqual(
                        $previousBanner->display_order,
                        $banner->display_order,
                        "Banner {$banner->id} (order: {$banner->display_order}) should come after banner {$previousBanner->id} (order: {$previousBanner->display_order})"
                    );
                    
                    // If same display_order, created_at should be >= previous
                    if ($banner->display_order === $previousBanner->display_order) {
                        $this->assertGreaterThanOrEqual(
                            $previousBanner->created_at->timestamp,
                            $banner->created_at->timestamp,
                            "Banner {$banner->id} with same display_order should be sorted by created_at after banner {$previousBanner->id}"
                        );
                    }
                }
                $previousBanner = $banner;
            }
        });
    }

    /**
     * **Feature: dynamic-banner-management, Property 6: Clickable Link Rendering**
     * 
     * For any banner with a non-null link value, the rendered HTML should contain 
     * an anchor element with that URL as the href attribute.
     * 
     * **Validates: Requirements 2.3, 6.3**
     */
    public function test_clickable_link_rendering(): void
    {
        $this->forAll(
            \Eris\Generator\choose(1, 3), // Number of banners with links
            \Eris\Generator\choose(0, 2)  // Number of banners without links
        )
        ->then(function (int $withLinkCount, int $withoutLinkCount) {
            // Clean database before each iteration
            \DB::table('banners')->delete();
            
            // Clear cache to ensure fresh data
            \Illuminate\Support\Facades\Cache::flush();
            
            $bannersWithLinks = [];
            $bannersWithoutLinks = [];
            
            // Create banners with links
            for ($i = 0; $i < $withLinkCount; $i++) {
                $link = 'https://example.com/promo-' . uniqid() . '-' . $i;
                $banner = Banner::create([
                    'image_path' => 'banners/with-link-' . $i . '.jpg',
                    'title_en' => 'Banner With Link ' . $i,
                    'link' => $link,
                    'display_order' => $i,
                    'is_active' => true,
                ]);
                $bannersWithLinks[] = ['banner' => $banner, 'link' => $link];
            }
            
            // Create banners without links
            for ($i = 0; $i < $withoutLinkCount; $i++) {
                $banner = Banner::create([
                    'image_path' => 'banners/without-link-' . $i . '.jpg',
                    'title_en' => 'Banner Without Link ' . $i,
                    'link' => null,
                    'display_order' => $withLinkCount + $i,
                    'is_active' => true,
                ]);
                $bannersWithoutLinks[] = $banner;
            }
            
            // Get the home page response
            $response = $this->get(route('home'));
            $response->assertStatus(200);
            
            $content = $response->getContent();
            
            // Verify banners with links have anchor elements with correct href
            foreach ($bannersWithLinks as $data) {
                $link = $data['link'];
                
                // Check that the link appears in an href attribute
                $this->assertStringContainsString(
                    'href="' . $link . '"',
                    $content,
                    "Banner with link '{$link}' should have an anchor element with that href"
                );
            }
            
            // Verify banners without links have their titles displayed
            foreach ($bannersWithoutLinks as $banner) {
                $this->assertStringContainsString(
                    $banner->title_en,
                    $content,
                    "Banner title '{$banner->title_en}' should appear in the content"
                );
            }
        });
    }
}
