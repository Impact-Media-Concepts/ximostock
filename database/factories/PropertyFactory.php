<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Process\FakeProcessResult;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['multiselect','singleselect','number','text','bool']);
        $options= [];
        if($type === 'multiselect' || $type == 'singleselect'){
            $options = fake()->words(4);
        }
        $values = json_encode([ 'type' => $type, 'options' => $options]);

        return [
            'name' => fake()->unique()->word(),
            'values' => $values
        ];
    }
}
