<?php

namespace Database\Seeders;

use App\Models\ShippingBlockedRange;
use App\Models\ShippingCity;
use App\Models\ShippingRegion;
use App\Models\ShippingSetting;
use Illuminate\Database\Seeder;

class ShippingDataSeeder extends Seeder
{
    public function run(): void
    {
        // ========== Regions ==========
        $westBank = ShippingRegion::updateOrCreate(
            ['key' => 'west_bank'],
            ['name_en' => 'West Bank', 'name_ar' => 'الضفة الغربية', 'name_he' => 'הגדה המערבית', 'is_active' => true, 'sort_order' => 1]
        );

        $interior48 = ShippingRegion::updateOrCreate(
            ['key' => 'interior_48'],
            ['name_en' => '1948 Territories', 'name_ar' => 'الداخل المحتل (48)', 'name_he' => 'שטחי 48', 'is_active' => true, 'sort_order' => 2]
        );

        // ========== West Bank Cities ==========
        $westBankCities = [
            ['key' => 'jerusalem', 'name_en' => 'Jerusalem', 'name_ar' => 'القدس', 'name_he' => 'ירושלים', 'gov_en' => 'Jerusalem', 'gov_ar' => 'القدس', 'gov_he' => 'ירושלים', 'min' => 100, 'max' => 148, 'sort' => 1],
            ['key' => 'bethlehem', 'name_en' => 'Bethlehem', 'name_ar' => 'بيت لحم', 'name_he' => 'בית לחם', 'gov_en' => 'Bethlehem', 'gov_ar' => 'بيت لحم', 'gov_he' => 'בית לחם', 'min' => 149, 'max' => 199, 'sort' => 2],
            ['key' => 'jenin', 'name_en' => 'Jenin', 'name_ar' => 'جنين', 'name_he' => "ג'נין", 'gov_en' => 'Jenin', 'gov_ar' => 'جنين', 'gov_he' => "ג'נין", 'min' => 200, 'max' => 299, 'sort' => 3],
            ['key' => 'tulkarm', 'name_en' => 'Tulkarm', 'name_ar' => 'طولكرم', 'name_he' => 'טולכרם', 'gov_en' => 'Tulkarm', 'gov_ar' => 'طولكرم', 'gov_he' => 'טולכרם', 'min' => 300, 'max' => 339, 'sort' => 4],
            ['key' => 'qalqilya', 'name_en' => 'Qalqilya', 'name_ar' => 'قلقيلية', 'name_he' => 'קלקיליה', 'gov_en' => 'Qalqilya', 'gov_ar' => 'قلقيلية', 'gov_he' => 'קלקיליה', 'min' => 340, 'max' => 377, 'sort' => 5],
            ['key' => 'salfit', 'name_en' => 'Salfit', 'name_ar' => 'سلفيت', 'name_he' => 'סלפית', 'gov_en' => 'Salfit', 'gov_ar' => 'سلفيت', 'gov_he' => 'סלפית', 'min' => 380, 'max' => 399, 'sort' => 6],
            ['key' => 'nablus', 'name_en' => 'Nablus', 'name_ar' => 'نابلس', 'name_he' => 'שכם', 'gov_en' => 'Nablus', 'gov_ar' => 'نابلس', 'gov_he' => 'שכם', 'min' => 400, 'max' => 499, 'sort' => 7],
            ['key' => 'tubas', 'name_en' => 'Tubas', 'name_ar' => 'طوباس', 'name_he' => 'טובאס', 'gov_en' => 'Tubas', 'gov_ar' => 'طوباس', 'gov_he' => 'טובאס', 'min' => 500, 'max' => 540, 'sort' => 8],
            ['key' => 'jericho', 'name_en' => 'Jericho', 'name_ar' => 'أريحا', 'name_he' => 'יריחו', 'gov_en' => 'Jericho & Al-Aghwar', 'gov_ar' => 'أريحا والأغوار', 'gov_he' => 'יריחו ובקעת הירדן', 'min' => 550, 'max' => 590, 'sort' => 9],
            ['key' => 'ramallah', 'name_en' => 'Ramallah & Al-Bireh', 'name_ar' => 'رام الله والبيرة', 'name_he' => 'רמאללה ואל-בירה', 'gov_en' => 'Ramallah & Al-Bireh', 'gov_ar' => 'رام الله والبيرة', 'gov_he' => 'רמאללה ואל-בירה', 'min' => 600, 'max' => 699, 'sort' => 10],
            ['key' => 'hebron', 'name_en' => 'Hebron', 'name_ar' => 'الخليل', 'name_he' => 'חברון', 'gov_en' => 'Hebron', 'gov_ar' => 'الخليل', 'gov_he' => 'חברון', 'min' => 700, 'max' => 797, 'sort' => 11],
        ];

        foreach ($westBankCities as $city) {
            ShippingCity::updateOrCreate(
                ['key' => $city['key']],
                [
                    'shipping_region_id' => $westBank->id,
                    'name_en' => $city['name_en'], 'name_ar' => $city['name_ar'], 'name_he' => $city['name_he'],
                    'governorate_en' => $city['gov_en'], 'governorate_ar' => $city['gov_ar'], 'governorate_he' => $city['gov_he'],
                    'postal_code_min' => $city['min'], 'postal_code_max' => $city['max'],
                    'is_active' => true, 'sort_order' => $city['sort'],
                ]
            );
        }

        // ========== 1948 Territories Cities ==========
        $interior48Cities = [
            ['key' => 'haifa', 'name_en' => 'Haifa', 'name_ar' => 'حيفا', 'name_he' => 'חיפה', 'gov_en' => 'Haifa', 'gov_ar' => 'حيفا', 'gov_he' => 'חיפה', 'min' => 1, 'max' => 10, 'sort' => 1],
            ['key' => 'jaffa', 'name_en' => 'Jaffa', 'name_ar' => 'يافا', 'name_he' => 'יפו', 'gov_en' => 'Jaffa', 'gov_ar' => 'يافا', 'gov_he' => 'יפו', 'min' => 11, 'max' => 20, 'sort' => 2],
            ['key' => 'nazareth', 'name_en' => 'Nazareth', 'name_ar' => 'الناصرة', 'name_he' => 'נצרת', 'gov_en' => 'Nazareth', 'gov_ar' => 'الناصرة', 'gov_he' => 'נצרת', 'min' => 21, 'max' => 30, 'sort' => 3],
            ['key' => 'acre', 'name_en' => 'Acre (Akka)', 'name_ar' => 'عكا', 'name_he' => 'עכו', 'gov_en' => 'Acre', 'gov_ar' => 'عكا', 'gov_he' => 'עכו', 'min' => 31, 'max' => 37, 'sort' => 4],
            ['key' => 'umm_al_fahm', 'name_en' => 'Umm Al-Fahm', 'name_ar' => 'أم الفحم', 'name_he' => 'אום אל-פחם', 'gov_en' => 'Umm Al-Fahm', 'gov_ar' => 'أم الفحم', 'gov_he' => 'אום אל-פחם', 'min' => 38, 'max' => 44, 'sort' => 5],
            ['key' => 'lod', 'name_en' => 'Lod (Lydda)', 'name_ar' => 'اللد', 'name_he' => 'לוד', 'gov_en' => 'Lod', 'gov_ar' => 'اللد', 'gov_he' => 'לוד', 'min' => 45, 'max' => 51, 'sort' => 6],
            ['key' => 'ramleh', 'name_en' => 'Ramleh', 'name_ar' => 'الرملة', 'name_he' => 'רמלה', 'gov_en' => 'Ramleh', 'gov_ar' => 'الرملة', 'gov_he' => 'רמלה', 'min' => 52, 'max' => 58, 'sort' => 7],
            ['key' => 'beer_sheva', 'name_en' => 'Beer Sheva (Negev)', 'name_ar' => 'بئر السبع', 'name_he' => 'באר שבע', 'gov_en' => 'Beer Sheva', 'gov_ar' => 'بئر السبع', 'gov_he' => 'באר שבע', 'min' => 59, 'max' => 65, 'sort' => 8],
            ['key' => 'tiberias', 'name_en' => 'Tiberias', 'name_ar' => 'طبريا', 'name_he' => 'טבריה', 'gov_en' => 'Tiberias', 'gov_ar' => 'طبريا', 'gov_he' => 'טבריה', 'min' => 66, 'max' => 72, 'sort' => 9],
            ['key' => 'safed', 'name_en' => 'Safed', 'name_ar' => 'صفد', 'name_he' => 'צפת', 'gov_en' => 'Safed', 'gov_ar' => 'صفد', 'gov_he' => 'צפת', 'min' => 73, 'max' => 79, 'sort' => 10],
            ['key' => 'taybeh', 'name_en' => 'Taybeh', 'name_ar' => 'الطيبة', 'name_he' => 'טייבה', 'gov_en' => 'Taybeh', 'gov_ar' => 'الطيبة', 'gov_he' => 'טייבה', 'min' => 80, 'max' => 85, 'sort' => 11],
            ['key' => 'baqa_al_gharbiyye', 'name_en' => 'Baqa Al-Gharbiyye', 'name_ar' => 'باقة الغربية', 'name_he' => "בקא אל-ע'רביה", 'gov_en' => 'Baqa Al-Gharbiyye', 'gov_ar' => 'باقة الغربية', 'gov_he' => "בקא אל-ע'רביה", 'min' => 86, 'max' => 91, 'sort' => 12],
            ['key' => 'shefa_amr', 'name_en' => 'Shefa-Amr', 'name_ar' => 'شفا عمرو', 'name_he' => 'שפרעם', 'gov_en' => 'Shefa-Amr', 'gov_ar' => 'شفا عمرو', 'gov_he' => 'שפרעם', 'min' => 92, 'max' => 99, 'sort' => 13],
        ];

        foreach ($interior48Cities as $city) {
            ShippingCity::updateOrCreate(
                ['key' => $city['key']],
                [
                    'shipping_region_id' => $interior48->id,
                    'name_en' => $city['name_en'], 'name_ar' => $city['name_ar'], 'name_he' => $city['name_he'],
                    'governorate_en' => $city['gov_en'], 'governorate_ar' => $city['gov_ar'], 'governorate_he' => $city['gov_he'],
                    'postal_code_min' => $city['min'], 'postal_code_max' => $city['max'],
                    'is_active' => true, 'sort_order' => $city['sort'],
                ]
            );
        }

        // ========== Blocked Ranges (Gaza) ==========
        $blockedRanges = [
            ['min' => 800, 'max' => 830, 'label_en' => 'North Gaza', 'label_ar' => 'شمال غزة', 'label_he' => 'צפון עזה', 'reason_en' => 'Shipping to Gaza is not available', 'reason_ar' => 'الشحن إلى غزة غير متاح', 'reason_he' => 'משלוח לעזה אינו זמין'],
            ['min' => 840, 'max' => 890, 'label_en' => 'Gaza', 'label_ar' => 'غزة', 'label_he' => 'עזה', 'reason_en' => 'Shipping to Gaza is not available', 'reason_ar' => 'الشحن إلى غزة غير متاح', 'reason_he' => 'משלוח לעזה אינו זמין'],
            ['min' => 900, 'max' => 929, 'label_en' => 'Deir El Balah', 'label_ar' => 'دير البلح', 'label_he' => 'דיר אל-בלח', 'reason_en' => 'Shipping to Gaza is not available', 'reason_ar' => 'الشحن إلى غزة غير متاح', 'reason_he' => 'משלוח לעזה אינו זמין'],
            ['min' => 930, 'max' => 969, 'label_en' => 'Khan Yunis', 'label_ar' => 'خان يونس', 'label_he' => "ח'אן יונס", 'reason_en' => 'Shipping to Gaza is not available', 'reason_ar' => 'الشحن إلى غزة غير متاح', 'reason_he' => 'משלוח לעזה אינו זמין'],
            ['min' => 970, 'max' => 999, 'label_en' => 'Rafah', 'label_ar' => 'رفح', 'label_he' => 'רפיח', 'reason_en' => 'Shipping to Gaza is not available', 'reason_ar' => 'الشحن إلى غزة غير متاح', 'reason_he' => 'משלוח לעזה אינו זמין'],
        ];

        foreach ($blockedRanges as $range) {
            ShippingBlockedRange::updateOrCreate(
                ['postal_code_min' => $range['min'], 'postal_code_max' => $range['max']],
                [
                    'label_en' => $range['label_en'], 'label_ar' => $range['label_ar'], 'label_he' => $range['label_he'],
                    'reason_en' => $range['reason_en'], 'reason_ar' => $range['reason_ar'], 'reason_he' => $range['reason_he'],
                    'is_active' => true,
                ]
            );
        }

        // ========== Shipping Settings ==========
        $settings = [
            ['key' => 'shipping_country', 'value' => 'Palestine', 'type' => 'string', 'desc_en' => 'Fixed shipping country', 'desc_ar' => 'دولة الشحن الثابتة', 'desc_he' => 'מדינת משלוח קבועה'],
            ['key' => 'postal_code_digits', 'value' => '7', 'type' => 'integer', 'desc_en' => 'Number of digits after P prefix', 'desc_ar' => 'عدد الأرقام بعد البادئة P', 'desc_he' => 'מספר ספרות אחרי הקידומת P'],
            ['key' => 'free_shipping_threshold', 'value' => '200', 'type' => 'integer', 'desc_en' => 'Free shipping for orders above this amount', 'desc_ar' => 'شحن مجاني للطلبات فوق هذا المبلغ', 'desc_he' => 'משלוח חינם להזמנות מעל סכום זה'],
            ['key' => 'shipping_fee', 'value' => '25', 'type' => 'integer', 'desc_en' => 'Default shipping fee', 'desc_ar' => 'رسوم الشحن الافتراضية', 'desc_he' => 'עלות משלוח ברירת מחדל'],
            ['key' => 'shipping_enabled', 'value' => '1', 'type' => 'boolean', 'desc_en' => 'Enable/disable shipping system', 'desc_ar' => 'تفعيل/تعطيل نظام الشحن', 'desc_he' => 'הפעלה/השבתה של מערכת המשלוח'],
        ];

        foreach ($settings as $setting) {
            ShippingSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description_en' => $setting['desc_en'],
                    'description_ar' => $setting['desc_ar'],
                    'description_he' => $setting['desc_he'],
                ]
            );
        }
    }
}
