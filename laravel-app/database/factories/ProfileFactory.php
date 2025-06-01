<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Enums\ProfileStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_name'     => fake()->lastName(),
            'first_name'    => fake()->firstName(),
            'image'         => null,
            'status'        => fake()->randomElement(ProfileStatus::class),
        ];
    }
}
