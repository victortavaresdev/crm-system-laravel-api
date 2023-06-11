<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\DTO\Auth\{LoginDTO, ResetPasswordDTO};
use App\Http\Requests\Auth\{LoginRequest, ResetPasswordRequest};
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary="Authenticate user",
     *     operationId="login",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", example="teste@gmail.com"),
     *             @OA\Property(property="password", example="teste123"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     * )
     */
    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromRequest($request);
        $token = $this->authService->login($dto);

        return response()->json([
            'accessToken' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Auth"},
     *     summary="Revoke API Token from user",
     *     operationId="logout",
     *     security={{"bearerToken":{}}},
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'revoked' => true
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Auth"},
     *     summary="Get user profile data",
     *     operationId="profile",
     *     security={{"bearerToken":{}}},
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     * )
     */
    public function getProfile(Request $request)
    {
        return $request->user();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/forgot-password",
     *     tags={"Auth"},
     *     summary="Send forgot password link to user email",
     *     operationId="forgot-password",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", example="teste@gmail.com"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     * )
     */
    public function forgotPassword(Request $request)
    {
        $email = $request->validate(['email' => 'required|email']);
        $this->authService->forgotPassword($email);

        return response()->json([
            'message' => 'Email sent successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/reset-password",
     *     tags={"Auth"},
     *     summary="Reset user password",
     *     operationId="reset-password",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", example="YOUR TOKEN"),
     *             @OA\Property(property="email", example="teste@gmail.com"),
     *             @OA\Property(property="password", example="password123"),
     *             @OA\Property(property="password_confirmation", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $dto = ResetPasswordDTO::fromRequest($request);
        $this->authService->resetPassword($dto);

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }
}
