<?php

namespace App\DTO\User;

use App\Http\Requests\User\UserRequest;

class UserDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $address,
        public readonly string $phone_number
    ) {
    }

    public static function fromRequest(UserRequest $request): self
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            address: $request->validated('address'),
            phone_number: $request->validated('phone_number'),
        );
    }
}
