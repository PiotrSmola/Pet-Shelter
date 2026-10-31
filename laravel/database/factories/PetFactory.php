<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(['Dog', 'Cat']),
            'breed' => 'Mixed',
            'age' => fake()->numberBetween(0, 15),
            'weight' => fake()->randomFloat(1, 1, 40),
            'photo_path' => 'photos/test.jpg',
            'description' => fake()->sentence(),
        ];
    }
}
