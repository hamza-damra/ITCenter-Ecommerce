<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create/get necessary categories and brands
        $this->createCategoriesAndBrands();

        // Create all 7 products
        $this->createProducts();
    }

    private function createCategoriesAndBrands()
    {
        // Create Categories
        $categories = [
            [
                'name_en' => 'Graphics Cards',
                'name_ar' => 'كروت الشاشة',
                'name_he' => 'כרטיסי מסך',
                'slug' => 'graphics-cards',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name_en' => 'PC Cases',
                'name_ar' => 'صناديق الكمبيوتر',
                'name_he' => 'ארונות מחשב',
                'slug' => 'pc-cases',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name_en' => 'Keyboards',
                'name_ar' => 'لوحات المفاتيح',
                'name_he' => 'מקלדות',
                'slug' => 'keyboards',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name_en' => 'Mice',
                'name_ar' => 'الفئران',
                'name_he' => 'עכברים',
                'slug' => 'mice',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name_en' => 'Power Supplies',
                'name_ar' => 'وحدات الطاقة',
                'name_he' => 'ספקי כוח',
                'slug' => 'power-supplies',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name_en' => 'Monitors',
                'name_ar' => 'الشاشات',
                'name_he' => 'מסכים',
                'slug' => 'monitors',
                'is_active' => true,
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            if (!Category::where('slug', $category['slug'])->exists()) {
                Category::create($category);
            }
        }

        // Create Brands
        $brands = [
            [
                'name' => 'MSI',
                'slug' => 'msi',
                'is_active' => true,
            ],
            [
                'name' => 'Antec',
                'slug' => 'antec',
                'is_active' => true,
            ],
            [
                'name' => 'Razer',
                'slug' => 'razer',
                'is_active' => true,
            ],
            [
                'name' => 'Glorious',
                'slug' => 'glorious',
                'is_active' => true,
            ],
            [
                'name' => 'ASUS',
                'slug' => 'asus',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            if (!Brand::where('slug', $brand['slug'])->exists()) {
                Brand::create($brand);
            }
        }
    }

    private function createProducts()
    {
        $products = [
            // Product 1: MSI RTX 5060 Ti
            [
                'name_en' => 'MSI RTX 5060 Ti Gaming Graphics Card',
                'name_ar' => 'كرت شاشة MSI RTX 5060 Ti للألعاب',
                'name_he' => 'כרטיס מסך MSI RTX 5060 Ti לגיימינג',
                'slug' => 'msi-rtx-5060-ti-gaming-graphics-card',
                'sku' => 'MSI-RTX-5060TI-001',
                'short_description_en' => 'Powerful MSI RTX 5060 Ti graphics card designed for high-performance gaming and creative workloads, offering smooth FPS and efficient cooling',
                'short_description_ar' => 'كرت شاشة MSI RTX 5060 Ti قوي مخصص للألعاب والأعمال الاحترافية مع أداء عالي وثبات رائع ونظام تبريد فعّال.',
                'short_description_he' => 'כרטיס מסך MSI RTX 5060 Ti חזק לגיימינג ועבודה יצירתית עם ביצועים גבוהים וקירור יעיל.',
                'description_en' => "Experience next-generation gaming power with the MSI RTX 5060 Ti. Engineered for smooth high-FPS gameplay, advanced ray-tracing, and AI-enhanced graphics performance.\n\nFeatures a robust cooling system, durable build quality, and optimized power efficiency, making it ideal for gamers, creators, and professionals seeking top-tier performance.\n\nKey Features:\n• High-performance NVIDIA architecture\n• Smooth FPS in modern AAA titles\n• Advanced cooling system by MSI\n• Ray-Tracing & AI-enhanced performance\n• Durable premium design",
                'description_ar' => "عِش قوة الألعاب من الجيل الجديد مع كرت الشاشة MSI RTX 5060 Ti. مُصمّم لتقديم أداء عالي في الألعاب الحديثة، ودعم تتبع الأشعة، وتقنيات الذكاء الاصطناعي لتحسين جودة الرسوم.\n\nمزود بنظام تبريد متطور وجودة تصنيع ممتازة لضمان ثبات وأداء قوي لفترات طويلة، مما يجعله خيارًا مثاليًا للاعبين وصنّاع المحتوى والمحترفين.\n\nمميزات:\n• أداء قوي مع أحدث الألعاب\n• دعم تتبع الأشعة والذكاء الاصطناعي\n• تبريد احترافي من MSI\n• تصميم قوي وجودة عالية\n• كفاءة ممتازة في استهلاك الطاقة",
                'description_he' => "שדרוג עוצמתי לגיימרים וליוצרים עם MSI RTX 5060 Ti. ביצועים גבוהים במשחקים מודרניים, תמיכה ב-Ray Tracing וטכנולוגיות AI לשיפור גרפיקה.\n\nמערכת קירור מתקדמת ובנייה איכותית מבית MSI המבטיחה יציבות וביצועים לאורך זמן.",
                'price' => 3499.00,
                'sale_price' => 3299.00,
                'stock_quantity' => 15,
                'main_image' => 'https://asset.msi.com/resize/image/global/product/product_17446955973acbf7143d82495e15d623436e02a7d5.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                'category_slug' => 'graphics-cards',
                'brand_slug' => 'msi',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => false,
                'stock_status' => 'in_stock',
                'warranty' => '3 years',
                'images' => [
                    'https://asset.msi.com/resize/image/global/product/product_17446955973acbf7143d82495e15d623436e02a7d5.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_17446882595412140dce3d3c7d296b3c4decc85a7d.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_17446882610ad83965b1f1a5391f9c59ef230358b5.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_17446882617eedc2773a238e03e9b7548dd9d2ea42.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_17446882634889f5f823c26f28e073f3dd9b713e5d.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://storage-asset.msi.com//global/picture/gallery/product_173864963806c5d3f20d4a2ea3a3b78a0a9e470967.png',
                ],
            ],

            // Product 2: Antec C8 PC Case
            [
                'name_en' => 'Antec C8 Full-Tower E-ATX PC Case',
                'name_ar' => 'صندوق حاسوب Antec C8 بحجم كامل',
                'name_he' => 'ארון מחשב Antec C8 מלא',
                'slug' => 'antec-c8-full-tower-pc-case',
                'sku' => 'ANTEC-C8-001',
                'short_description_en' => 'Premium full-tower case with dual-chamber design, toolless assembly, seamless tempered glass panels, and 360mm radiator support',
                'short_description_ar' => 'صندوق كمبيوتر فاخر بتصميم مزدوج الحجرة، تجميع بدون أدوات، ألواح زجاجية شفافة سلسة، ودعم مشتت حراري 360 ملم',
                'short_description_he' => 'ארון מחשב פרימיום עם עיצוב דו-תאי, הרכבה ללא כלים, פאנלי זכוכית מחוסמת חלקים ותמיכה ברדיאטור 360 מ"מ',
                'description_en' => "The Antec C8 is a premium full-tower case featuring a sophisticated dual-chamber design and seamless tempered glass panels for an unobstructed view of your build.\n\nKey Features:\n• Dual-chamber design with vertical cooling compatibility\n• Supports E-ATX, ATX, M-ATX, ITX motherboards\n• GPU support up to 370mm, CPU coolers up to 175mm\n• 360mm radiator support (front), 280mm (top)\n• Tool-free assembly with quick-release panels\n• Type-C front panel connectivity\n• RTX 40 series compatible\n• 8 expansion slots\n• 2x internal 3.5\" bays, 4x 2.5\" bays\n• Supports up to 10 fans\n• 4mm non-drilled tempered glass panels",
                'description_ar' => "صندوق Antec C8 هو صندوق كمبيوتر فاخر بحجم كامل يتميز بتصميم مزدوج الحجرة متطور وألواح زجاجية شفافة سلسة لعرض مكونات جهازك بوضوح.\n\nالميزات الرئيسية:\n• تصميم مزدوج الحجرة مع توافق تبريد عمودي\n• يدعم اللوحات الأم E-ATX و ATX و M-ATX و ITX\n• دعم كروت الشاشة حتى 370 ملم، مبردات المعالج حتى 175 ملم\n• دعم مشتت حراري 360 ملم (أمامي)، 280 ملم (علوي)\n• تجميع بدون أدوات مع ألواح سريعة الفك\n• منفذ Type-C في اللوحة الأمامية\n• متوافق مع سلسلة RTX 40\n• 8 فتحات توسع\n• 2 فتحة داخلية 3.5 بوصة، 4 فتحات 2.5 بوصة\n• يدعم حتى 10 مراوح\n• ألواح زجاجية مقسّاة بسمك 4 ملم",
                'description_he' => "ארון Antec C8 הוא ארון מחשב מלא פרימיום עם עיצוב דו-תאי מתוחכם ופאנלי זכוכית מחוסמת חלקים לתצוגה בלתי מוגבלת של המערכת שלך.\n\nתכונות עיקריות:\n• עיצוב דו-תאי עם תאימות קירור אנכי\n• תומך בלוחות אם E-ATX, ATX, M-ATX, ITX\n• תמיכה בכרטיס מסך עד 370 מ\"מ, מקרר CPU עד 175 מ\"מ\n• תמיכה ברדיאטור 360 מ\"מ (קדמי), 280 מ\"מ (עליון)\n• הרכבה ללא כלים עם פאנלים להסרה מהירה\n• חיבור Type-C בפאנל הקדמי\n• תואם לסדרת RTX 40\n• 8 חריצי הרחבה",
                'price' => 599.00,
                'sale_price' => 549.00,
                'stock_quantity' => 10,
                'main_image' => 'https://m.media-amazon.com/images/I/71-2omOmgVL._AC_SL1500_.jpg',
                'category_slug' => 'pc-cases',
                'brand_slug' => 'antec',
                'is_active' => true,
                'is_featured' => false,
                'is_new' => true,
                'is_bestseller' => false,
                'stock_status' => 'in_stock',
                'warranty' => '2 years',
                'images' => [
                    'https://m.media-amazon.com/images/I/71-2omOmgVL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/715cpGeIYNL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71MW2SOpgqL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71Ft-x6TlGL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/9136fB4YmJL._AC_SL1500_.jpg',
                ],
            ],

            // Product 3: Razer Huntsman Mini
            [
                'name_en' => 'Razer Huntsman Mini 60% Gaming Keyboard',
                'name_ar' => 'لوحة مفاتيح Razer Huntsman Mini 60% للألعاب',
                'name_he' => 'מקלדת גיימינג Razer Huntsman Mini 60%',
                'slug' => 'razer-huntsman-mini-60-gaming-keyboard',
                'sku' => 'RAZER-HUNT-MINI-001',
                'short_description_en' => 'Compact 60% mechanical gaming keyboard with clicky optical switches, Chroma RGB lighting, PBT keycaps, and rapid trigger mode',
                'short_description_ar' => 'لوحة مفاتيح ميكانيكية مدمجة 60% مع مفاتيح ضوئية صوتية، إضاءة Chroma RGB، أغطية مفاتيح PBT، ووضع الزناد السريع',
                'short_description_he' => 'מקלדת גיימינג מכנית קומפקטית 60% עם מתגים אופטיים קליקיים, תאורת Chroma RGB, מכסי מקשים PBT ומצב הדק מהיר',
                'description_en' => "The Razer Huntsman Mini is a compact 60% gaming keyboard featuring cutting-edge optical switches for lightning-fast performance.\n\nKey Features:\n• Razer Clicky Optical Switches (1.5mm actuation, 45g force)\n• 60% form factor for minimalist setups\n• Chroma RGB with 16.8 million colors\n• Doubleshot PBT keycaps with textured finish\n• Snap Tap mode for responsive inputs\n• Rapid Trigger mode for faster actuation\n• Onboard memory for 5 profiles\n• Fully programmable macros via Razer Hypershift\n• USB Type-C non-proprietary cable\n• Aluminum top frame for durability\n• 30% shorter actuation than traditional switches",
                'description_ar' => "Razer Huntsman Mini هي لوحة مفاتيح ألعاب مدمجة 60% تتميز بمفاتيح ضوئية متطورة لأداء فائق السرعة.\n\nالميزات الرئيسية:\n• مفاتيح Razer الضوئية الصوتية (تفعيل 1.5 ملم، قوة 45 جرام)\n• تصميم 60% للإعدادات البسيطة\n• إضاءة Chroma RGB بـ 16.8 مليون لون\n• أغطية مفاتيح PBT مزدوجة التشكيل مع لمسة نهائية محكمة\n• وضع Snap Tap للإدخالات سريعة الاستجابة\n• وضع Rapid Trigger للتفعيل الأسرع\n• ذاكرة داخلية لـ 5 ملفات تعريف\n• ماكرو قابل للبرمجة بالكامل عبر Razer Hypershift\n• كابل USB Type-C غير احتكاري\n• إطار علوي من الألومنيوم للمتانة\n• تفعيل أقصر بنسبة 30% من المفاتيح التقليدية",
                'description_he' => "ה-Razer Huntsman Mini היא מקלדת גיימינג קומפקטית 60% המציגה מתגים אופטיים מתקדמים לביצועים מהירים במיוחד.\n\nתכונות עיקריות:\n• מתגים אופטיים קליקיים של Razer (הפעלה 1.5 מ\"מ, כוח 45 גרם)\n• פורמט 60% להתקנות מינימליסטיות\n• Chroma RGB עם 16.8 מיליון צבעים\n• מכסי מקשים PBT עם גימור מרקם\n• מצב Snap Tap לקלט רספונסיבי\n• מצב Rapid Trigger להפעלה מהירה יותר\n• זיכרון פנימי ל-5 פרופילים\n• מאקרו הניתן לתכנות מלא דרך Razer Hypershift",
                'price' => 499.00,
                'sale_price' => 449.00,
                'stock_quantity' => 20,
                'main_image' => 'https://m.media-amazon.com/images/I/618etkLUt9L._AC_SL1500_.jpg',
                'category_slug' => 'keyboards',
                'brand_slug' => 'razer',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => false,
                'is_bestseller' => true,
                'stock_status' => 'in_stock',
                'warranty' => '1 year',
                'images' => [
                    'https://m.media-amazon.com/images/I/618etkLUt9L._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81gMc6FyI1L._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71Xp0M9CfXL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71OHv4AWRYL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71EXApeyE2L._AC_SL1500_.jpg',
                ],
            ],

            // Product 4: Razer DeathAdder V3 HyperSpeed
            [
                'name_en' => 'Razer DeathAdder V3 HyperSpeed Wireless Gaming Mouse',
                'name_ar' => 'فأرة ألعاب لاسلكية Razer DeathAdder V3 HyperSpeed',
                'name_he' => 'עכבר גיימינג אלחוטי Razer DeathAdder V3 HyperSpeed',
                'slug' => 'razer-deathadder-v3-hyperspeed-wireless-mouse',
                'sku' => 'RAZER-DA-V3-HS-001',
                'short_description_en' => '55g ultralight wireless gaming mouse with Focus X 26K DPI sensor, 100-hour battery life, and Gen-3 optical switches',
                'short_description_ar' => 'فأرة ألعاب لاسلكية خفيفة للغاية 55 جرام مع مستشعر Focus X 26K DPI، عمر بطارية 100 ساعة، ومفاتيح ضوئية من الجيل الثالث',
                'short_description_he' => 'עכבר גיימינג אלחוטי קל במיוחד 55 גרם עם חיישן Focus X 26K DPI, סוללה ל-100 שעות ומתגים אופטיים דור 3',
                'description_en' => "The Razer DeathAdder V3 HyperSpeed delivers ultralight wireless performance with cutting-edge sensor technology and exceptional battery life.\n\nKey Features:\n• 55g ultralight weight\n• Focus X 26K DPI optical sensor (99.6% accuracy)\n• Optical Mouse Switches Gen-3 (90M click lifecycle)\n• Up to 100 hours battery life\n• USB Type-C charging\n• HyperSpeed Wireless (8000Hz polling with dongle)\n• Ergonomic right-handed design\n• 8 programmable buttons\n• 0.2ms actuation with no debounce delay\n• Smooth-touch finish\n• Compatible with Razer Synapse",
                'description_ar' => "توفر فأرة Razer DeathAdder V3 HyperSpeed أداءً لاسلكيًا خفيفًا للغاية مع تقنية مستشعر متطورة وعمر بطارية استثنائي.\n\nالميزات الرئيسية:\n• وزن خفيف للغاية 55 جرام\n• مستشعر ضوئي Focus X 26K DPI (دقة 99.6%)\n• مفاتيح ضوئية من الجيل الثالث (عمر 90 مليون نقرة)\n• عمر بطارية حتى 100 ساعة\n• شحن USB Type-C\n• لاسلكي HyperSpeed (معدل استطلاع 8000 هرتز مع الدونجل)\n• تصميم مريح لليد اليمنى\n• 8 أزرار قابلة للبرمجة\n• تفعيل 0.2 ملي ثانية بدون تأخير\n• لمسة نهائية ناعمة\n• متوافق مع Razer Synapse",
                'description_he' => "ה-Razer DeathAdder V3 HyperSpeed מספק ביצועים אלחוטיים קלים במיוחד עם טכנולוגיית חיישן מתקדמת וחיי סוללה יוצאי דופן.\n\nתכונות עיקריות:\n• משקל קל במיוחד 55 גרם\n• חיישן אופטי Focus X 26K DPI (דיוק 99.6%)\n• מתגים אופטיים דור 3 (90 מיליון לחיצות)\n• עד 100 שעות חיי סוללה\n• טעינת USB Type-C\n• HyperSpeed אלחוטי (סקר 8000 הרץ עם דונגל)\n• עיצוב ארגונומי לימין\n• 8 כפתורים הניתנים לתכנות\n• הפעלה 0.2 מילישניות ללא עיכוב",
                'price' => 399.00,
                'sale_price' => 349.00,
                'stock_quantity' => 25,
                'main_image' => 'https://m.media-amazon.com/images/I/71SfcN143LL._AC_SL1500_.jpg',
                'category_slug' => 'mice',
                'brand_slug' => 'razer',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => false,
                'is_bestseller' => true,
                'stock_status' => 'in_stock',
                'warranty' => '2 years',
                'images' => [
                    'https://m.media-amazon.com/images/I/71SfcN143LL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/7173yUBdWWL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/61rz+52tLTL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/71QWN1Q9jkL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81vN0jQYhBL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81cjeNNO9lL._AC_SL1500_.jpg',
                ],
            ],

            // Product 5: Glorious Model D Wireless
            [
                'name_en' => 'Glorious Model D Wireless Gaming Mouse',
                'name_ar' => 'فأرة ألعاب لاسلكية Glorious Model D',
                'name_he' => 'עכבר גיימינג אלחוטי Glorious Model D',
                'slug' => 'glorious-model-d-wireless-gaming-mouse',
                'sku' => 'GLORIOUS-MD-WL-001',
                'short_description_en' => '69g superlight wireless gaming mouse with BAMF sensor, 71-hour battery, RGB lighting, and ergonomic design',
                'short_description_ar' => 'فأرة ألعاب لاسلكية فائقة الخفة 69 جرام مع مستشعر BAMF، بطارية 71 ساعة، إضاءة RGB، وتصميم مريح',
                'short_description_he' => 'עכבר גיימינג אלחוטי קל במיוחד 69 גרם עם חיישן BAMF, סוללה ל-71 שעות, תאורת RGB ועיצוב ארגונומי',
                'description_en' => "The Glorious Model D Wireless combines lightweight performance with exceptional battery life and precision tracking.\n\nKey Features:\n• 69g lightweight design\n• BAMF sensor (19,000 max DPI, 400 IPS)\n• 2.4GHz wireless (1ms latency)\n• Up to 71 hours battery (RGB off)\n• USB-C charging (play while charging)\n• 16.8M RGB colors\n• 6 customizable buttons\n• Ergonomic right-handed shape\n• Premium PTFE mouse feet\n• Glorious CORE software compatible\n• Ultra-flexible Ascended paracord cable\n• 1,000Hz polling rate",
                'description_ar' => "تجمع فأرة Glorious Model D اللاسلكية بين الأداء الخفيف وعمر البطارية الاستثنائي والتتبع الدقيق.\n\nالميزات الرئيسية:\n• تصميم خفيف 69 جرام\n• مستشعر BAMF (19,000 DPI كحد أقصى، 400 IPS)\n• لاسلكي 2.4 جيجاهرتز (كمون 1 مللي ثانية)\n• عمر بطارية حتى 71 ساعة (إضاءة RGB مطفأة)\n• شحن USB-C (اللعب أثناء الشحن)\n• 16.8 مليون لون RGB\n• 6 أزرار قابلة للتخصيص\n• شكل مريح لليد اليمنى\n• أقدام فأرة PTFE فاخرة\n• متوافق مع برنامج Glorious CORE\n• كابل Ascended paracord فائق المرونة\n• معدل استطلاع 1,000 هرتز",
                'description_he' => "ה-Glorious Model D Wireless משלב ביצועים קלים עם חיי סוללה יוצאי דופן ומעקב מדויק.\n\nתכונות עיקריות:\n• עיצוב קל 69 גרם\n• חיישן BAMF (19,000 DPI מקסימום, 400 IPS)\n• אלחוטי 2.4GHz (איחור 1 מילישניות)\n• עד 71 שעות סוללה (RGB כבוי)\n• טעינת USB-C (משחק בזמן טעינה)\n• 16.8 מיליון צבעי RGB\n• 6 כפתורים הניתנים להתאמה אישית\n• צורה ארגונומית לימין\n• רגליות עכבר PTFE פרימיום\n• תואם לתוכנת Glorious CORE",
                'price' => 329.00,
                'sale_price' => 299.00,
                'stock_quantity' => 30,
                'main_image' => 'https://m.media-amazon.com/images/I/71smsucDsZL._AC_SL1500_.jpg',
                'category_slug' => 'mice',
                'brand_slug' => 'glorious',
                'is_active' => true,
                'is_featured' => false,
                'is_new' => false,
                'is_bestseller' => true,
                'stock_status' => 'in_stock',
                'warranty' => '2 years',
                'images' => [
                    'https://m.media-amazon.com/images/I/71smsucDsZL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/516bfC0-1VL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/51spbZC9WHL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/612zzeZh8iL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/61x75FM8XkL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/61EcjPTsGUL._AC_SL1500_.jpg',
                ],
            ],

            // Product 6: MSI MAG A850GL PCIE5
            [
                'name_en' => 'MSI MAG A850GL PCIE5 850W Power Supply',
                'name_ar' => 'وحدة طاقة MSI MAG A850GL PCIE5 850 واط',
                'name_he' => 'ספק כוח MSI MAG A850GL PCIE5 850W',
                'slug' => 'msi-mag-a850gl-pcie5-850w-power-supply',
                'sku' => 'MSI-A850GL-PCIE5-001',
                'short_description_en' => 'Fully modular 850W power supply with 80+ Gold efficiency, ATX 3.1 & PCIe 5.1 ready, native 12V-2x6 cable, and 10-year warranty',
                'short_description_ar' => 'وحدة طاقة معيارية بالكامل 850 واط مع كفاءة 80+ Gold، جاهزة لـ ATX 3.1 و PCIe 5.1، كابل 12V-2x6 أصلي، وضمان 10 سنوات',
                'short_description_he' => 'ספק כוח מודולרי מלא 850W עם יעילות 80+ Gold, מוכן ל-ATX 3.1 ו-PCIe 5.1, כבל 12V-2x6 מקורי ואחריות 10 שנים',
                'description_en' => "The MSI MAG A850GL PCIE5 delivers reliable 850W power with full modularity and next-gen compatibility.\n\nKey Features:\n• 850W total power with 80 PLUS Gold efficiency\n• ATX 3.1 & PCIe 5.1 compliant\n• Up to 600W to PCIe 5.1 graphics cards\n• Native dual-color 16-pin PCIe cable (yellow)\n• 4x 8-pin VGA connectors\n• Fully modular design\n• Compact 140mm x 150mm ATX form factor\n• 120mm Fluid-Dynamic Bearing fan\n• 2x & 3x power excursion support\n• 1x ATX 24-pin cable\n• 4 peripheral connectors\n• 10-year warranty\n• Quiet and stable operation",
                'description_ar' => "توفر وحدة الطاقة MSI MAG A850GL PCIE5 طاقة موثوقة 850 واط مع تصميم معياري كامل وتوافق من الجيل التالي.\n\nالميزات الرئيسية:\n• طاقة إجمالية 850 واط مع كفاءة 80 PLUS Gold\n• متوافق مع ATX 3.1 و PCIe 5.1\n• حتى 600 واط لكروت الشاشة PCIe 5.1\n• كابل PCIe 16 دبوس أصلي بلونين (أصفر)\n• 4 موصلات VGA 8 دبوس\n• تصميم معياري بالكامل\n• عامل شكل ATX مدمج 140 ملم × 150 ملم\n• مروحة محمل ديناميكي سائل 120 ملم\n• دعم زيادة طاقة 2x و 3x\n• كابل ATX 24 دبوس واحد\n• 4 موصلات طرفية\n• ضمان 10 سنوات\n• تشغيل هادئ ومستقر",
                'description_he' => "ה-MSI MAG A850GL PCIE5 מספק הספק אמין של 850W עם מודולריות מלאה ותאימות לדור הבא.\n\nתכונות עיקריות:\n• הספק כולל של 850W עם יעילות 80 PLUS Gold\n• תואם ל-ATX 3.1 ו-PCIe 5.1\n• עד 600W לכרטיסי מסך PCIe 5.1\n• כבל PCIe 16 פין מקורי בשני צבעים (צהוב)\n• 4 מחברי VGA 8 פין\n• עיצוב מודולרי מלא\n• פקטור צורה ATX קומפקטי 140 מ\"מ × 150 מ\"מ\n• מאוורר 120 מ\"מ עם מיסב דינמי נוזלי\n• תמיכה בעליית הספק 2x ו-3x\n• כבל ATX 24 פין אחד\n• 4 מחברי ציוד היקפי\n• אחריות 10 שנים",
                'price' => 549.00,
                'sale_price' => 499.00,
                'stock_quantity' => 12,
                'main_image' => 'https://asset.msi.com/resize/image/global/product/product_17296927006602f4c3b6d358736fc5d1b1d42f4d30.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                'category_slug' => 'power-supplies',
                'brand_slug' => 'msi',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => false,
                'stock_status' => 'in_stock',
                'warranty' => '10 years',
                'images' => [
                    'https://asset.msi.com/resize/image/global/product/product_17296927006602f4c3b6d358736fc5d1b1d42f4d30.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_172976436279e8200db6edb3739316441b0063bf7a.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_16861307271614336078f5c76c62ff157c01353710.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                    'https://asset.msi.com/resize/image/global/product/product_17297642078c5bdeb3c7f150603f2eeca6150e8151.png62405b38c58fe0f07fcef2367d8a9ba1/1024.png',
                ],
            ],

            // Product 7: ASUS ROG Strix XG27AQDMG
            [
                'name_en' => 'ASUS ROG Strix 27" 1440P OLED Gaming Monitor',
                'name_ar' => 'شاشة ألعاب ASUS ROG Strix 27 بوصة 1440P OLED',
                'name_he' => 'מסך גיימינג ASUS ROG Strix 27 אינץ\' 1440P OLED',
                'slug' => 'asus-rog-strix-27-1440p-oled-gaming-monitor',
                'sku' => 'ASUS-ROG-XG27AQDMG-001',
                'short_description_en' => '27-inch QHD OLED gaming monitor with 240Hz refresh rate, 0.03ms response time, custom heatsink, and 99% DCI-P3 color coverage',
                'short_description_ar' => 'شاشة ألعاب OLED 27 بوصة QHD مع معدل تحديث 240 هرتز، زمن استجابة 0.03 مللي ثانية، مشتت حراري مخصص، وتغطية لونية 99% DCI-P3',
                'short_description_he' => 'מסך גיימינג OLED 27 אינץ\' QHD עם קצב רענון 240Hz, זמן תגובה 0.03ms, גוף קירור מותאם אישית וכיסוי צבע 99% DCI-P3',
                'description_en' => "The ASUS ROG Strix XG27AQDMG delivers stunning OLED visuals with extreme refresh rates for competitive gaming.\n\nKey Features:\n• 27\" (26.5\" viewable) WOLED panel\n• 2560x1440 QHD resolution\n• 240Hz refresh rate\n• 0.03ms GTG response time\n• Glossy display surface\n• 99% DCI-P3, 135% sRGB color space\n• 1,300 cd/m² peak brightness (HDR)\n• 1,500,000:1 contrast ratio\n• 10-bit color (1073.7M colors)\n• FreeSync Premium & G-SYNC Compatible\n• OLED Anti-flicker technology\n• Custom heatsink for longevity\n• DisplayPort 1.4 DSC, 2x HDMI 2.0\n• 2x USB 3.2 Gen 1 ports\n• Adjustable stand (tilt, swivel, pivot, height)\n• VESA 100x100mm compatible\n• DisplayWidget Center software\n• 3-year warranty",
                'description_ar' => "توفر شاشة ASUS ROG Strix XG27AQDMG مرئيات OLED مذهلة مع معدلات تحديث فائقة للألعاب التنافسية.\n\nالميزات الرئيسية:\n• لوحة WOLED 27 بوصة (26.5 بوصة قابلة للمشاهدة)\n• دقة QHD 2560×1440\n• معدل تحديث 240 هرتز\n• زمن استجابة 0.03 مللي ثانية GTG\n• سطح شاشة لامع\n• مساحة لونية 99% DCI-P3، 135% sRGB\n• سطوع ذروة 1,300 cd/m² (HDR)\n• نسبة تباين 1,500,000:1\n• ألوان 10 بت (1073.7 مليون لون)\n• FreeSync Premium & G-SYNC متوافق\n• تقنية OLED مضادة للوميض\n• مشتت حراري مخصص للمتانة\n• DisplayPort 1.4 DSC، 2x HDMI 2.0\n• 2 منفذ USB 3.2 Gen 1\n• حامل قابل للتعديل (إمالة، دوران، محور، ارتفاع)\n• متوافق مع VESA 100×100 ملم\n• برنامج DisplayWidget Center\n• ضمان 3 سنوات",
                'description_he' => "ה-ASUS ROG Strix XG27AQDMG מספק ויזואליה OLED מדהימה עם קצבי רענון קיצוניים לגיימינג תחרותי.\n\nתכונות עיקריות:\n• פאנל WOLED 27 אינץ' (26.5 אינץ' נראה)\n• רזולוציית QHD 2560×1440\n• קצב רענון 240Hz\n• זמן תגובה 0.03ms GTG\n• משטח תצוגה מבריק\n• מרחב צבע 99% DCI-P3, 135% sRGB\n• בהירות שיא 1,300 cd/m² (HDR)\n• יחס ניגודיות 1,500,000:1\n• צבע 10 ביט (1073.7 מיליון צבעים)\n• תואם FreeSync Premium ו-G-SYNC\n• טכנולוגיית OLED נגד הבהוב\n• גוף קירור מותאם אישית לאריכות ימים\n• DisplayPort 1.4 DSC, 2x HDMI 2.0\n• 2 יציאות USB 3.2 Gen 1\n• מעמד מתכוונן (הטיה, סיבוב, ציר, גובה)\n• תואם VESA 100×100 מ\"מ\n• תוכנת DisplayWidget Center\n• אחריות 3 שנים",
                'price' => 2799.00,
                'sale_price' => 2599.00,
                'stock_quantity' => 8,
                'main_image' => 'https://m.media-amazon.com/images/I/91PIJXT-HbL._AC_SL1500_.jpg',
                'category_slug' => 'monitors',
                'brand_slug' => 'asus',
                'is_active' => true,
                'is_featured' => true,
                'is_new' => true,
                'is_bestseller' => false,
                'stock_status' => 'in_stock',
                'warranty' => '3 years',
                'images' => [
                    'https://m.media-amazon.com/images/I/91PIJXT-HbL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81uOeJmsIiL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81T7Vn-pVfL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81Ikd73AxvL._AC_SL1500_.jpg',
                    'https://m.media-amazon.com/images/I/81+XzhbNMtL._AC_SL1500_.jpg',
                ],
            ],
        ];

        // Create each product
        foreach ($products as $productData) {
            $this->createProduct($productData);
        }

        $this->command->info('✅ Successfully created 7 new products with all images and details!');
    }

    private function createProduct(array $data)
    {
        // Get category and brand
        $category = Category::where('slug', $data['category_slug'])->first();
        $brand = Brand::where('slug', $data['brand_slug'])->first();

        // Extract images
        $images = $data['images'];
        unset($data['images'], $data['category_slug'], $data['brand_slug']);

        // Create product
        $product = Product::create(array_merge($data, [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]));

        // Create product images
        foreach ($images as $index => $imageUrl) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imageUrl,
                'order' => $index,
                'is_primary' => $index === 0,
                'alt_text' => $product->name_en . ' - Image ' . ($index + 1),
            ]);
        }

        $this->command->info("Created product: {$product->name_en}");
    }
}
