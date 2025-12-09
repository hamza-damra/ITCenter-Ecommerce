<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name_en' => 'Gaming',
                'name_ar' => 'ألعاب',
                'slug' => 'gaming',
                'color' => '#ef4444',
                'icon' => 'fas fa-gamepad',
                'position' => 1,
            ],
            [
                'name_en' => 'Office',
                'name_ar' => 'مكتبي',
                'slug' => 'office',
                'color' => '#3b82f6',
                'icon' => 'fas fa-briefcase',
                'position' => 2,
            ],
            [
                'name_en' => 'Student',
                'name_ar' => 'طلابي',
                'slug' => 'student',
                'color' => '#22c55e',
                'icon' => 'fas fa-graduation-cap',
                'position' => 3,
            ],
            [
                'name_en' => 'Professional',
                'name_ar' => 'احترافي',
                'slug' => 'professional',
                'color' => '#8b5cf6',
                'icon' => 'fas fa-user-tie',
                'position' => 4,
            ],
            [
                'name_en' => 'Budget',
                'name_ar' => 'اقتصادي',
                'slug' => 'budget',
                'color' => '#f59e0b',
                'icon' => 'fas fa-dollar-sign',
                'position' => 5,
            ],
            [
                'name_en' => 'Premium',
                'name_ar' => 'فاخر',
                'slug' => 'premium',
                'color' => '#ec4899',
                'icon' => 'fas fa-crown',
                'position' => 6,
            ],
            [
                'name_en' => 'Portable',
                'name_ar' => 'محمول',
                'slug' => 'portable',
                'color' => '#06b6d4',
                'icon' => 'fas fa-laptop',
                'position' => 7,
            ],
            [
                'name_en' => 'High Performance',
                'name_ar' => 'أداء عالي',
                'slug' => 'high-performance',
                'color' => '#dc2626',
                'icon' => 'fas fa-bolt',
                'position' => 8,
            ],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
