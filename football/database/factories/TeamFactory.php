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
        return [
            'name' => \ucwords($this->faker->unique()->city . ' ' . $this->faker->word),
            'coach' => $this->faker->name(),
            'category' => 'Category ' . (string) $this->faker->randomDigit(),
            'budget' => $this->faker->randomFloat(2, 800000,1000000)
        ];
    }
}
