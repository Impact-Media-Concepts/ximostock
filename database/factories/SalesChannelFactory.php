<?php

namespace Database\Factories;

use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesChannels>
 */
class SalesChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'work_space_id' => WorkSpace::factory()->create(),
            'channel_type'=> 'WooCommerce',
            'url' => fake()->url(),
            'api_key' => fake()->url()
        ];
    }
}
