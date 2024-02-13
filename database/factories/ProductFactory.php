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
            'primary_category_id' => 0,
            'artical_number' => fake()->unique()->numberBetween(1000,9999),
            'ean'=> fake()->unique()->ean13(),
            'title' => fake()->word(),
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(),
            'price' => fake()->randomNumber(3, true)
        ];
    }
}
