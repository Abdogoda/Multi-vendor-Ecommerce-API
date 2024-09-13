<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        for ($i=1; $i < 5; $i++) { 
            foreach (['admin', 'vendor', 'customer'] as $role) {
                User::create([
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => $role.$i.'@gmail.com',
                    'password' => Hash::make('123456'),
                    'phone' => fake()->phoneNumber(),
                    'address' => fake()->address(),
                    'role' => $role,
                    'status' => $i > 3 ? 'deactive' : 'active',
                ]);
            }
        }
    }
}