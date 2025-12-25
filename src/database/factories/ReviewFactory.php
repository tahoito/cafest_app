<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;  
use App\Models\Store;  
use App\Models\Review; 
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class; 
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'store_id' => Store::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(3,5),
            'body' => $this->faker->randomElement([
                '可愛くておしゃれで映え写真がいっぱい撮れる！',
                '落ち着いた雰囲気で長居しやすい。',
                'デートにぴったりで居心地がよかった。',
            ]),
        ];
    }
}
