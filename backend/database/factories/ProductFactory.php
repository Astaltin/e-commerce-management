<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'name' => fake()->words([2, 3, 4, 5], true),
        //     'description' => fake()->sentence([3, 4, 5]),
        //     'price' => fake()->randomFloat(2, max: 999),
        //     'category_id' => null
        // ];

        return [
            'name' => fake()->words(asText: true),
            'description' => fake()->sentence,
            'price' => fake()->randomFloat(2, max: 999),
            'category_id' => null
        ];
    }
}
