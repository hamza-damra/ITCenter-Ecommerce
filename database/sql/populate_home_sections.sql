-- ============================================
-- SQL Script to Populate Home Page Sections
-- ============================================

-- 1. Reset all product flags (Optional - for testing)
-- UPDATE products SET is_new = 0, is_featured = 0, is_bestseller = 0;

-- ============================================
-- 2. Set Featured Products (المنتجات المميزة)
-- ============================================
-- Mark first 10 active products as featured
UPDATE products 
SET is_featured = 1 
WHERE is_active = 1 
LIMIT 10;

-- ============================================
-- 3. Set New Arrivals (وصل حديثاً)
-- ============================================
-- Mark most recently created products as new
UPDATE products 
SET is_new = 1 
WHERE is_active = 1 
ORDER BY created_at DESC 
LIMIT 10;

-- Alternative: Mark products created in last 30 days
-- UPDATE products 
-- SET is_new = 1 
-- WHERE is_active = 1 
-- AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY);

-- ============================================
-- 4. Set Bestsellers (الأكثر مبيعاً)
-- ============================================
-- Mark products with highest sales as bestsellers
UPDATE products p
INNER JOIN (
    SELECT 
        oi.product_id,
        SUM(oi.quantity) as total_sales
    FROM order_items oi
    INNER JOIN orders o ON o.id = oi.order_id
    WHERE o.status = 'completed'
    GROUP BY oi.product_id
    ORDER BY total_sales DESC
    LIMIT 10
) as sales ON sales.product_id = p.id
SET p.is_bestseller = 1
WHERE p.is_active = 1;

-- Alternative: Manual selection
-- UPDATE products 
-- SET is_bestseller = 1 
-- WHERE id IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

-- ============================================
-- 5. Set Sale Prices (التخفيضات)
-- ============================================
-- Add sale prices to some products (20% discount)
UPDATE products 
SET sale_price = ROUND(price * 0.8, 2)
WHERE is_active = 1 
AND sale_price IS NULL
LIMIT 10;

-- Alternative: Set specific discounts
-- UPDATE products 
-- SET sale_price = 800 
-- WHERE id = 1 AND price = 1000;

-- ============================================
-- 6. Verify the data
-- ============================================

-- Check Featured Products
SELECT 
    id, 
    name_ar as name,
    price,
    sale_price,
    is_featured,
    is_new,
    is_bestseller
FROM products 
WHERE is_featured = 1 AND is_active = 1
LIMIT 10;

-- Check New Arrivals
SELECT 
    id, 
    name_ar as name,
    price,
    sale_price,
    is_new,
    created_at
FROM products 
WHERE is_new = 1 AND is_active = 1
ORDER BY created_at DESC
LIMIT 10;

-- Check Bestsellers
SELECT 
    id, 
    name_ar as name,
    price,
    sale_price,
    is_bestseller
FROM products 
WHERE is_bestseller = 1 AND is_active = 1
LIMIT 10;

-- Check On Sale Products
SELECT 
    id, 
    name_ar as name,
    price,
    sale_price,
    ROUND(((price - sale_price) / price * 100), 0) as discount_percentage
FROM products 
WHERE is_active = 1 
AND sale_price IS NOT NULL 
AND sale_price < price
LIMIT 10;

-- ============================================
-- 7. Count products in each section
-- ============================================
SELECT 
    'Featured' as section,
    COUNT(*) as count
FROM products 
WHERE is_featured = 1 AND is_active = 1

UNION ALL

SELECT 
    'New Arrivals' as section,
    COUNT(*) as count
FROM products 
WHERE is_new = 1 AND is_active = 1

UNION ALL

SELECT 
    'Bestsellers' as section,
    COUNT(*) as count
FROM products 
WHERE is_bestseller = 1 AND is_active = 1

UNION ALL

SELECT 
    'On Sale' as section,
    COUNT(*) as count
FROM products 
WHERE is_active = 1 
AND sale_price IS NOT NULL 
AND sale_price < price;
