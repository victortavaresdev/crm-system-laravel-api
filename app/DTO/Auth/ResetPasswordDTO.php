<?php

namespace App\DTO\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordDTO
{
    public function __construct(
        public readonly string $token,
        public readonly string $email,
        public readonly string $password,
        public readonly string $password_confirmation
    ) {
    }

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            token: $request->validated('token'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            password_confirmation: $request->validated('password_confirmation'),
        );
    }
}
