<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use App\DTO\User\{CreateUserDTO, UpdateUserDTO};
use App\Http\Controllers\Controller;
use App\Http\Requests\User\{StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/create",
     *     tags={"Users"},
     *     summary="Create user",
     *     operationId="create-user",
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", example="Amanda"),
     *             @OA\Property(property="last_name", example="Torres"),
     *             @OA\Property(property="email", example="amanda@gmail.com"),
     *             @OA\Property(property="password", example="teste123",),
     *             @OA\Property(property="address", example="Rua machado de assis"),
     *             @OA\Property(property="phone_number", example="(11) 98080-4040"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="400",description="Bad Request"),
     *     @OA\Response(response="409",description="Conflict"),
     * )
     */
    public function store(StoreUserRequest $request): JsonResource
    {
        $dto = CreateUserDTO::fromRequest($request);
        $user = $this->userService->store($dto);

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Get user data",
     *     operationId="get-user",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function show(string $id): JsonResource
    {
        $user = $this->isUserAuthorized($id, 'view');

        return new UserResource($user);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/users/{id}/update",
     *     tags={"Users"},
     *     summary="Update user data",
     *     operationId="update-user",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="first_name", example="Amanda updated"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function update(string $id, UpdateUserRequest $request): JsonResource
    {
        $this->isUserAuthorized($id, 'update');

        $dto = UpdateUserDTO::fromRequest($request);
        $updatedUser = $this->userService->update($id, $dto);

        return new UserResource($updatedUser);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}/delete",
     *     tags={"Users"},
     *     summary="Delete user data",
     *     operationId="delete-user",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function destroy(string $id): void
    {
        $this->isUserAuthorized($id, 'delete');
        $this->userService->destroy($id);
    }

    private function isUserAuthorized(string $id, string $action): User|null
    {
        $user = $this->userService->show($id);
        $this->authorize($action, $user);

        return $user;
    }
}
