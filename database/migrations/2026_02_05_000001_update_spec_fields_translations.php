<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates existing spec_fields with Arabic and Hebrew translations.
     */
    public function up(): void
    {
        $translations = [
            // PC/Laptop fields
            'processor' => ['ar' => 'المعالج', 'he' => 'מעבד'],
            'ram' => ['ar' => 'الذاكرة العشوائية', 'he' => 'זיכרון RAM'],
            'storage' => ['ar' => 'التخزين', 'he' => 'אחסון'],
            'storage_type' => ['ar' => 'نوع التخزين', 'he' => 'סוג אחסון'],
            'graphics' => ['ar' => 'كرت الشاشة', 'he' => 'כרטיס מסך'],
            'screen_size' => ['ar' => 'حجم الشاشة', 'he' => 'גודל מסך'],
            'screen_resolution' => ['ar' => 'دقة الشاشة', 'he' => 'רזולוציית מסך'],
            'operating_system' => ['ar' => 'نظام التشغيل', 'he' => 'מערכת הפעלה'],
            'battery' => ['ar' => 'عمر البطارية', 'he' => 'חיי סוללה'],
            'touchscreen' => ['ar' => 'شاشة لمس', 'he' => 'מסך מגע'],
            
            // Smartphone fields
            'screen_type' => ['ar' => 'نوع الشاشة', 'he' => 'סוג מסך'],
            'main_camera' => ['ar' => 'الكاميرا الرئيسية', 'he' => 'מצלמה ראשית'],
            'front_camera' => ['ar' => 'الكاميرا الأمامية', 'he' => 'מצלמה קדמית'],
            'dual_sim' => ['ar' => 'شريحتين', 'he' => 'סים כפול'],
            '5g_support' => ['ar' => 'دعم 5G', 'he' => 'תמיכה ב-5G'],
            
            // Monitor fields
            'resolution' => ['ar' => 'الدقة', 'he' => 'רזולוציה'],
            'panel_type' => ['ar' => 'نوع اللوحة', 'he' => 'סוג פאנל'],
            'refresh_rate' => ['ar' => 'معدل التحديث', 'he' => 'קצב רענון'],
            'response_time' => ['ar' => 'زمن الاستجابة', 'he' => 'זמן תגובה'],
            'aspect_ratio' => ['ar' => 'نسبة العرض', 'he' => 'יחס מסך'],
            'hdr' => ['ar' => 'دعم HDR', 'he' => 'תמיכה ב-HDR'],
            'curved' => ['ar' => 'شاشة منحنية', 'he' => 'מסך קעור'],
            'ports' => ['ar' => 'المنافذ', 'he' => 'חיבורים'],
            'vesa_mount' => ['ar' => 'تركيب VESA', 'he' => 'תלייה VESA'],
            
            // Accessories fields
            'compatibility' => ['ar' => 'التوافق', 'he' => 'תאימות'],
            'connectivity' => ['ar' => 'الاتصال', 'he' => 'קישוריות'],
            'color' => ['ar' => 'اللون', 'he' => 'צבע'],
            'material' => ['ar' => 'الخامة', 'he' => 'חומר'],
            'battery_life' => ['ar' => 'عمر البطارية', 'he' => 'חיי סוללה'],
            'waterproof' => ['ar' => 'مقاوم للماء', 'he' => 'עמיד למים'],
            
            // Additional common fields
            'display' => ['ar' => 'الشاشة', 'he' => 'תצוגה'],
            'weight' => ['ar' => 'الوزن', 'he' => 'משקל'],
            'dimensions' => ['ar' => 'الأبعاد', 'he' => 'מידות'],
            'warranty' => ['ar' => 'الضمان', 'he' => 'אחריות'],
        ];

        foreach ($translations as $key => $labels) {
            DB::table('spec_fields')
                ->where('key', $key)
                ->update([
                    'label_ar' => $labels['ar'],
                    'label_he' => $labels['he'],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset to English labels
        DB::table('spec_fields')->update([
            'label_ar' => DB::raw('label_en'),
            'label_he' => DB::raw('label_en'),
        ]);
    }
};
