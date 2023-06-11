<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\DTO\Project\{CreateProjectDTO, UpdateProjectDTO};
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProjectEloquentORM implements ProjectRepositoryInterface
{
    public function create(CreateProjectDTO $dto): Project
    {
        $createdProject = Project::create(
            [
                'title' => $dto->title,
                'description' => $dto->description,
                'user_id' => auth()->user()->id,
                'client_id' => $dto->assigned_client,
                'deadline' => $dto->deadline,
                'status' => $dto->status,
            ]
        );

        return $createdProject;
    }

    public function findAll()
    {
        $userProjects = Auth::user()->projects()->paginate(10);

        return $userProjects;
    }

    public function findById(string $id): Project|null
    {
        $projectData = Project::find($id);
        return $projectData;
    }

    public function update(string $id, UpdateProjectDTO $dto): Project|null
    {
        $updatedProject = Project::find($id);
        $updatedProject->update(
            array_filter(
                [
                    'title' => $dto->title,
                    'description' => $dto->description,
                    'client_id' => $dto->assigned_client,
                    'deadline' => $dto->deadline,
                    'status' => $dto->status,
                ]
            )
        );

        return $updatedProject;
    }

    public function delete(string $id): void
    {
        $project = Project::find($id);
        $project->delete();
    }
}
