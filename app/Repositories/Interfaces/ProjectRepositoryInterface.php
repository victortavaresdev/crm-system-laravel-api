<?php

namespace App\Repositories\Interfaces;

use App\Models\Project;
use App\DTO\Project\{CreateProjectDTO, UpdateProjectDTO};

interface ProjectRepositoryInterface
{
    public function create(CreateProjectDTO $dto): Project;
    public function findAll();
    public function findById(string $id): Project|null;
    public function update(string $id, UpdateProjectDTO $dto): Project|null;
    public function delete(string $id): void;
}
