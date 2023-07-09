<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::factory()->create(['status' => 'Open']);
        Project::factory()->create(['status' => 'In_Progress']);
    }
}
