<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::insert([
            ['name' => '現金'],
            ['name' => 'クレジットカード'],
            ['name' => 'PayPay'],
            ['name' => 'Suica'],
            ['name' => 'Apple Pay'],
        ]);
    }
}
