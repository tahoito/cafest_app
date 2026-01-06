<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Cafe Lumiere',
                'email' => 'lumiere@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中区栄1-24-38 エムズハウス栄 2F',
                'phone' => '052-123-4567',
                'area' => '栄',
                'mood' => '静か',
                'description' => '落ち着いた空間で、こだわりのコーヒーと焼き菓子を楽しめるカフェです。',
                'budget_min' => 800,
                'budget_max' => 1600,
                'open_time' => '08:00:00',
                'close_time' => '19:00:00',
                'closed_days' => ['tue'],
            ],
            [
                'name' => 'Morining',
                'email' => 'morininge@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中区栄3丁目31-13',
                'phone' => '1234567',
                'area' => '矢場町',
                'mood' => 'ペットOK',
                'description' => '落ち着いた空間で、こだわりのコーヒーと焼き菓子を楽しめるカフェです。',
                'budget_min' => 800,
                'budget_max' => 1600,
                'open_time' => '08:00:00',
                'close_time' => '19:00:00',
                'closed_days' => ['tue'],
            ],
            [
                'name' => 'フラワーカフェ',
                'email' => 'morininge@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市昭和区桜山町',
                'phone' => '070908654',
                'area' => '桜山',
                'mood' => 'ペットOK',
                'description' => '落ち着いた空間で、こだわりのコーヒーと焼き菓子を楽しめるカフェです。',
                'budget_min' => 3000,
                'budget_max' => 4000,
                'open_time' => '08:00:00',
                'close_time' => '19:00:00',
                'closed_days' => ['tue'],
            ],
            [
                'name' => 'cafest',
                'email' => 'cafest@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中村区名駅1丁目1番',
                'phone' => '03040118',
                'area' => '名駅',
                'mood' => '韓国風',
                'description' => '落ち着いた空間で、こだわりのコーヒーと焼き菓子を楽しめるカフェです。',
                'budget_min' => 1000,
                'budget_max' => 2000,
                'open_time' => '08:00:00',
                'close_time' => '19:00:00',
                'closed_days' => ['tue'],
            ],
            [
                'name' => 'ミラクル',
                'email' => 'yes@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市千種区未盛通1丁目7番地',
                'phone' => '5678903',
                'area' => '覚王山',
                'mood' => '女子会',
                'description' => '落ち着いた空間で、こだわりのコーヒーと焼き菓子を楽しめるカフェです。',
                'budget_min' => 5000,
                'budget_max' => 7000,
                'open_time' => '08:00:00',
                'close_time' => '19:00:00',
                'closed_days' => ['tue'],
            ],
        ];

        foreach ($stores as $data) {
            Store::updateOrCreate(
                ['email' => $data['email']], 
                $data
            );
        }
    }
}
