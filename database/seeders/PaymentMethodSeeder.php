<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash', 'type' => 'cash', 'description' => 'Pay with cash on delivery'],
            ['name' => 'UPI', 'type' => 'upi', 'description' => 'Pay using UPI apps'],
            ['name' => 'Credit Card', 'type' => 'credit_card', 'description' => 'Pay using credit card'],
            ['name' => 'Debit Card', 'type' => 'debit_card', 'description' => 'Pay using debit card'],
            ['name' => 'Wallet', 'type' => 'wallet', 'description' => 'Pay using digital wallet'],
            ['name' => 'Net Banking', 'type' => 'netbanking', 'description' => 'Pay using net banking'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['type' => $method['type']],
                [
                    'name' => $method['name'],
                    'description' => $method['description'],
                    'status' => 'active',
                ]
            );
        }
    }
}
