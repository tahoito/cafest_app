<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Models\StoreHour;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'cafest',
                'email' => 'cafest@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中村区名駅1丁目1番',
                'phone' => '03-0401-18XX',
                'area' => '名駅',
                'mood' => '韓国風',
                'description' => '韓国っぽい雰囲気のカフェです。',
                'budget_min' => 1000,
                'budget_max' => 2000,
            ],
        ];

        foreach ($stores as $data) {
            $store = Store::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $store->paymentMethods()->sync([
                1,3,4
            ]);

            $hours = [
                ['day_of_week' => 1, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false], // Mon
                ['day_of_week' => 2, 'open_time' => null,   'close_time' => null,   'is_closed' => true ], // Tue
                ['day_of_week' => 3, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false], // Wed
                ['day_of_week' => 4, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false], // Thu
                ['day_of_week' => 5, 'open_time' => '10:00', 'close_time' => '18:00', 'is_closed' => false], // Fri
                ['day_of_week' => 6, 'open_time' => '09:00', 'close_time' => '19:00', 'is_closed' => false], // Sat
                ['day_of_week' => 0, 'open_time' => '09:00', 'close_time' => '19:00', 'is_closed' => false], // Sun
            ];

            foreach ($hours as $h) {
                StoreHour::updateOrCreate(
                    ['store_id' => $store->id, 'day_of_week' => $h['day_of_week']],
                    $h
                );
            }
        }
    }

}
