<?php

namespace App\Services\Project;

use App\Models\Project;
use App\DTO\Project\{CreateProjectDTO, UpdateProjectDTO};
use App\Exceptions\CustomExceptions\BadRequestException;
use App\Exceptions\CustomExceptions\NotFoundException;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository,
        protected ProjectRepositoryInterface $projectRepository
    ) {
    }

    public function store(CreateProjectDTO $dto): Project
    {
        $client = $this->clientRepository->findByContactName($dto->assigned_client);
        if (!$client) throw new BadRequestException('Client not registered');

        $dto->assigned_client = $client->id;

        $project = $this->projectRepository->create($dto);
        return $project;
    }

    public function index()
    {
        $projects = $this->projectRepository->findAll();

        return $projects;
    }

    public function show(string $id): Project|null
    {
        $project = $this->projectRepository->findById($id);
        if (!$project) throw new NotFoundException('Project not found');

        return $project;
    }

    public function update(string $id, UpdateProjectDTO $dto): Project|null
    {
        $project = $this->projectRepository->update($id, $dto);

        return $project;
    }

    public function destroy(string $id): void
    {
        $this->projectRepository->delete($id);
    }
}
