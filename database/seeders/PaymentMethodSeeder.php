<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder{
    public function run(): void{
        $paymentMethods = [
            ['name' => 'Cash on Delivery', 'description' => 'Pay with cash upon delivery of the item.', 'status' => true],
            ['name' => 'Mobile Wallet', 'description' => 'Pay using mobile wallet applications.', 'status' => true],
            ['name' => 'Visa Card', 'description' => 'Pay using Visa credit or debit cards.', 'status' => true],
            // ['name' => 'MasterCard', 'description' => 'Pay using MasterCard credit or debit cards.', 'status' => true],
            // ['name' => 'PayPal', 'description' => 'Pay using PayPal account.', 'status' => true],
        ];

        foreach ($paymentMethods as $method) {
            DB::table('payment_methods')->insert($method);
        }
    }
}