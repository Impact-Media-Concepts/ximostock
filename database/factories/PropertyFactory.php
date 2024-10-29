<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Process\FakeProcessResult;
use Illuminate\Support\Facades\Log;


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

        $type = fake()->randomElement(
            [
                0 => 'multiselect',
                1 => 'singleselect',
                2 => 'number',
                3 => 'text',
                4 => 'bool'
            ]
        );
        Log::info('Factory');
        Log::info($type);
        $options= [];
        if($type === 'multiselect' || $type === 'singleselect'){
            $options = fake()->words(4);
        }
        $values = json_encode(['options' => [ 'Red' , 'Green', 'Blue' ]]);


        Log::info($values);
        return [
            'work_space_id' => 1,
            'name' => fake()->unique()->word(),
            'options' => $values,
            'type' => $type,
        ];
    }
}
