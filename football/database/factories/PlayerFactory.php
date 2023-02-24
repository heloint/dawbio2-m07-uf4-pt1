<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakeDate = $this->faker->dateTimeBetween($startDate = '-40 years', $endDate = '-18years');
        $formatedFakeDate = $fakeDate->format('dmY');

        if (\strlen($formatedFakeDate) !== 8) {
            $formatedFakeDate = "0{$formatedFakeDate}";
        }

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $formatedFakeDate,
            'salary' => $this->faker->randomFloat(2, 5000, 8000)
        ];
    }
}
