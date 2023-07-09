<?php

namespace App\Http\Controllers\Api\V1\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group User endpoints
 */
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * POST User
     *
     * Create a new user.
     *
     * @response 201 {"message":"Created"}
     * @response 400 {"message": "Bad Request"}
     * @response 409 {"message": "Conflict"}
     */
    public function store(UserRequest $request): JsonResource
    {
        $dto = UserDTO::fromRequest($request);
        $user = $this->userService->store($dto);

        return new UserResource($user);
    }

    /**
     * GET User
     *
     * Get user data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function show(User $user): JsonResource
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * PUT User
     *
     * Update user data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 400 {"message": "Bad Request"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function update(User $user, UserRequest $request): JsonResource
    {
        $this->authorize('update', $user);

        $dto = UserDTO::fromRequest($request);
        $updatedUser = $this->userService->update($user, $dto);

        return new UserResource($updatedUser);
    }

    /**
     * DELETE User
     *
     * Delete user data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function destroy(User $user): void
    {
        $this->authorize('delete', $user);
        $this->userService->destroy($user);
    }
}
