<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'test_password',
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'remember_token' => Str::random(10),
        ];
    }
}
