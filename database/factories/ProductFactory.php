<?php

namespace Database\Factories;

use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
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
        return [
            'work_space_id' => 1,
            'sku' => fake()->word() . '-' . fake()->unique()->numberBetween(0, 999999),
            'ean' => fake()->unique()->ean13(),
            'title' => fake()->word(). '-' . fake()->word(). '-' . fake()->numberBetween(0, 999999),
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(),
            'price' => fake()->numberBetween(1, 1000),
            'discount' => fake()->randomNumber(2, true),
            'type' => 'simple'
        ];
    }
}
