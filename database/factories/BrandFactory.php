<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $nameEn = $this->faker->unique()->company();
        $nameAr = $this->getArabicBrandName();
        
        return [
            'name_en' => $nameEn,
            'name_ar' => $nameAr,
            'slug' => Str::slug($nameEn),
            'description_en' => $this->faker->paragraph(3),
            'description_ar' => $this->getArabicDescription(),
            'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($nameEn) . '&size=300&background=random',
            'website' => $this->faker->url(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'is_active' => $this->faker->boolean(95),
            'is_featured' => $this->faker->boolean(30),
            'order' => $this->faker->numberBetween(0, 100),
            'meta_title' => $nameEn . ' - IT Center',
            'meta_description' => $this->faker->sentence(20),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }

    /**
     * Get Arabic brand name.
     */
    private function getArabicBrandName(): string
    {
        $arabicBrands = [
            'ديل',
            'إتش بي',
            'لينوفو',
            'أسوس',
            'أيسر',
            'أبل',
            'مايكروسوفت',
            'سامسونج',
            'إل جي',
            'توشيبا',
        ];
        
        return $this->faker->randomElement($arabicBrands);
    }

    /**
     * Get Arabic description.
     */
    private function getArabicDescription(): string
    {
        return 'علامة تجارية رائدة في مجال التكنولوجيا توفر منتجات عالية الجودة وحلول مبتكرة لجميع احتياجاتك التقنية.';
    }
}
