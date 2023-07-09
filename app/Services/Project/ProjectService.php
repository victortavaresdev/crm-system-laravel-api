<?php

namespace App\Services\Project;

use App\DTO\Project\ProjectDTO;
use App\Exceptions\Custom\NotFoundException;
use App\Models\Client;
use App\Models\Project;

class ProjectService
{
    public function store(ProjectDTO $dto): Project
    {
        $client = Client::where('contact_name', $dto->assigned_client)->first();
        if (!$client)
            throw new NotFoundException('Client not found');

        $project = auth()->user()->projects()->create(['client_id' => $client->id, ...(array) $dto]);

        return $project;
    }

    public function index()
    {
        $projects = auth()->user()->projects()->paginate(10);

        return $projects;
    }

    public function update(Project $project, ProjectDTO $dto): Project|null
    {
        $project->update((array) $dto);

        return $project;
    }

    public function destroy(Project $project): void
    {
        $project->delete();
    }
}
