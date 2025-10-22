<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FullProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all existing products and related data
        echo "Deleting all existing products...\n";
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductImage::truncate();
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo "All products deleted!\n";

        // Get categories and brands
        $categories = Category::all();
        $brands = Brand::all();

        if ($categories->isEmpty() || $brands->isEmpty()) {
            echo "Please run CategorySeeder and BrandSeeder first!\n";
            return;
        }

        // Map categories by slug for easy access
        $categoryMap = $categories->keyBy('slug');
        $brandMap = $brands->keyBy('slug');

        echo "Creating new products with quality metadata...\n";

        $products = [
            // LAPTOPS
            [
                'name_en' => 'Dell XPS 15 9520',
                'name_ar' => 'ديل اكس بي اس 15 9520',
                'name_he' => 'דל XPS 15 9520',
                'slug' => 'dell-xps-15-9520',
                'category' => 'laptops',
                'brand' => 'dell',
                'sku' => 'DELL-XPS15-9520',
                'price' => 7499,
                'sale_price' => 6999,
                'stock_quantity' => 15,
                'stock_status' => 'in_stock',
                'short_description_en' => '15.6" 4K OLED, Intel i7-12700H, 16GB RAM, 512GB SSD, RTX 3050 Ti',
                'short_description_ar' => 'شاشة 15.6 بوصة 4K OLED، معالج انتل i7-12700H، رام 16 جيجا، 512 جيجا SSD، RTX 3050 Ti',
                'short_description_he' => 'מסך 15.6 אינץ\' 4K OLED, Intel i7-12700H, 16GB RAM, 512GB SSD, RTX 3050 Ti',
                'description_en' => 'The Dell XPS 15 9520 is a premium laptop featuring a stunning 15.6-inch 4K OLED display, 12th Gen Intel Core i7 processor, 16GB DDR5 RAM, 512GB PCIe SSD, and NVIDIA GeForce RTX 3050 Ti graphics. Perfect for creative professionals and power users.',
                'description_ar' => 'ديل اكس بي اس 15 9520 هو جهاز محمول متميز يتميز بشاشة OLED مذهلة بحجم 15.6 بوصة بدقة 4K، ومعالج Intel Core i7 من الجيل الثاني عشر، وذاكرة وصول عشوائي DDR5 بسعة 16 جيجابايت، ومحرك أقراص SSD PCIe بسعة 512 جيجابايت، ورسومات NVIDIA GeForce RTX 3050 Ti. مثالي للمحترفين المبدعين ومستخدمي الطاقة.',
                'description_he' => 'Dell XPS 15 9520 הוא מחשב נייד פרימיום עם מסך OLED מדהים בגודל 15.6 אינץ\' ברזולוציית 4K, מעבד Intel Core i7 דור 12, 16GB DDR5 RAM, 512GB PCIe SSD וכרטיס מסך NVIDIA GeForce RTX 3050 Ti. מושלם עבור אנשי מקצוע יצירתיים ומשתמשי כוח.',
                'search_keywords' => 'laptop, dell, xps, 4k, oled, gaming, creative, professional, i7, rtx, premium',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/notebooks/xps-notebooks/xps-15-9520/media-gallery/notebook-xps-15-9520-nt-blue-gallery-4.psd?fmt=png-alpha&pscan=auto&scl=1&hei=804&wid=1284&qlt=100,1&resMode=sharp2&size=1284,804&chrss=full'
            ],
            [
                'name_en' => 'MacBook Pro 16" M2 Max',
                'name_ar' => 'ماك بوك برو 16 بوصة M2 ماكس',
                'name_he' => 'MacBook Pro 16 אינץ\' M2 Max',
                'slug' => 'macbook-pro-16-m2-max',
                'category' => 'laptops',
                'brand' => 'apple',
                'sku' => 'APPLE-MBP16-M2MAX',
                'price' => 12999,
                'sale_price' => null,
                'stock_quantity' => 8,
                'stock_status' => 'in_stock',
                'short_description_en' => '16" Liquid Retina XDR, M2 Max chip, 32GB unified memory, 1TB SSD',
                'short_description_ar' => 'شاشة 16 بوصة Liquid Retina XDR، شريحة M2 Max، ذاكرة موحدة 32 جيجا، 1 تيرابايت SSD',
                'short_description_he' => 'מסך 16 אינץ\' Liquid Retina XDR, שבב M2 Max, זיכרון 32GB, 1TB SSD',
                'description_en' => 'The ultimate pro laptop. MacBook Pro with M2 Max chip delivers exceptional performance for the most demanding workflows. Features a stunning 16-inch Liquid Retina XDR display, up to 22 hours battery life, and advanced thermal architecture.',
                'description_ar' => 'الجهاز المحمول الاحترافي النهائي. يوفر MacBook Pro مع شريحة M2 Max أداءً استثنائيًا لأكثر سير العمل تطلبًا. يتميز بشاشة Liquid Retina XDR مذهلة بحجم 16 بوصة، وعمر بطارية يصل إلى 22 ساعة، وهندسة حرارية متقدمة.',
                'description_he' => 'המחשב הנייד המקצועי האולטימטיבי. MacBook Pro עם שבב M2 Max מספק ביצועים יוצאי דופן לזרימות העבודה התובעניות ביותר. כולל מסך Liquid Retina XDR מדהים בגודל 16 אינץ\', חיי סוללה של עד 22 שעות וארכיטקטורה תרמית מתקדמת.',
                'search_keywords' => 'macbook, pro, apple, m2, max, laptop, professional, creative, video editing, development',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/mbp16-spacegray-select-202301?wid=904&hei=840&fmt=jpeg&qlt=90&.v=1671304673202'
            ],
            [
                'name_en' => 'HP Pavilion 15',
                'name_ar' => 'اتش بي بافيليون 15',
                'name_he' => 'HP Pavilion 15',
                'slug' => 'hp-pavilion-15',
                'category' => 'laptops',
                'brand' => 'hp',
                'sku' => 'HP-PAV15-2023',
                'price' => 3299,
                'sale_price' => 2999,
                'stock_quantity' => 25,
                'stock_status' => 'in_stock',
                'short_description_en' => '15.6" FHD, Intel i5-1235U, 8GB RAM, 256GB SSD',
                'short_description_ar' => 'شاشة 15.6 بوصة FHD، انتل i5-1235U، 8 جيجا رام، 256 جيجا SSD',
                'short_description_he' => 'מסך 15.6 אינץ\' FHD, Intel i5-1235U, 8GB RAM, 256GB SSD',
                'description_en' => 'Perfect for everyday computing. The HP Pavilion 15 combines style and performance with a sleek design, Full HD display, and powerful Intel processor. Ideal for students and home users.',
                'description_ar' => 'مثالي للحوسبة اليومية. يجمع HP Pavilion 15 بين الأناقة والأداء بتصميم أنيق وشاشة Full HD ومعالج Intel قوي. مثالي للطلاب ومستخدمي المنزل.',
                'description_he' => 'מושלם למחשוב יומיומי. ה-HP Pavilion 15 משלב סטייל וביצועים עם עיצוב אלגנטי, תצוגת Full HD ומעבד Intel חזק. אידיאלי לסטודנטים ומשתמשי בית.',
                'search_keywords' => 'laptop, hp, pavilion, affordable, student, home, everyday, budget',
                'is_featured' => false,
                'is_new' => false,
                'image' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c08111255.png'
            ],
            [
                'name_en' => 'Lenovo ThinkPad X1 Carbon Gen 11',
                'name_ar' => 'لينوفو ثينك باد X1 كاربون الجيل 11',
                'name_he' => 'Lenovo ThinkPad X1 Carbon Gen 11',
                'slug' => 'lenovo-thinkpad-x1-carbon-gen11',
                'category' => 'laptops',
                'brand' => 'lenovo',
                'sku' => 'LEN-X1C-G11',
                'price' => 8499,
                'sale_price' => 7999,
                'stock_quantity' => 12,
                'stock_status' => 'in_stock',
                'short_description_en' => '14" WUXGA, Intel i7-1365U, 16GB RAM, 512GB SSD, Ultra-portable',
                'short_description_ar' => 'شاشة 14 بوصة WUXGA، انتل i7-1365U، 16 جيجا رام، 512 جيجا SSD، خفيف الوزن',
                'short_description_he' => 'מסך 14 אינץ\' WUXGA, Intel i7-1365U, 16GB RAM, 512GB SSD, קל משקל',
                'description_en' => 'The legendary ThinkPad X1 Carbon Gen 11. Ultra-portable business laptop with military-grade durability, stunning display, and all-day battery life. Perfect for business professionals on the go.',
                'description_ar' => 'ThinkPad X1 Carbon Gen 11 الأسطوري. جهاز محمول للأعمال خفيف الوزن للغاية مع متانة عسكرية وشاشة مذهلة وعمر بطارية طوال اليوم. مثالي لرجال الأعمال أثناء التنقل.',
                'description_he' => 'ה-ThinkPad X1 Carbon Gen 11 האגדי. מחשב נייד עסקי קל במיוחד עם עמידות ברמה צבאית, מסך מדהים וחיי סוללה ליום שלם. מושלם עבור אנשי עסקים בתנועה.',
                'search_keywords' => 'laptop, lenovo, thinkpad, business, professional, ultrabook, portable, durable',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://p1-ofp.static.pub/fes/cms/2023/01/13/r8r2hd0gfh4rrykm7xhwql3m7cqxj2658347.png'
            ],

            // DESKTOPS
            [
                'name_en' => 'Dell Alienware Aurora R15',
                'name_ar' => 'ديل اليين وير اورورا R15',
                'name_he' => 'Dell Alienware Aurora R15',
                'slug' => 'dell-alienware-aurora-r15',
                'category' => 'desktops',
                'brand' => 'dell',
                'sku' => 'DELL-AWR15-2023',
                'price' => 9999,
                'sale_price' => 8999,
                'stock_quantity' => 5,
                'stock_status' => 'in_stock',
                'short_description_en' => 'Intel i9-13900KF, RTX 4080, 32GB DDR5, 1TB NVMe SSD',
                'short_description_ar' => 'انتل i9-13900KF، RTX 4080، 32 جيجا DDR5، 1 تيرابايت NVMe SSD',
                'short_description_he' => 'Intel i9-13900KF, RTX 4080, 32GB DDR5, 1TB NVMe SSD',
                'description_en' => 'The ultimate gaming desktop. Alienware Aurora R15 features the latest Intel Core i9 processor, NVIDIA RTX 4080 graphics, and advanced cooling. Dominate any game at maximum settings.',
                'description_ar' => 'سطح المكتب الألعاب النهائي. يتميز Alienware Aurora R15 بأحدث معالج Intel Core i9 ورسومات NVIDIA RTX 4080 وتبريد متقدم. سيطر على أي لعبة بأقصى الإعدادات.',
                'description_he' => 'מחשב הגיימינג האולטימטיבי. Alienware Aurora R15 כולל מעבד Intel Core i9 העדכני ביותר, כרטיס מסך NVIDIA RTX 4080 וקירור מתקדם. שלוט בכל משחק בהגדרות המקסימליות.',
                'search_keywords' => 'gaming, desktop, alienware, dell, rtx 4080, i9, high-end, powerful, gaming pc',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/desktops/alienware-desktops/alienware-aurora-r15-desktop/media-gallery/desktop-aw-r15-bk-gallery-1.psd?fmt=png-alpha&pscan=auto&scl=1&hei=804&wid=1068&qlt=100,1&resMode=sharp2&size=1068,804&chrss=full'
            ],
            [
                'name_en' => 'HP Omen 45L Gaming Desktop',
                'name_ar' => 'اتش بي اومن 45L للألعاب',
                'name_he' => 'HP Omen 45L גיימינג',
                'slug' => 'hp-omen-45l-gaming',
                'category' => 'desktops',
                'brand' => 'hp',
                'sku' => 'HP-OMEN45L-2023',
                'price' => 7999,
                'sale_price' => 7299,
                'stock_quantity' => 8,
                'stock_status' => 'in_stock',
                'short_description_en' => 'AMD Ryzen 7 7700X, RTX 4070, 16GB DDR5, 1TB SSD',
                'short_description_ar' => 'AMD Ryzen 7 7700X، RTX 4070، 16 جيجا DDR5، 1 تيرابايت SSD',
                'short_description_he' => 'AMD Ryzen 7 7700X, RTX 4070, 16GB DDR5, 1TB SSD',
                'description_en' => 'Unleash your gaming potential with the HP Omen 45L. Featuring AMD Ryzen 7 processor, NVIDIA RTX 4070 graphics, and tempered glass chassis with customizable RGB lighting.',
                'description_ar' => 'أطلق العنان لإمكانات الألعاب الخاصة بك مع HP Omen 45L. يتميز بمعالج AMD Ryzen 7 ورسومات NVIDIA RTX 4070 وهيكل زجاجي مقسى مع إضاءة RGB قابلة للتخصيص.',
                'description_he' => 'שחרר את פוטנציאל הגיימינג שלך עם HP Omen 45L. כולל מעבד AMD Ryzen 7, כרטיס מסך NVIDIA RTX 4070 ומארז זכוכית מחוסמת עם תאורת RGB להתאמה אישית.',
                'search_keywords' => 'gaming, desktop, hp, omen, ryzen, rtx 4070, rgb, amd, gaming pc',
                'is_featured' => true,
                'is_new' => false,
                'image' => 'https://ssl-product-images.www8-hp.com/digmedialib/prodimg/lowres/c08111260.png'
            ],
            [
                'name_en' => 'Apple iMac 24" M3',
                'name_ar' => 'ابل ايماك 24 بوصة M3',
                'name_he' => 'Apple iMac 24 אינץ\' M3',
                'slug' => 'apple-imac-24-m3',
                'category' => 'desktops',
                'brand' => 'apple',
                'sku' => 'APPLE-IMAC24-M3',
                'price' => 7499,
                'sale_price' => null,
                'stock_quantity' => 10,
                'stock_status' => 'in_stock',
                'short_description_en' => '24" 4.5K Retina, M3 chip, 8GB unified memory, 256GB SSD',
                'short_description_ar' => 'شاشة 24 بوصة 4.5K Retina، شريحة M3، 8 جيجا ذاكرة موحدة، 256 جيجا SSD',
                'short_description_he' => 'מסך 24 אינץ\' 4.5K Retina, שבב M3, 8GB זיכרון, 256GB SSD',
                'description_en' => 'The all-in-one iMac with stunning 24-inch 4.5K Retina display and powerful M3 chip. Available in vibrant colors, featuring advanced camera and studio-quality mics. Perfect for creative work and everyday tasks.',
                'description_ar' => 'جهاز iMac الكل في واحد مع شاشة Retina مذهلة بحجم 24 بوصة بدقة 4.5K وشريحة M3 قوية. متوفر بألوان نابضة بالحياة، ويتميز بكاميرا متقدمة وميكروفونات بجودة الاستوديو. مثالي للعمل الإبداعي والمهام اليومية.',
                'description_he' => 'ה-iMac הכל-באחד עם מסך Retina מדהים בגודל 24 אינץ\' ברזולוציית 4.5K ושבב M3 חזק. זמין בצבעים תוססים, כולל מצלמה מתקדמת ומיקרופונים באיכות אולפן. מושלם לעבודה יצירתית ומשימות יומיומיות.',
                'search_keywords' => 'imac, apple, all-in-one, desktop, m3, creative, design, 4k, retina',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/imac-24-blue-selection-hero-202310?wid=904&hei=840&fmt=jpeg&qlt=90&.v=1697909056408'
            ],

            // MONITORS
            [
                'name_en' => 'LG UltraGear 27" Gaming Monitor',
                'name_ar' => 'ال جي الترا جير 27 بوصة شاشة للألعاب',
                'name_he' => 'LG UltraGear 27 אינץ\' צג גיימינג',
                'slug' => 'lg-ultragear-27-gaming',
                'category' => 'monitors',
                'brand' => 'lg',
                'sku' => 'LG-UG27-165HZ',
                'price' => 1799,
                'sale_price' => 1599,
                'stock_quantity' => 20,
                'stock_status' => 'in_stock',
                'short_description_en' => '27" QHD IPS, 165Hz, 1ms, G-Sync Compatible, HDR10',
                'short_description_ar' => '27 بوصة QHD IPS، 165 هرتز، 1 مللي ثانية، متوافق مع G-Sync، HDR10',
                'short_description_he' => '27 אינץ\' QHD IPS, 165Hz, 1ms, תמיכה ב-G-Sync, HDR10',
                'description_en' => 'Experience gaming like never before with the LG UltraGear 27" monitor. Features 165Hz refresh rate, 1ms response time, and stunning QHD resolution. G-Sync compatible for tear-free gaming.',
                'description_ar' => 'استمتع بتجربة ألعاب لم يسبق لها مثيل مع شاشة LG UltraGear 27 بوصة. يتميز بمعدل تحديث 165 هرتز ووقت استجابة 1 مللي ثانية ودقة QHD مذهلة. متوافق مع G-Sync للألعاب الخالية من التمزق.',
                'description_he' => 'חווה גיימינג כמו שמעולם לא היה עם צג LG UltraGear 27 אינץ\'. כולל קצב רענון 165Hz, זמן תגובה 1ms ורזולוציית QHD מדהימה. תומך ב-G-Sync למשחק ללא קרעים.',
                'search_keywords' => 'monitor, gaming, lg, ultragear, 165hz, qhd, g-sync, fast, responsive',
                'is_featured' => true,
                'is_new' => false,
                'image' => 'https://www.lg.com/content/dam/channel/wcms/us/images/monitors/27gn800-b_aus_ecare_350.jpg'
            ],
            [
                'name_en' => 'Dell UltraSharp 32" 4K Monitor',
                'name_ar' => 'ديل الترا شارب 32 بوصة 4K',
                'name_he' => 'Dell UltraSharp 32 אינץ\' 4K',
                'slug' => 'dell-ultrasharp-32-4k',
                'category' => 'monitors',
                'brand' => 'dell',
                'sku' => 'DELL-US32-4K',
                'price' => 2999,
                'sale_price' => null,
                'stock_quantity' => 15,
                'stock_status' => 'in_stock',
                'short_description_en' => '32" 4K UHD IPS, 99% sRGB, USB-C, Height Adjustable',
                'short_description_ar' => '32 بوصة 4K UHD IPS، 99% sRGB، USB-C، قابل لتعديل الارتفاع',
                'short_description_he' => '32 אינץ\' 4K UHD IPS, 99% sRGB, USB-C, כולל כוונון גובה',
                'description_en' => 'Professional-grade Dell UltraSharp 32" 4K monitor with stunning color accuracy (99% sRGB), USB-C connectivity, and ergonomic stand. Perfect for content creators and professionals.',
                'description_ar' => 'شاشة Dell UltraSharp 32 بوصة 4K ذات الدرجة الاحترافية مع دقة ألوان مذهلة (99٪ sRGB) واتصال USB-C وحامل مريح. مثالي لمنشئي المحتوى والمحترفين.',
                'description_he' => 'צג Dell UltraSharp 32 אינץ\' 4K ברמה מקצועית עם דיוק צבעים מדהים (99% sRGB), חיבור USB-C ומעמד ארגונומי. מושלם ליוצרי תוכן ואנשי מקצוע.',
                'search_keywords' => 'monitor, dell, ultrasharp, 4k, professional, color accurate, usb-c, content creation',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://i.dell.com/is/image/DellContent/content/dam/ss2/product-images/dell-client-products/peripherals/monitors/u-series/u3223qe/media-gallery/monitor-u3223qe-gallery-1.psd?fmt=png-alpha&pscan=auto&scl=1&hei=804&wid=1070&qlt=100,1&resMode=sharp2&size=1070,804&chrss=full'
            ],

            // KEYBOARDS & MICE
            [
                'name_en' => 'Logitech MX Keys Advanced Wireless Keyboard',
                'name_ar' => 'لوجيتك MX كيز لوحة مفاتيح لاسلكية متقدمة',
                'name_he' => 'Logitech MX Keys מקלדת אלחוטית מתקדמת',
                'slug' => 'logitech-mx-keys',
                'category' => 'keyboards-mice',
                'brand' => 'logitech',
                'sku' => 'LOGI-MXKEYS',
                'price' => 499,
                'sale_price' => 449,
                'stock_quantity' => 30,
                'stock_status' => 'in_stock',
                'short_description_en' => 'Wireless, Backlit, Multi-Device, USB-C Rechargeable',
                'short_description_ar' => 'لاسلكية، مضاءة، متعددة الأجهزة، قابلة لإعادة الشحن عبر USB-C',
                'short_description_he' => 'אלחוטית, מוארת, רב-מכשירית, נטענת USB-C',
                'description_en' => 'Type with confidence on the Logitech MX Keys. Features smart illumination, perfect-stroke keys, and the ability to connect to multiple devices. USB-C rechargeable with up to 10 days battery life.',
                'description_ar' => 'اكتب بثقة على Logitech MX Keys. تتميز بإضاءة ذكية ومفاتيح مثالية والقدرة على الاتصال بأجهزة متعددة. قابلة لإعادة الشحن عبر USB-C مع عمر بطارية يصل إلى 10 أيام.',
                'description_he' => 'הקלד בביטחון עם Logitech MX Keys. כולל תאורה חכמה, מקשים במכה מושלמת ויכולת להתחבר למספר מכשירים. נטען USB-C עם חיי סוללה של עד 10 ימים.',
                'search_keywords' => 'keyboard, logitech, mx keys, wireless, backlit, professional, typing, productivity',
                'is_featured' => false,
                'is_new' => false,
                'image' => 'https://resource.logitech.com/w_692,c_limit,q_auto,f_auto,dpr_1.0/d_transparent.gif/content/dam/logitech/en/products/keyboards/mx-keys/gallery/mx-keys-gallery-graphite-1.png'
            ],
            [
                'name_en' => 'Logitech MX Master 3S Wireless Mouse',
                'name_ar' => 'لوجيتك MX ماستر 3S فأرة لاسلكية',
                'name_he' => 'Logitech MX Master 3S עכבר אלחוטי',
                'slug' => 'logitech-mx-master-3s',
                'category' => 'keyboards-mice',
                'brand' => 'logitech',
                'sku' => 'LOGI-MXM3S',
                'price' => 429,
                'sale_price' => 399,
                'stock_quantity' => 35,
                'stock_status' => 'in_stock',
                'short_description_en' => '8K DPI, Quiet Clicks, Multi-Device, USB-C Fast Charging',
                'short_description_ar' => '8K DPI، نقرات هادئة، متعدد الأجهزة، شحن سريع USB-C',
                'short_description_he' => '8K DPI, קליקים שקטים, רב-מכשירי, טעינה מהירה USB-C',
                'description_en' => 'Work faster with the Logitech MX Master 3S. Ultra-precise 8K DPI sensor, quiet clicks, and electromagnetic scrolling. Connect to multiple devices and switch between them seamlessly.',
                'description_ar' => 'اعمل بشكل أسرع مع Logitech MX Master 3S. مستشعر 8K DPI فائق الدقة، ونقرات هادئة، وتمرير كهرومغناطيسي. اتصل بأجهزة متعددة وتبديل بينها بسلاسة.',
                'description_he' => 'עבוד מהר יותר עם Logitech MX Master 3S. חיישן 8K DPI דיוק מרבי, קליקים שקטים וגלילה אלקטרומגנטית. התחבר למספר מכשירים ועבור ביניהם בצורה חלקה.',
                'search_keywords' => 'mouse, logitech, mx master, wireless, professional, productivity, ergonomic, precise',
                'is_featured' => true,
                'is_new' => false,
                'image' => 'https://resource.logitech.com/w_692,c_limit,q_auto,f_auto,dpr_1.0/d_transparent.gif/content/dam/logitech/en/products/mice/mx-master-3s/gallery/mx-master-3s-mouse-top-view-graphite.png'
            ],

            // HEADPHONES
            [
                'name_en' => 'Sony WH-1000XM5 Wireless Headphones',
                'name_ar' => 'سوني WH-1000XM5 سماعات لاسلكية',
                'name_he' => 'Sony WH-1000XM5 אוזניות אלחוטיות',
                'slug' => 'sony-wh1000xm5',
                'category' => 'headphones',
                'brand' => 'sony',
                'sku' => 'SONY-WH1000XM5',
                'price' => 1699,
                'sale_price' => 1499,
                'stock_quantity' => 18,
                'stock_status' => 'in_stock',
                'short_description_en' => 'Industry-leading Noise Canceling, 30hr Battery, Hi-Res Audio',
                'short_description_ar' => 'إلغاء ضوضاء رائد في الصناعة، 30 ساعة بطارية، صوت عالي الدقة',
                'short_description_he' => 'ביטול רעשים מוביל בתעשייה, סוללה 30 שעות, אודיו Hi-Res',
                'description_en' => 'Experience world-class noise cancellation with Sony WH-1000XM5. Features 30-hour battery life, exceptional sound quality, multipoint connection, and speak-to-chat technology. Perfect for work, travel, and music.',
                'description_ar' => 'استمتع بإلغاء الضوضاء على مستوى عالمي مع Sony WH-1000XM5. يتميز بعمر بطارية 30 ساعة وجودة صوت استثنائية واتصال متعدد النقاط وتقنية التحدث للدردشة. مثالي للعمل والسفر والموسيقى.',
                'description_he' => 'חווה ביטול רעשים ברמה עולמית עם Sony WH-1000XM5. כולל חיי סוללה של 30 שעות, איכות צליל יוצאת דופן, חיבור רב-נקודתי וטכנולוגיית דיבור לצ\'אט. מושלם לעבודה, נסיעות ומוזיקה.',
                'search_keywords' => 'headphones, sony, wireless, noise canceling, premium, audio, travel, music',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://www.sony.com/image/5d02da5df552836db0363175dd3e3e2c?fmt=png-alpha&wid=660'
            ],

            // GRAPHICS CARDS
            [
                'name_en' => 'NVIDIA GeForce RTX 4090',
                'name_ar' => 'انفيديا جي فورس RTX 4090',
                'name_he' => 'NVIDIA GeForce RTX 4090',
                'slug' => 'nvidia-rtx-4090',
                'category' => 'graphics-cards',
                'brand' => 'nvidia',
                'sku' => 'NVIDIA-RTX4090',
                'price' => 8999,
                'sale_price' => null,
                'stock_quantity' => 3,
                'stock_status' => 'in_stock',
                'short_description_en' => '24GB GDDR6X, Ada Lovelace, Ray Tracing, DLSS 3',
                'short_description_ar' => '24 جيجا GDDR6X، Ada Lovelace، تتبع الأشعة، DLSS 3',
                'short_description_he' => '24GB GDDR6X, Ada Lovelace, Ray Tracing, DLSS 3',
                'description_en' => 'The ultimate graphics card for gaming and creation. NVIDIA RTX 4090 delivers revolutionary performance with 24GB memory, Ada Lovelace architecture, and DLSS 3. Play at 4K with maxed-out settings.',
                'description_ar' => 'بطاقة الرسومات النهائية للألعاب والإبداع. يقدم NVIDIA RTX 4090 أداءً ثوريًا مع ذاكرة 24 جيجابايت وبنية Ada Lovelace و DLSS 3. العب بدقة 4K مع الإعدادات القصوى.',
                'description_he' => 'כרטיס המסך האולטימטיבי לגיימינג ויצירה. NVIDIA RTX 4090 מספק ביצועים מהפכניים עם זיכרון 24GB, ארכיטקטורת Ada Lovelace ו-DLSS 3. שחק ב-4K עם הגדרות מקסימליות.',
                'search_keywords' => 'graphics card, gpu, nvidia, rtx 4090, gaming, high-end, 4k, ray tracing, powerful',
                'is_featured' => true,
                'is_new' => false,
                'image' => 'https://www.nvidia.com/content/dam/en-zz/Solutions/geforce/ada/rtx-4090/geforce-ada-4090-web-og-1200x630.jpg'
            ],

            // STORAGE
            [
                'name_en' => 'Samsung 990 PRO 2TB NVMe SSD',
                'name_ar' => 'سامسونج 990 PRO 2 تيرابايت NVMe SSD',
                'name_he' => 'Samsung 990 PRO 2TB NVMe SSD',
                'slug' => 'samsung-990-pro-2tb',
                'category' => 'storage',
                'brand' => 'samsung',
                'sku' => 'SAMS-990PRO-2TB',
                'price' => 899,
                'sale_price' => 799,
                'stock_quantity' => 25,
                'stock_status' => 'in_stock',
                'short_description_en' => '2TB, PCIe 4.0, 7,450 MB/s Read, 6,900 MB/s Write',
                'short_description_ar' => '2 تيرابايت، PCIe 4.0، قراءة 7,450 ميجابايت/ثانية، كتابة 6,900 ميجابايت/ثانية',
                'short_description_he' => '2TB, PCIe 4.0, קריאה 7,450 MB/s, כתיבה 6,900 MB/s',
                'description_en' => 'Lightning-fast storage with Samsung 990 PRO. Features blazing speeds up to 7,450 MB/s read and 6,900 MB/s write. PCIe 4.0 x4 interface with heatsink for optimal performance. Perfect for gaming and content creation.',
                'description_ar' => 'تخزين سريع البرق مع Samsung 990 PRO. يتميز بسرعات مذهلة تصل إلى 7,450 ميجابايت/ثانية للقراءة و 6,900 ميجابايت/ثانية للكتابة. واجهة PCIe 4.0 x4 مع مبدد حراري للأداء الأمثل. مثالي للألعاب وإنشاء المحتوى.',
                'description_he' => 'אחסון מהיר כברק עם Samsung 990 PRO. כולל מהירויות מסחררות עד 7,450 MB/s קריאה ו-6,900 MB/s כתיבה. ממשק PCIe 4.0 x4 עם גוף קירור לביצועים מיטביים. מושלם לגיימינג ויצירת תוכן.',
                'search_keywords' => 'ssd, nvme, samsung, storage, fast, pcie 4.0, gaming, 2tb, solid state',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://images.samsung.com/is/image/samsung/p6pim/levant/mz-v9p2t0bw/gallery/levant-990-pro-pcie-4-0-nvme-m-2-ssd-2tb-mz-v9p2t0bw-536864084?$650_519_PNG$'
            ],

            // PROCESSORS
            [
                'name_en' => 'AMD Ryzen 9 7950X',
                'name_ar' => 'AMD رايزن 9 7950X',
                'name_he' => 'AMD Ryzen 9 7950X',
                'slug' => 'amd-ryzen-9-7950x',
                'category' => 'processors',
                'brand' => 'amd',
                'sku' => 'AMD-R9-7950X',
                'price' => 2999,
                'sale_price' => 2799,
                'stock_quantity' => 12,
                'stock_status' => 'in_stock',
                'short_description_en' => '16 Cores, 32 Threads, 5.7GHz Max Boost, AM5 Socket',
                'short_description_ar' => '16 نواة، 32 خيط، 5.7 جيجاهرتز بوست، مقبس AM5',
                'short_description_he' => '16 ליבות, 32 חוטים, 5.7GHz Max Boost, שקע AM5',
                'description_en' => 'Extreme performance with AMD Ryzen 9 7950X. 16 cores and 32 threads of processing power, up to 5.7GHz boost clock, and AMD 3D V-Cache technology. The ultimate CPU for gaming and content creation.',
                'description_ar' => 'أداء متطرف مع AMD Ryzen 9 7950X. 16 نواة و 32 خيطًا من قوة المعالجة، وساعة تعزيز تصل إلى 5.7 جيجاهرتز، وتقنية AMD 3D V-Cache. وحدة المعالجة المركزية النهائية للألعاب وإنشاء المحتوى.',
                'description_he' => 'ביצועים קיצוניים עם AMD Ryzen 9 7950X. 16 ליבות ו-32 חוטי עיבוד, שעון בוסט עד 5.7GHz וטכנולוגיית AMD 3D V-Cache. המעבד האולטימטיבי לגיימינג ויצירת תוכן.',
                'search_keywords' => 'processor, cpu, amd, ryzen 9, high-end, gaming, productivity, 16 core',
                'is_featured' => true,
                'is_new' => false,
                'image' => 'https://cdn.mos.cms.futurecdn.net/pXqcgdJrMyh6cULJUmWBd8.jpg'
            ],
            [
                'name_en' => 'Intel Core i9-13900K',
                'name_ar' => 'انتل كور i9-13900K',
                'name_he' => 'Intel Core i9-13900K',
                'slug' => 'intel-i9-13900k',
                'category' => 'processors',
                'brand' => 'intel',
                'sku' => 'INTEL-I9-13900K',
                'price' => 2799,
                'sale_price' => 2599,
                'stock_quantity' => 15,
                'stock_status' => 'in_stock',
                'short_description_en' => '24 Cores, 32 Threads, 5.8GHz Max Turbo, LGA1700',
                'short_description_ar' => '24 نواة، 32 خيط، 5.8 جيجاهرتز توربو، LGA1700',
                'short_description_he' => '24 ליבות, 32 חוטים, 5.8GHz Max Turbo, LGA1700',
                'description_en' => 'Unleash extreme performance with Intel Core i9-13900K. 24 cores (8P+16E), 32 threads, and up to 5.8GHz turbo frequency. Raptor Lake architecture delivers exceptional gaming and multitasking performance.',
                'description_ar' => 'أطلق العنان للأداء المتطرف مع Intel Core i9-13900K. 24 نواة (8P+16E)، و 32 خيطًا، وتردد توربو يصل إلى 5.8 جيجاهرتز. توفر بنية Raptor Lake أداءً استثنائيًا للألعاب والمهام المتعددة.',
                'description_he' => 'שחרר ביצועים קיצוניים עם Intel Core i9-13900K. 24 ליבות (8P+16E), 32 חוטים ותדר טורבו עד 5.8GHz. ארכיטקטורת Raptor Lake מספקת ביצועי גיימינג ומולטי-טאסקינג יוצאי דופן.',
                'search_keywords' => 'processor, cpu, intel, i9, gaming, high-end, raptor lake, performance',
                'is_featured' => true,
                'is_new' => true,
                'image' => 'https://www.intel.com/content/dam/www/central-libraries/us/en/images/2022-09/13th-gen-core-i9-badge-rwd.png.rendition.intel.web.480.270.png'
            ],
        ];

        // Create products
        foreach ($products as $productData) {
            $category = $categoryMap->get($productData['category']);
            $brand = $brandMap->get($productData['brand']);

            if (!$category || !$brand) {
                echo "Skipping {$productData['name_en']} - category or brand not found\n";
                continue;
            }

            $product = Product::create([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name_en' => $productData['name_en'],
                'name_ar' => $productData['name_ar'],
                'slug' => $productData['slug'],
                'sku' => $productData['sku'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'stock_quantity' => $productData['stock_quantity'],
                'stock_status' => $productData['stock_status'],
                'short_description_en' => $productData['short_description_en'],
                'short_description_ar' => $productData['short_description_ar'],
                'description_en' => $productData['description_en'],
                'description_ar' => $productData['description_ar'],
                'meta_keywords' => $productData['search_keywords'],
                'is_featured' => $productData['is_featured'],
                'is_new' => $productData['is_new'],
                'is_active' => true,
                'views_count' => rand(100, 5000),
                'main_image' => $productData['image'] ?? null,
            ]);

            // Add main product image
            if (isset($productData['image'])) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $productData['image'],
                    'is_primary' => true,
                    'order' => 0,
                ]);
            }

            echo "✓ Created: {$productData['name_en']}\n";
        }

        echo "\n✅ Successfully created " . count($products) . " products with quality metadata!\n";
    }
}

