<?php

namespace App\Services\Auth;

use App\Models\User;
use App\DTO\Auth\{LoginDTO, ResetPasswordDTO};
use App\Exceptions\CustomExceptions\BadRequestException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\{Auth, Hash, Password};
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function login(LoginDTO $dto): string
    {
        $credentials = ['email' => $dto->email, 'password' => $dto->password];
        if (!Auth::attempt($credentials)) throw new BadRequestException('Invalid credentials');

        $user = $this->userRepository->findByEmail($dto->email);
        $user->tokens()->delete();
        $token = $user->createToken('API Token of ' . $user->first_name)->plainTextToken;

        return $token;
    }

    public function forgotPassword(array $email): string
    {
        $status = Password::sendResetLink($email);
        if ($status !== Password::RESET_LINK_SENT) throw new BadRequestException('Email not registered');

        return $status;
    }

    public function resetPassword(ResetPasswordDTO $dto): string
    {
        $credentials = [
            'token' => $dto->token,
            'email' => $dto->email,
            'password' => $dto->password,
            'password_confirmation' => $dto->password_confirmation
        ];

        $status = Password::reset(
            $credentials,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) throw new BadRequestException('Reset password failure');

        return $status;
    }
}
