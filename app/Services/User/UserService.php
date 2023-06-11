<?php

namespace App\Services\User;

use App\Models\User;
use App\DTO\User\{CreateUserDTO, UpdateUserDTO};
use App\Exceptions\CustomExceptions\{ConflictException, NotFoundException};
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function store(CreateUserDTO $dto): User
    {
        $userEmail = $this->userRepository->findByEmail($dto->email);
        if ($userEmail) throw new ConflictException('Email already registered');

        $user = $this->userRepository->create($dto);
        return $user;
    }

    public function show(string $id): User|null
    {
        $user = $this->userRepository->findById($id);
        if (!$user) throw new NotFoundException('User not found');

        return $user;
    }

    public function update(string $id, UpdateUserDTO $dto): User|null
    {
        $user = $this->userRepository->update($id, $dto);

        return $user;
    }

    public function destroy(string $id): void
    {
        $this->userRepository->delete($id);
    }
}
