# Fix: Raw CSS Rendering on Homepage Hero

## Issue
Raw CSS text was being rendered as visible content directly beneath the homepage carousel/slider. The CSS rules for promo-related classes (`.promo-badge`, `.promo-image`, `.promo-content`, `.promo-title`, `.promo-price`, `.price-row`, etc.) appeared as plain text, including the comment `/* removed old promo section styles */`.

## Root Cause
The `home.blade.php` file had two `<style>` blocks:
1. First block: Lines 6-1909 (properly opened and closed)
2. Second block: Lines 1940-2053 (missing opening `<style>` tag)

The CSS rules starting at line 1940 were not wrapped in a `<style>` tag, causing the browser to render them as plain text instead of parsing them as CSS.

Additionally, the `.promo-badge` selector declaration was missing, with only its properties present (starting with `top: 20px;`).

## Fix Applied

### File: `resources/views/home.blade.php`

**Changed lines 1939-1941:**
```html
<!-- Before -->
</div>

    top: 20px;

<!-- After -->
</div>

<style>
.promo-badge {
    position: absolute;
    top: 20px;
```

**Summary of changes:**
1. Added missing `<style>` opening tag at line 1940
2. Added complete `.promo-badge` selector with `position: absolute;` property
3. Ensured all promo-related CSS rules (lines 1940-2053) are now properly wrapped within the `<style>` block

The closing `</style>` tag was already present at line 2053.

## Testing

### Manual Verification
1. Cleared Laravel caches: `php artisan view:clear && php artisan cache:clear`
2. The homepage should now render without any visible CSS text

### Automated Test
Created `tests/Feature/HomePageTest.php` with two tests:
- `test_homepage_does_not_contain_raw_css_text()` - Asserts no raw CSS selectors or comments are visible
- `test_homepage_renders_successfully()` - Smoke test for homepage rendering

Run tests with:
```bash
composer test
# or
php artisan test
```

## Visual Impact
No visual changes to the design. The fix restores the intended styling for promo sections that was already defined but not being applied due to the missing `<style>` tag.

## Files Changed
- `resources/views/home.blade.php` - Fixed missing `<style>` tag and `.promo-badge` selector
- `tests/Feature/HomePageTest.php` - NEW: Regression test
- `tests/TestCase.php` - NEW: Base test class

## Prevention
The feature test will catch any future regressions where CSS is accidentally rendered as text on the homepage.
