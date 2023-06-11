<?php

namespace App\Http\Controllers\Projects;

use App\Models\Project;
use App\Http\Controllers\Controller;
use App\DTO\Project\{CreateProjectDTO, UpdateProjectDTO};
use App\Http\Requests\Project\{StoreProjectRequest, UpdateProjectRequest};
use App\Http\Resources\Project\{ProjectCollection, ProjectResource};
use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use App\Services\Project\ProjectService;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/projects/create",
     *     tags={"Projects"},
     *     summary="Create project",
     *     operationId="create-project",
     *     security={{"bearerToken":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", example="Titulo do projeto"),
     *             @OA\Property(property="description", example="Descrição do projeto"),
     *             @OA\Property(property="deadline", example="10/10/2023"),
     *             @OA\Property(property="status", example="open"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="400",description="Bad Request"),
     *     @OA\Response(response="409",description="Conflict"),
     * )
     */
    public function store(StoreProjectRequest $request): JsonResource
    {
        $dto = CreateProjectDTO::fromRequest($request);
        $project = $this->projectService->store($dto);

        return new ProjectResource($project);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/projects",
     *     tags={"Projects"},
     *     summary="Get projects list",
     *     operationId="get-projects",
     *     security={{"bearerToken":{}}},
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     * )
     */
    public function index(): ResourceCollection
    {
        $projects = $this->projectService->index();

        return new ProjectCollection($projects);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/projects/{id}",
     *     tags={"Projects"},
     *     summary="Get project data",
     *     operationId="get-project",
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
        $project = $this->isUserAuthorized($id, 'view');

        return new ProjectResource($project);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/projects/{id}/update",
     *     tags={"Projects"},
     *     summary="Update project data",
     *     operationId="update-project",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", example="Titulo do projeto updated"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function update(string $id, UpdateProjectRequest $request): JsonResource
    {
        $this->isUserAuthorized($id, 'update');

        $dto = UpdateProjectDTO::fromRequest($request);
        $project = $this->projectService->update($id, $dto);

        return new ProjectResource($project);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/projects/{id}/delete",
     *     tags={"Projects"},
     *     summary="Delete project data",
     *     operationId="delete-project",
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
        $this->projectService->destroy($id);
    }

    private function isUserAuthorized(string $id, string $action): Project|null
    {
        $project = $this->projectService->show($id);
        $this->authorize($action, $project);

        return $project;
    }
}
