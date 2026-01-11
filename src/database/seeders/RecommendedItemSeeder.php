<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecommendedItems;

class RecommendedItemSeeder extends Seeder
{
    public function run(): void
    {
        RecommendedItems::insert([
            [
                'store_id' => 5,
                'name' => 'カフェラテ',
                'price' => 550,
                'description' => '一番人気の定番ラテ',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 5,
                'name' => 'チーズケーキ',
                'price' => 480,
                'description' => 'しっとり濃厚でコーヒーと相性抜群',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 5,
                'name' => 'アボカドトースト',
                'price' => 780,
                'description' => 'ランチにもおすすめの一皿',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
