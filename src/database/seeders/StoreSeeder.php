<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Models\Tag;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagMap = Tag::pluck('id', 'name'); 
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
                'name' => 'Night Brew',
                'email' => 'nightbrew@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中村区〇〇',
                'area' => 'meieki',
                'mood' => '落ち着く',
                'budget_min' => 1200,
                'budget_max' => 2800,
                'open_time' => '11:00:00',
                'close_time' => '23:00:00',
                'closed_days' => ['mon'],
            ],
            [
                'name' => 'Morning Toast',
                'email' => 'toast@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市千種区〇〇',
                'area' => 'chikusa',
                'mood' => 'にぎやか',
                'budget_min' => 500,
                'budget_max' => 1200,
                'open_time' => '06:30:00',
                'close_time' => '14:00:00',
                'closed_days' => [],
            ],
            [
                'name' => 'Sweets Atelier',
                'email' => 'sweets@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中区〇〇',
                'area' => 'osukannon',
                'mood' => 'かわいい',
                'budget_min' => 1500,
                'budget_max' => 3500,
                'open_time' => '10:00:00',
                'close_time' => '20:00:00',
                'closed_days' => ['wed'],
            ],
            [
                'name' => 'Study Dock',
                'email' => 'studydock@example.com',
                'password' => Hash::make('password'),
                'address' => '名古屋市中区〇〇',
                'area' => 'fushimi',
                'mood' => '作業向け',
                'budget_min' => 900,
                'budget_max' => 1900,
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'closed_days' => ['sun'],
            ],
        ];

        foreach ($stores as $data) {
            Store::create($data);
        }
    }
}
