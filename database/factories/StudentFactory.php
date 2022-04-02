<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['m', 'f']);

        return [
            "name" => $this->faker->name($gender === "m" ? "male" : "female"),
            "email" => $this->faker->safeEmail(),
            "date_birth" => $this->faker->dateTimeBetween("-30 years","-18 years"),
            "height" => $this->faker->randomFloat(2,1.60, 1.99),
            "weight" => $this->faker->numberBetween(60, 85),
            "gender" => $gender
        ];
    }
}
