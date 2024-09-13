<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorPaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $vendors = Vendor::all();
        $paymentMethods = PaymentMethod::pluck('id')->toArray();

        if (empty($vendors) || empty($paymentMethods)) {
            $this->command->info('Please seed vendors and categories before running products seeder.');
            return;
        }

        foreach ($vendors as $vendor) {
            $vendor->paymentMethods()->sync($paymentMethods);
        }

    }
}