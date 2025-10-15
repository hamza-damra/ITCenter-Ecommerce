<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Dell Technologies is an American multinational computer technology company',
                'logo' => 'https://logo.clearbit.com/dell.com',
                'website' => 'https://www.dell.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'HP Inc. is an American multinational information technology company',
                'logo' => 'https://logo.clearbit.com/hp.com',
                'website' => 'https://www.hp.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'Lenovo Group Limited is a Chinese multinational technology company',
                'logo' => 'https://logo.clearbit.com/lenovo.com',
                'website' => 'https://www.lenovo.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'ASUS',
                'slug' => 'asus',
                'description' => 'ASUSTeK Computer Inc. is a Taiwanese multinational computer hardware company',
                'logo' => 'https://logo.clearbit.com/asus.com',
                'website' => 'https://www.asus.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'MSI',
                'slug' => 'msi',
                'description' => 'Micro-Star International is a Taiwanese multinational information technology company',
                'logo' => 'https://logo.clearbit.com/msi.com',
                'website' => 'https://www.msi.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 5,
            ],
            [
                'name' => 'Acer',
                'slug' => 'acer',
                'description' => 'Acer Inc. is a Taiwanese multinational hardware and electronics company',
                'logo' => 'https://logo.clearbit.com/acer.com',
                'website' => 'https://www.acer.com',
                'is_active' => true,
                'is_featured' => false,
                'order' => 6,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Apple Inc. is an American multinational technology company',
                'logo' => 'https://logo.clearbit.com/apple.com',
                'website' => 'https://www.apple.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 7,
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Samsung Electronics Co., Ltd. is a South Korean multinational electronics company',
                'logo' => 'https://logo.clearbit.com/samsung.com',
                'website' => 'https://www.samsung.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 8,
            ],
            [
                'name' => 'Intel',
                'slug' => 'intel',
                'description' => 'Intel Corporation is an American multinational corporation and technology company',
                'logo' => 'https://logo.clearbit.com/intel.com',
                'website' => 'https://www.intel.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 9,
            ],
            [
                'name' => 'AMD',
                'slug' => 'amd',
                'description' => 'Advanced Micro Devices, Inc. is an American multinational semiconductor company',
                'logo' => 'https://logo.clearbit.com/amd.com',
                'website' => 'https://www.amd.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 10,
            ],
            [
                'name' => 'NVIDIA',
                'slug' => 'nvidia',
                'description' => 'Nvidia Corporation is an American multinational technology company',
                'logo' => 'https://logo.clearbit.com/nvidia.com',
                'website' => 'https://www.nvidia.com',
                'is_active' => true,
                'is_featured' => true,
                'order' => 11,
            ],
            [
                'name' => 'Logitech',
                'slug' => 'logitech',
                'description' => 'Logitech International S.A. is a Swiss manufacturer of computer peripherals',
                'logo' => 'https://logo.clearbit.com/logitech.com',
                'website' => 'https://www.logitech.com',
                'is_active' => true,
                'is_featured' => false,
                'order' => 12,
            ],
            [
                'name' => 'Corsair',
                'slug' => 'corsair',
                'description' => 'Corsair Gaming, Inc. is an American computer peripherals and hardware company',
                'logo' => 'https://logo.clearbit.com/corsair.com',
                'website' => 'https://www.corsair.com',
                'is_active' => true,
                'is_featured' => false,
                'order' => 13,
            ],
            [
                'name' => 'Razer',
                'slug' => 'razer',
                'description' => 'Razer Inc. is a Singaporean-American multinational technology company',
                'logo' => 'https://logo.clearbit.com/razer.com',
                'website' => 'https://www.razer.com',
                'is_active' => true,
                'is_featured' => false,
                'order' => 14,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
