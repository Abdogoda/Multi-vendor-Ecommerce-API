<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder{

    public function run(): void{

        $categories = [
            // Main categories
            ['name_en' => 'Electronics', 'name_ar' => 'إلكترونيات', 'slug' => 'electronics'],
            ['name_en' => 'Clothing', 'name_ar' => 'ملابس', 'slug' => 'clothing'],
            ['name_en' => 'Home Appliances', 'name_ar' => 'أجهزة منزلية', 'slug' => 'home-appliances'],
            ['name_en' => 'Books', 'name_ar' => 'كتب', 'slug' => 'books'],
            ['name_en' => 'Toys', 'name_ar' => 'ألعاب', 'slug' => 'toys'],
            ['name_en' => 'Beauty', 'name_ar' => 'جمال', 'slug' => 'beauty'],
            ['name_en' => 'Health & Wellness', 'name_ar' => 'صحة وعافية', 'slug' => 'health-wellness'],
            ['name_en' => 'Furniture', 'name_ar' => 'أثاث', 'slug' => 'furniture'],
            ['name_en' => 'Jewelry', 'name_ar' => 'مجوهرات', 'slug' => 'jewelry'],
            ['name_en' => 'Sports & Outdoors', 'name_ar' => 'رياضة في الهواء الطلق', 'slug' => 'sports-outdoors'],
            ['name_en' => 'Automotive', 'name_ar' => 'سيارات', 'slug' => 'automotive'],
            ['name_en' => 'Food & Beverage', 'name_ar' => 'طعام ومشروبات', 'slug' => 'food-beverage'],
            ['name_en' => 'Office Supplies', 'name_ar' => 'لوازم مكتبية', 'slug' => 'office-supplies'],
            ['name_en' => 'Pet Supplies', 'name_ar' => 'لوازم الحيوانات الأليفة', 'slug' => 'pet-supplies'],
            ['name_en' => 'Art & Craft', 'name_ar' => 'فن وحرف', 'slug' => 'art-craft'],
            
            // Subcategories
            ['name_en' => 'Mobile Phones', 'name_ar' => 'هواتف محمولة', 'slug' => 'mobile-phones', 'parent_id' => 1],
            ['name_en' => 'Laptops', 'name_ar' => 'أجهزة كمبيوتر محمولة', 'slug' => 'laptops', 'parent_id' => 1],
            ['name_en' => 'Sofas', 'name_ar' => 'أرائك', 'slug' => 'sofas', 'parent_id' => 8],
            ['name_en' => 'Dining Tables', 'name_ar' => 'طاولات طعام', 'slug' => 'dining-tables', 'parent_id' => 8],
            ['name_en' => 'Cosmetics', 'name_ar' => 'مستحضرات التجميل', 'slug' => 'cosmetics', 'parent_id' => 6],
            ['name_en' => 'Skincare', 'name_ar' => 'العناية بالبشرة', 'slug' => 'skincare', 'parent_id' => 6],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}