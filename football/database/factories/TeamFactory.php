<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        // FIELDS
        //· name (string, unique)
        //· coach (string)
        //· category (string)
        //· budget (double)
        return [
            'name' => $this->faker->unique()->name,
            'coach' => $this->faker->name() . ' ' . $this->faker->lastName(),
            'category' => 'Category ' . (string) $this->faker->randomDigit(),
            'budget' => $this->faker->randomFloat(2)
        ];
    }
}
