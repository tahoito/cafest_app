<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['slug' => 'cash',   'name' => '現金'],
            ['slug' => 'card',   'name' => 'クレジットカード'],
            ['slug' => 'paypay', 'name' => 'PayPay'],
        ];

        foreach ($methods as $m) {
            \App\Models\PaymentMethod::updateOrCreate(
                ['slug' => $m['slug']],
                ['name' => $m['name']]
            );
        }
    }
}
