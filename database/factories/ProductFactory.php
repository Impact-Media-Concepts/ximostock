<?php

namespace Database\Factories;

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
            'sku' => fake()->word() . '-' . fake()->unique()->numberBetween(0, 999999),
            'ean' => fake()->unique()->ean13(),
            'title' => fake()->sentence(),
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(),
            'price' => fake()->randomNumber(3, true)
        ];
    }
}
