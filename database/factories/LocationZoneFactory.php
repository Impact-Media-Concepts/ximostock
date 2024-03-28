<?php

namespace Database\Factories;

use App\Models\InventoryLocation;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationZone>
 */
class LocationZoneFactory extends Factory
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
            'name'=>fake()->word(),
            'inventory_location_id' => InventoryLocation::factory()->create()
        ];
    }
}
