<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Store;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Store::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'address' => $this->faker->city() . ' ' . $this->faker->streetAddress(),
            'area' => $this->faker->randomElement($areas),
            'rating' => $this->faker->randomFloat(1, 3.0, 5.0),
            'description' => $this->faker->realText(80),
            'image_url' => null,
        ];
    }
}
