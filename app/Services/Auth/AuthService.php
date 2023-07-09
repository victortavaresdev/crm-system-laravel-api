<?php

namespace App\Services\Auth;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\ResetPasswordDTO;
use App\Exceptions\Custom\BadRequestException;
use App\Exceptions\Custom\NotFoundException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    public function login(LoginDTO $dto, string|null $userAgent): string
    {
        $user = User::where('email', $dto->email)->first();
        if (!$user || !Hash::check($dto->password, $user->password))
            throw new BadRequestException('Invalid credentials');

        $device = substr($userAgent ?? '', 0, 255);
        $user->tokens()->delete();

        $token = $user->createToken($device)->plainTextToken;

        return $token;
    }

    public function forgotPassword(array $email): string
    {
        $status = Password::sendResetLink($email);
        if ($status !== Password::RESET_LINK_SENT)
            throw new NotFoundException('Email not found');

        return $status;
    }

    public function resetPassword(ResetPasswordDTO $dto): string
    {
        $status = Password::reset(
            (array) $dto,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET)
            throw new BadRequestException('Reset password failure');

        return $status;
    }
}
