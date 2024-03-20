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
            'title' => fake()->sentence(),
            'short_description' => fake()->paragraph(),
            'long_description' => fake()->paragraph(),
            'price' => fake()->randomNumber(3, true),
        ];
    }
}
