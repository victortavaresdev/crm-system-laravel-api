<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{

    public function definition(): array
    {
        return [
            'contact_name' => fake()->firstName(),
            'contact_email' => fake()->unique()->safeEmail(),
            'contact_phone_number' => '(11) 98080-3030',
            'company_name' => fake()->company(),
            'company_address' => fake()->address(),
            'company_city' => fake()->city(),
            'company_zip' => '20030-900',
            'user_id' => User::factory()->create()->id
        ];
    }
}
