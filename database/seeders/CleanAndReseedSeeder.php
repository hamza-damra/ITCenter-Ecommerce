<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CleanAndReseedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables
        $this->command->info('🗑️ حذف البيانات القديمة...');
        ProductImage::truncate();
        Product::truncate();
        Category::truncate();
        Brand::truncate();
        Order::truncate();
        OrderItem::truncate();
        CartItem::truncate();
        Review::truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ تم حذف البيانات بنجاح');

        // Seed brands first
        $this->seedBrands();
        
        // Seed categories with transparent images
        $this->seedCategories();
        
        // Seed products with transparent images
        $this->seedProducts();

        $this->command->info('✅ تم إضافة البيانات الجديدة بنجاح');
    }

    private function seedBrands(): void
    {
        $this->command->info('📦 إضافة العلامات التجارية...');

        $brands = [
            [
                'name_en' => 'Apple',
                'name_ar' => 'آبل',
                'name_he' => 'אפל',
                'slug' => 'apple',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/apple-14.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'Samsung',
                'name_ar' => 'سامسونج',
                'name_he' => 'סמסונג',
                'slug' => 'samsung',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/samsung-8.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'Dell',
                'name_ar' => 'ديل',
                'name_he' => 'דל',
                'slug' => 'dell',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/dell-2.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'HP',
                'name_ar' => 'اتش بي',
                'name_he' => 'HP',
                'slug' => 'hp',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/hp-2.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'Lenovo',
                'name_ar' => 'لينوفو',
                'name_he' => 'לנובו',
                'slug' => 'lenovo',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/lenovo-2.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'ASUS',
                'name_ar' => 'أسوس',
                'name_he' => 'אסוס',
                'slug' => 'asus',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/asus-1.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'Microsoft',
                'name_ar' => 'مايكروسوفت',
                'name_he' => 'מיקרוסופט',
                'slug' => 'microsoft',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/microsoft-5.svg',
                'is_active' => true,
            ],
            [
                'name_en' => 'Logitech',
                'name_ar' => 'لوجيتك',
                'name_he' => 'לוג\'יטק',
                'slug' => 'logitech',
                'logo' => 'https://cdn.worldvectorlogo.com/logos/logitech-2.svg',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }

    private function seedCategories(): void
    {
        $this->command->info('📁 إضافة الفئات...');

        $categories = [
            [
                'name_en' => 'Laptops',
                'name_ar' => 'أجهزة لابتوب',
                'name_he' => 'מחשבים ניידים',
                'slug' => 'laptops',
                'description_en' => 'High-performance laptops for work and gaming',
                'description_ar' => 'أجهزة لابتوب عالية الأداء للعمل والألعاب',
                'description_he' => 'מחשבים ניידים בעלי ביצועים גבוהים לעבודה ומשחקים',
                'image' => 'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name_en' => 'Desktop Computers',
                'name_ar' => 'أجهزة كمبيوتر مكتبية',
                'name_he' => 'מחשבים שולחניים',
                'slug' => 'desktop-computers',
                'description_en' => 'Powerful desktop computers for professionals',
                'description_ar' => 'أجهزة كمبيوتر مكتبية قوية للمحترفين',
                'description_he' => 'מחשבים שולחניים עוצמתיים למקצוענים',
                'image' => 'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name_en' => 'Monitors',
                'name_ar' => 'شاشات العرض',
                'name_he' => 'מסכים',
                'slug' => 'monitors',
                'description_en' => 'High-resolution monitors for better viewing experience',
                'description_ar' => 'شاشات عالية الدقة لتجربة مشاهدة أفضل',
                'description_he' => 'מסכים ברזולוציה גבוהה לחוויית צפייה טובה יותר',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2888/2888704.png',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name_en' => 'Keyboards & Mice',
                'name_ar' => 'لوحات المفاتيح والفأرة',
                'name_he' => 'מקלדות ועכברים',
                'slug' => 'keyboards-mice',
                'description_en' => 'Premium keyboards and mice for productivity',
                'description_ar' => 'لوحات مفاتيح وفأرة مميزة للإنتاجية',
                'description_he' => 'מקלדות ועכברים איכותיים לפרודוקטיביות',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2972/2972351.png',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name_en' => 'Storage Devices',
                'name_ar' => 'أجهزة التخزين',
                'name_he' => 'התקני אחסון',
                'slug' => 'storage-devices',
                'description_en' => 'External and internal storage solutions',
                'description_ar' => 'حلول التخزين الداخلية والخارجية',
                'description_he' => 'פתרונות אחסון פנימיים וחיצוניים',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2906/2906206.png',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name_en' => 'Printers & Scanners',
                'name_ar' => 'الطابعات والماسحات',
                'name_he' => 'מדפסות וסורקים',
                'slug' => 'printers-scanners',
                'description_en' => 'Professional printing and scanning solutions',
                'description_ar' => 'حلول طباعة ومسح احترافية',
                'description_he' => 'פתרונות הדפסה וסריקה מקצועיים',
                'image' => 'https://cdn-icons-png.flaticon.com/512/3659/3659898.png',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name_en' => 'Networking',
                'name_ar' => 'الشبكات',
                'name_he' => 'ציוד רשת',
                'slug' => 'networking',
                'description_en' => 'Routers, switches, and network accessories',
                'description_ar' => 'الراوترات والسويتشات وملحقات الشبكات',
                'description_he' => 'נתבים, מתגים ואביזרי רשת',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2906/2906274.png',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name_en' => 'Audio & Headphones',
                'name_ar' => 'السماعات وسماعات الرأس',
                'name_he' => 'אודיו ואוזניות',
                'slug' => 'audio-headphones',
                'description_en' => 'Premium audio equipment and headphones',
                'description_ar' => 'معدات صوتية وسماعات رأس مميزة',
                'description_he' => 'ציוד אודיו ואוזניות איכותיות',
                'image' => 'https://cdn-icons-png.flaticon.com/512/2599/2599068.png',
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

    private function seedProducts(): void
    {
        $this->command->info('🎁 إضافة المنتجات...');

        $categories = Category::all();
        $brands = Brand::all();

        $products = [
            // Laptops
            [
                'name_en' => 'MacBook Pro 16" M3 Max',
                'name_ar' => 'ماك بوك برو 16 بوصة M3 ماكس',
                'name_he' => 'MacBook Pro 16 אינץ\' M3 Max',
                'slug' => 'macbook-pro-16-m3-max',
                'description_en' => 'The most powerful MacBook Pro ever. With M3 Max chip, 36GB RAM, and 1TB SSD.',
                'description_ar' => 'أقوى ماك بوك برو على الإطلاق. مع معالج M3 ماكس، ذاكرة 36 جيجا، وهارد 1 تيرا SSD.',
                'description_he' => 'ה-MacBook Pro החזק ביותר אי פעם. עם שבב M3 Max, זיכרון 36GB ו-SSD 1TB.',
                'price' => 3999.99,
                'sale_price' => 3699.99,
                'sku' => 'LAPTOP-MBP16-M3MAX',
                'stock_quantity' => 15,
                'category' => 'laptops',
                'brand' => 'apple',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2721/2721297.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2721/2721297.png',
                    'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                ],
                'is_featured' => true,
            ],
            [
                'name_en' => 'Dell XPS 15 Developer Edition',
                'name_ar' => 'ديل XPS 15 نسخة المطورين',
                'name_he' => 'Dell XPS 15 גרסת מפתחים',
                'slug' => 'dell-xps-15-developer',
                'description_en' => 'Ultimate laptop for developers. Intel i9, 32GB RAM, 1TB SSD, NVIDIA RTX 4060.',
                'description_ar' => 'اللابتوب المثالي للمطورين. معالج إنتل i9، ذاكرة 32 جيجا، هارد 1 تيرا SSD، كارت شاشة NVIDIA RTX 4060.',
                'description_he' => 'המחשב הנייד האולטימטיבי למפתחים. Intel i9, זיכרון 32GB, SSD 1TB, NVIDIA RTX 4060.',
                'price' => 2799.99,
                'sku' => 'LAPTOP-DELLXPS15-DEV',
                'stock_quantity' => 25,
                'category' => 'laptops',
                'brand' => 'dell',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                    'https://cdn-icons-png.flaticon.com/512/2721/2721297.png',
                ],
                'is_featured' => true,
            ],
            [
                'name_en' => 'ASUS ROG Zephyrus G16',
                'name_ar' => 'أسوس ROG زيفيروس G16',
                'name_he' => 'ASUS ROG Zephyrus G16',
                'slug' => 'asus-rog-zephyrus-g16',
                'description_en' => 'Gaming laptop with Intel i9-14900HX, 32GB RAM, RTX 4080, 240Hz display.',
                'description_ar' => 'لابتوب ألعاب مع معالج Intel i9-14900HX، ذاكرة 32 جيجا، RTX 4080، شاشة 240Hz.',
                'description_he' => 'מחשב נייד לגיימינג עם Intel i9-14900HX, זיכרון 32GB, RTX 4080, מסך 240Hz.',
                'price' => 3299.99,
                'sale_price' => 2999.99,
                'sku' => 'LAPTOP-ASUS-ROGG16',
                'stock_quantity' => 12,
                'category' => 'laptops',
                'brand' => 'asus',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                ],
                'is_featured' => true,
            ],
            // Monitors
            [
                'name_en' => 'Samsung Odyssey OLED G9',
                'name_ar' => 'سامسونج أوديسي OLED G9',
                'name_he' => 'Samsung Odyssey OLED G9',
                'slug' => 'samsung-odyssey-oled-g9',
                'description_en' => '49" curved OLED gaming monitor, 5120x1440, 240Hz, 0.03ms response time.',
                'description_ar' => 'شاشة ألعاب منحنية OLED 49 بوصة، دقة 5120x1440، 240Hz، زمن استجابة 0.03ms.',
                'description_he' => 'מסך גיימינג OLED מעוקל 49 אינץ\', רזולוציה 5120x1440, 240Hz, זמן תגובה 0.03ms.',
                'price' => 1899.99,
                'sale_price' => 1699.99,
                'sku' => 'MONITOR-SAMSUNG-G9',
                'stock_quantity' => 8,
                'category' => 'monitors',
                'brand' => 'samsung',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2888/2888704.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2888/2888704.png',
                ],
                'is_featured' => true,
            ],
            [
                'name_en' => 'Dell UltraSharp 32" 4K',
                'name_ar' => 'ديل ألترا شارب 32 بوصة 4K',
                'name_he' => 'Dell UltraSharp 32 אינץ\' 4K',
                'slug' => 'dell-ultrasharp-32-4k',
                'description_en' => 'Professional 4K monitor with USB-C hub, 99% sRGB, HDR400.',
                'description_ar' => 'شاشة احترافية 4K مع USB-C hub، تغطية 99% sRGB، HDR400.',
                'description_he' => 'מסך מקצועי 4K עם USB-C hub, כיסוי 99% sRGB, HDR400.',
                'price' => 799.99,
                'sku' => 'MONITOR-DELL-US32',
                'stock_quantity' => 20,
                'category' => 'monitors',
                'brand' => 'dell',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2888/2888704.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2888/2888704.png',
                ],
            ],
            // Keyboards & Mice
            [
                'name_en' => 'Logitech MX Master 3S',
                'name_ar' => 'لوجيتك MX ماستر 3S',
                'name_he' => 'Logitech MX Master 3S',
                'slug' => 'logitech-mx-master-3s',
                'description_en' => 'Premium wireless mouse with 8K DPI, quiet clicks, USB-C charging.',
                'description_ar' => 'فأرة لاسلكية مميزة بدقة 8K DPI، نقرات هادئة، شحن USB-C.',
                'description_he' => 'עכבר אלחוטי פרימיום עם רזולוציה 8K DPI, לחיצות שקטות, טעינה USB-C.',
                'price' => 99.99,
                'sale_price' => 89.99,
                'sku' => 'MOUSE-LOGI-MX3S',
                'stock_quantity' => 50,
                'category' => 'keyboards-mice',
                'brand' => 'logitech',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2972/2972351.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2972/2972351.png',
                ],
                'is_featured' => true,
            ],
            [
                'name_en' => 'Logitech MX Keys Mechanical',
                'name_ar' => 'لوجيتك MX كييز ميكانيكال',
                'name_he' => 'Logitech MX Keys Mechanical',
                'slug' => 'logitech-mx-keys-mechanical',
                'description_en' => 'Wireless mechanical keyboard with smart backlighting and multi-device support.',
                'description_ar' => 'لوحة مفاتيح ميكانيكية لاسلكية مع إضاءة خلفية ذكية ودعم أجهزة متعددة.',
                'description_he' => 'מקלדת מכנית אלחוטית עם תאורת רקע חכמה ותמיכה במספר מכשירים.',
                'price' => 149.99,
                'sku' => 'KEYBOARD-LOGI-MXKEYS',
                'stock_quantity' => 35,
                'category' => 'keyboards-mice',
                'brand' => 'logitech',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2972/2972351.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2972/2972351.png',
                ],
            ],
            // Storage
            [
                'name_en' => 'Samsung T9 Portable SSD 2TB',
                'name_ar' => 'سامسونج T9 SSD محمول 2 تيرا',
                'name_he' => 'Samsung T9 SSD נייד 2TB',
                'slug' => 'samsung-t9-ssd-2tb',
                'description_en' => 'Ultra-fast portable SSD with USB 3.2 Gen 2x2, up to 2000MB/s read speed.',
                'description_ar' => 'هارد SSD محمول فائق السرعة مع USB 3.2 Gen 2x2، سرعة قراءة تصل إلى 2000MB/s.',
                'description_he' => 'SSD נייד מהיר במיוחד עם USB 3.2 Gen 2x2, מהירות קריאה עד 2000MB/s.',
                'price' => 199.99,
                'sale_price' => 179.99,
                'sku' => 'STORAGE-SAMSUNG-T9-2TB',
                'stock_quantity' => 40,
                'category' => 'storage-devices',
                'brand' => 'samsung',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2906/2906206.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2906/2906206.png',
                ],
                'is_featured' => true,
            ],
            // Printers
            [
                'name_en' => 'HP LaserJet Pro M404n',
                'name_ar' => 'اتش بي ليزر جيت برو M404n',
                'name_he' => 'HP LaserJet Pro M404n',
                'slug' => 'hp-laserjet-pro-m404n',
                'description_en' => 'Monochrome laser printer, 38 ppm, automatic two-sided printing.',
                'description_ar' => 'طابعة ليزر أحادية اللون، 38 صفحة في الدقيقة، طباعة تلقائية على الوجهين.',
                'description_he' => 'מדפסת לייזר שחור לבן, 38 עמודים לדקה, הדפסה דו-צדדית אוטומטית.',
                'price' => 299.99,
                'sku' => 'PRINTER-HP-M404N',
                'stock_quantity' => 18,
                'category' => 'printers-scanners',
                'brand' => 'hp',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/3659/3659898.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/3659/3659898.png',
                ],
            ],
            // Networking
            [
                'name_en' => 'ASUS RT-AX88U Pro WiFi 6 Router',
                'name_ar' => 'أسوس RT-AX88U برو راوتر WiFi 6',
                'name_he' => 'ASUS RT-AX88U Pro נתב WiFi 6',
                'slug' => 'asus-rt-ax88u-pro',
                'description_en' => 'High-performance WiFi 6 router with 8 LAN ports, AiMesh support.',
                'description_ar' => 'راوتر WiFi 6 عالي الأداء مع 8 منافذ LAN، دعم AiMesh.',
                'description_he' => 'נתב WiFi 6 בעל ביצועים גבוהים עם 8 יציאות LAN, תמיכה ב-AiMesh.',
                'price' => 349.99,
                'sale_price' => 299.99,
                'sku' => 'ROUTER-ASUS-AX88U',
                'stock_quantity' => 22,
                'category' => 'networking',
                'brand' => 'asus',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2906/2906274.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2906/2906274.png',
                ],
            ],
            // Audio
            [
                'name_en' => 'Logitech G Pro X 2 Wireless Gaming Headset',
                'name_ar' => 'لوجيتك G Pro X 2 سماعة ألعاب لاسلكية',
                'name_he' => 'Logitech G Pro X 2 אוזניות גיימינג אלחוטיות',
                'slug' => 'logitech-g-pro-x2-wireless',
                'description_en' => 'Professional wireless gaming headset with 50mm drivers, DTS:X 2.0.',
                'description_ar' => 'سماعة ألعاب لاسلكية احترافية مع مكبرات صوت 50mm، تقنية DTS:X 2.0.',
                'description_he' => 'אוזניות גיימינג אלחוטיות מקצועיות עם רמקולים 50mm, טכנולוגיית DTS:X 2.0.',
                'price' => 249.99,
                'sale_price' => 219.99,
                'sku' => 'HEADSET-LOGI-PROX2',
                'stock_quantity' => 30,
                'category' => 'audio-headphones',
                'brand' => 'logitech',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/2599/2599068.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/2599/2599068.png',
                ],
                'is_featured' => true,
            ],
            // Desktop Computers
            [
                'name_en' => 'HP Z2 Tower G9 Workstation',
                'name_ar' => 'اتش بي Z2 تاور G9 محطة عمل',
                'name_he' => 'HP Z2 Tower G9 תחנת עבודה',
                'slug' => 'hp-z2-tower-g9',
                'description_en' => 'Professional workstation: Intel Xeon W-1390P, 32GB ECC RAM, NVIDIA RTX A4000.',
                'description_ar' => 'محطة عمل احترافية: معالج Intel Xeon W-1390P، ذاكرة 32GB ECC، كارت شاشة NVIDIA RTX A4000.',
                'description_he' => 'תחנת עבודה מקצועית: Intel Xeon W-1390P, זיכרון 32GB ECC, NVIDIA RTX A4000.',
                'price' => 3499.99,
                'sku' => 'DESKTOP-HP-Z2G9',
                'stock_quantity' => 10,
                'category' => 'desktop-computers',
                'brand' => 'hp',
                'main_image' => 'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                'images' => [
                    'https://cdn-icons-png.flaticon.com/512/3474/3474360.png',
                ],
            ],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('slug', $productData['category']);
            $brand = $brands->firstWhere('slug', $productData['brand']);

            if (!$category || !$brand) {
                continue;
            }

            $product = Product::create([
                'name_en' => $productData['name_en'],
                'name_ar' => $productData['name_ar'],
                'name_he' => $productData['name_he'],
                'slug' => $productData['slug'],
                'description_en' => $productData['description_en'],
                'description_ar' => $productData['description_ar'],
                'description_he' => $productData['description_he'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'sku' => $productData['sku'],
                'stock_quantity' => $productData['stock_quantity'],
                'min_stock_quantity' => 5,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'main_image' => $productData['main_image'],
                'is_active' => true,
                'is_featured' => $productData['is_featured'] ?? false,
                'meta_title' => $productData['name_en'],
                'meta_description' => $productData['description_en'],
            ]);

            // Add product images
            foreach ($productData['images'] as $index => $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageUrl,
                    'order' => $index,
                    'is_primary' => $index === 0,
                    'alt_text' => $product->name_en . ' - Image ' . ($index + 1),
                ]);
            }
        }
    }
}
