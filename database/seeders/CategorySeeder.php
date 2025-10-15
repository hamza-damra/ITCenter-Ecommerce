<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'High-performance laptops for work and gaming',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 1,
                'subcategories' => [
                    ['name' => 'Gaming Laptops', 'slug' => 'gaming-laptops'],
                    ['name' => 'Business Laptops', 'slug' => 'business-laptops'],
                    ['name' => 'Ultrabooks', 'slug' => 'ultrabooks'],
                ]
            ],
            [
                'name' => 'Desktops',
                'slug' => 'desktops',
                'description' => 'Powerful desktop computers',
                'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 2,
                'subcategories' => [
                    ['name' => 'Gaming Desktops', 'slug' => 'gaming-desktops'],
                    ['name' => 'Workstations', 'slug' => 'workstations'],
                    ['name' => 'All-in-One PCs', 'slug' => 'all-in-one-pcs'],
                ]
            ],
            [
                'name' => 'Components',
                'slug' => 'components',
                'description' => 'Computer components and parts',
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 3,
                'subcategories' => [
                    ['name' => 'Processors', 'slug' => 'processors'],
                    ['name' => 'Graphics Cards', 'slug' => 'graphics-cards'],
                    ['name' => 'Motherboards', 'slug' => 'motherboards'],
                    ['name' => 'RAM', 'slug' => 'ram'],
                    ['name' => 'Storage', 'slug' => 'storage'],
                    ['name' => 'Power Supplies', 'slug' => 'power-supplies'],
                ]
            ],
            [
                'name' => 'Peripherals',
                'slug' => 'peripherals',
                'description' => 'Computer peripherals and accessories',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 4,
                'subcategories' => [
                    ['name' => 'Keyboards', 'slug' => 'keyboards'],
                    ['name' => 'Mice', 'slug' => 'mice'],
                    ['name' => 'Monitors', 'slug' => 'monitors'],
                    ['name' => 'Headsets', 'slug' => 'headsets'],
                    ['name' => 'Webcams', 'slug' => 'webcams'],
                ]
            ],
            [
                'name' => 'Networking',
                'slug' => 'networking',
                'description' => 'Networking equipment',
                'image' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 5,
                'subcategories' => [
                    ['name' => 'Routers', 'slug' => 'routers'],
                    ['name' => 'Switches', 'slug' => 'switches'],
                    ['name' => 'Access Points', 'slug' => 'access-points'],
                ]
            ],
            [
                'name' => 'Software',
                'slug' => 'software',
                'description' => 'Software and licenses',
                'image' => 'https://images.unsplash.com/photo-1555421689-d68471e189f2?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 6,
                'subcategories' => [
                    ['name' => 'Operating Systems', 'slug' => 'operating-systems'],
                    ['name' => 'Office Software', 'slug' => 'office-software'],
                    ['name' => 'Security Software', 'slug' => 'security-software'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'] ?? [];
            unset($categoryData['subcategories']);

            $category = Category::create($categoryData);

            foreach ($subcategories as $subcategoryData) {
                $subcategoryData['parent_id'] = $category->id;
                $subcategoryData['description'] = $subcategoryData['name'] . ' category';
                $subcategoryData['is_active'] = true;
                Category::create($subcategoryData);
            }
        }
    }
}
