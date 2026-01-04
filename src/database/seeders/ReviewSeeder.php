<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use App\Models\Tag;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            
            return;
        }

        $tags = Tag::pluck('id', 'name'); 
        $stores = Store::all();
        if ($stores->isEmpty()) return;

        foreach ($stores as $store) {
            $r1 = Review::create([
                'user_id' => $user->id,
                'store_id' => $store->id,
                'rating' => 5,
                'body' => '雰囲気が良くてまた来たいです。',
            ]);

            $attach1 = collect(['映え', 'スイーツ', '作業', '静か', 'デート', '夜カフェ', 'モーニング'])
                ->map(fn($name) => $tags[$name] ?? null)
                ->filter()
                ->take(2)
                ->values()
                ->all();

            if (!empty($attach1)) {
                $r1->tags()->sync($attach1);
            }

            // 2個目（任意）
            $r2 = Review::create([
                'user_id' => $user->id,
                'store_id' => $store->id,
                'rating' => 4,
                'body' => 'コーヒーが美味しかったです。',
            ]);

            $attach2 = collect(['コーヒー', 'ひとり', '推し活'])
                ->map(fn($name) => $tags[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            if (!empty($attach2)) {
                $r2->tags()->sync($attach2);
            }
        }
    }
}
