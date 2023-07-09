<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->text(20),
            'description' => fake()->text(100),
            'user_id' => User::factory()->create()->id,
            'client_id' => Client::factory()->create()->id,
            'deadline' => fake()->date(),
            'status' => 'Open'
        ];
    }
}
