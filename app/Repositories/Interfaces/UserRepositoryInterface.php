<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\DTO\User\{CreateUserDTO, UpdateUserDTO};

interface UserRepositoryInterface
{
    public function create(CreateUserDTO $dto): User;
    public function findById(string $id): User|null;
    public function findByEmail(string $email): User|null;
    public function update(string $id, UpdateUserDTO $dto): User|null;
    public function delete(string $id): void;
}
