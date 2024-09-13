<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $users = User::where('role', 'vendor')->get();
        foreach ($users as $user) {
            Vendor::create([
                'user_id' => $user->id,
                'shop_name_en' => fake()->company(),
                'shop_email' => fake()->companyEmail(),
                'shop_phone' => fake()->phoneNumber(),
                'shop_address' => fake()->address(),
                'shop_website' => fake()->url(),
                'shop_logo' => fake()->imageUrl(),
            ]);
        }
    }
}