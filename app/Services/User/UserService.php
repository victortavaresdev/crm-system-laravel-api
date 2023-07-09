<?php

namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\Exceptions\Custom\ConflictException;
use App\Models\User;

class UserService
{
    public function store(UserDTO $dto): User
    {
        $userEmail = User::where('email', $dto->email)->first();
        if ($userEmail)
            throw new ConflictException('Email already registered');

        $user = User::create((array) $dto);

        return $user;
    }

    public function update(User $user, UserDTO $dto): User|null
    {
        $user->update((array) $dto);

        return $user;
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }
}
