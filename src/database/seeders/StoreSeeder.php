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
                'address' => '名古屋市中区〇〇',
                'area' => 'sakae',
                'mood' => '静か',
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
                'address' => '名古屋市中区〇〇',
                'area' => 'yabatyo',
                'mood' => 'ペットOK',
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
                'address' => '名古屋市中区〇〇',
                'area' => 'sakurayama',
                'mood' => 'ペットOK',
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
                'address' => '名古屋市中区〇〇',
                'area' => 'meieki',
                'mood' => '韓国風',
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
                'address' => '名古屋市中区〇〇',
                'area' => 'kakuozan',
                'mood' => '女子会',
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
