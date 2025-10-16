<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $nameEn = implode(' ', $this->faker->words(3));
        $nameAr = $this->getArabicOfferName();
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(-30, 0));
        $endDate = $startDate->copy()->addDays($this->faker->numberBetween(7, 60));
        
        return [
            'name_en' => ucfirst($nameEn),
            'name_ar' => $nameAr,
            'slug' => Str::slug($nameEn),
            'description_en' => $this->faker->paragraph(2),
            'description_ar' => $this->getArabicDescription(),
            'discount_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'discount_value' => $this->faker->randomFloat(2, 5, 50),
            'min_purchase_amount' => $this->faker->randomElement([null, 100, 200, 500]),
            'max_uses' => $this->faker->randomElement([null, 50, 100, 500]),
            'uses_count' => $this->faker->numberBetween(0, 30),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => $this->faker->boolean(80),
            'banner_image' => 'https://picsum.photos/seed/' . Str::random(10) . '/1200/400',
        ];
    }

    /**
     * Get Arabic offer name.
     */
    private function getArabicOfferName(): string
    {
        $arabicOffers = [
            'عرض خاص محدود',
            'تخفيضات الموسم',
            'عرض الجمعة البيضاء',
            'تخفيضات الصيف',
            'عرض نهاية العام',
            'تخفيضات حصرية',
            'عرض العودة للمدارس',
            'تخفيضات رمضان',
        ];
        
        return $this->faker->randomElement($arabicOffers);
    }

    /**
     * Get Arabic description.
     */
    private function getArabicDescription(): string
    {
        return 'عرض خاص لفترة محدودة! احصل على أفضل المنتجات التقنية بأسعار لا تقبل المنافسة. لا تفوت هذه الفرصة الذهبية.';
    }

    /**
     * Indicate that the offer is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(30),
        ]);
    }
}
