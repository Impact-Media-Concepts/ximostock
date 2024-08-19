<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
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
            'name' => fake()->word(),
            'company_name' => fake()->word(),
            'website' => fake()->url(),
            'phone_number' => fake()->numberBetween(1000000000, 9999999999)
        ];
    }
}
