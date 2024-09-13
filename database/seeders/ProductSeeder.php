<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder{

    public function run(): void{
        $vendorIds = Vendor::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        if (empty($vendorIds) || empty($categoryIds)) {
            $this->command->info('Please seed vendors and categories before running products seeder.');
            return;
        }

        foreach (range(1, 50) as $index) {
            Product::create([
                'vendor_id' => $vendorIds[array_rand($vendorIds)],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'name_en' => 'Product Name ' . $index,
                'name_ar' => 'اسم المنتج ' . $index,
                'description' => 'This is a description for product ' . $index,
                'price' => rand(100, 1000) / 100,
                'discount_price' => rand(50, 500) / 100,
                'stock' => rand(1, 100),
                'status' => ['active', 'out of stock', 'draft'][array_rand(['active', 'out of stock', 'draft'])],
                'main_image' => 'default-product-image.jpg',
            ]);
        }
    }
}