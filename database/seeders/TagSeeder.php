<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder{

    public function run(): void{
        
        $tags = [
            ['name_en' => 'Electronics', 'name_ar' => 'إلكترونيات'],
            ['name_en' => 'Clothing', 'name_ar' => 'ملابس'],
            ['name_en' => 'Home Appliances', 'name_ar' => 'أجهزة منزلية'],
            ['name_en' => 'Books', 'name_ar' => 'كتب'],
            ['name_en' => 'Toys', 'name_ar' => 'ألعاب'],
            ['name_en' => 'Beauty', 'name_ar' => 'جمال'],
            ['name_en' => 'Health & Wellness', 'name_ar' => 'صحة وعافية'],
            ['name_en' => 'Furniture', 'name_ar' => 'أثاث'],
            ['name_en' => 'Jewelry', 'name_ar' => 'مجوهرات'],
            ['name_en' => 'Sports & Outdoors', 'name_ar' => 'رياضة في الهواء الطلق'],
            ['name_en' => 'Automotive', 'name_ar' => 'سيارات'],
            ['name_en' => 'Food & Beverage', 'name_ar' => 'طعام ومشروبات'],
            ['name_en' => 'Office Supplies', 'name_ar' => 'لوازم مكتبية'],
            ['name_en' => 'Pet Supplies', 'name_ar' => 'لوازم الحيوانات الأليفة'],
            ['name_en' => 'Art & Craft', 'name_ar' => 'فن وحرف'],
        ];

        DB::table('tags')->insert($tags);
    }
}