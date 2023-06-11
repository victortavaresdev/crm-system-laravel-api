<?php

namespace App\Repositories\User;

use App\Models\User;
use App\DTO\User\{CreateUserDTO, UpdateUserDTO};
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserEloquentORM implements UserRepositoryInterface
{
    public function create(CreateUserDTO $dto): User
    {
        $createdUser = User::create(
            [
                'first_name' => $dto->first_name,
                'last_name' => $dto->last_name,
                'email' => $dto->email,
                'password' => $dto->password,
                'address' => $dto->address,
                'phone_number' => $dto->phone_number,
            ]
        );

        return $createdUser;
    }

    public function findById(string $id): User|null
    {
        $userData = User::find($id);
        return $userData;
    }

    public function findByEmail(string $email): User|null
    {
        $userData = User::where(['email' => $email])->first();
        return $userData;
    }

    public function update(string $id, UpdateUserDTO $dto): User|null
    {
        $updatedUser = User::find($id);
        $updatedUser->update(
            array_filter(
                [
                    'first_name' => $dto->first_name,
                    'last_name' => $dto->last_name,
                    'email' => $dto->email,
                    'password' => $dto->password,
                    'address' => $dto->address,
                    'phone_number' => $dto->phone_number,
                ]
            )
        );

        return $updatedUser;
    }

    public function delete(string $id): void
    {
        $user = User::find($id);
        $user->delete();
    }
}
