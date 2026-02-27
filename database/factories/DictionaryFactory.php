<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dictionary>
 */
class DictionaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake(config('app.locale'))->name(),
            'language_1_id' => fake()->randomElement([1, 2, 3, 4, 5]),
            'language_2_id' => fake()->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
