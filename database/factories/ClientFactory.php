<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contact_name' => fake()->firstName(),
            'contact_email' => fake()->unique()->safeEmail(),
            'contact_phone_number' => fake()->phoneNumber(),
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'company_city' => fake()->city(),
            'company_zip' => fake()->postcode(),
            'user_id' => User::factory()->create()->id
        ];
    }
}
