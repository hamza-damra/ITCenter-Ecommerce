<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $nameEn = implode(' ', $this->faker->unique()->words(2));
        $nameAr = $this->getArabicCategoryName();
        
        return [
            'name_en' => ucfirst($nameEn),
            'name_ar' => $nameAr,
            'slug' => Str::slug($nameEn),
            'description_en' => $this->faker->sentence(15),
            'description_ar' => $this->getArabicDescription(),
            'image' => 'https://picsum.photos/seed/' . Str::random(10) . '/640/480',
            'parent_id' => null,
            'is_active' => $this->faker->boolean(90),
            'order' => $this->faker->numberBetween(0, 100),
            'meta_title' => ucfirst($nameEn) . ' - IT Center',
            'meta_description' => $this->faker->sentence(20),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }

    /**
     * Get Arabic category name.
     */
    private function getArabicCategoryName(): string
    {
        $arabicNames = [
            'أجهزة كمبيوتر محمولة',
            'أجهزة كمبيوتر مكتبية',
            'الملحقات',
            'المكونات',
            'الشاشات',
            'لوحات المفاتيح',
            'الفأرة',
            'الطابعات',
            'الماسحات الضوئية',
            'الكاميرات',
        ];
        
        return $this->faker->randomElement($arabicNames);
    }

    /**
     * Get Arabic description.
     */
    private function getArabicDescription(): string
    {
        $descriptions = [
            'اكتشف أحدث التقنيات في عالم الكمبيوتر والإلكترونيات',
            'منتجات عالية الجودة بأفضل الأسعار في السوق',
            'تشكيلة واسعة من المنتجات التقنية المتطورة',
            'أفضل العلامات التجارية العالمية متوفرة لديهم',
            'حلول تقنية متكاملة لجميع احتياجاتك',
        ];
        
        return $this->faker->randomElement($descriptions);
    }

    /**
     * Indicate that the category is a subcategory.
     */
    public function subcategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::factory(),
        ]);
    }
}
