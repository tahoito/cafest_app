<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Store;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = User::pluck('id')->all();
        $storeIds = Store::pluck('id')->all();

        if (empty($userIds) || empty($storeIds)) {
            return; // どっちか0なら作れないので終了
        }

        // 例：30件作る
        for ($i = 0; $i < 30; $i++) {
            Review::create([
                'user_id' => fake()->randomElement($userIds),
                'store_id' => fake()->randomElement($storeIds),
                'rating' => fake()->numberBetween(1, 5),
                'body' => fake()->realText(80),
            ]);
        }
    }
}
