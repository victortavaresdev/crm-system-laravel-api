<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\DTO\Project\ProjectDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use App\Services\Project\ProjectService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group Project endpoints
 */
class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {
    }

    /**
     * POST Project
     *
     * Create a new project.
     *
     * @authenticated
     *
     * @response 201 {"message":"Created"}
     * @response 400 {"message": "Bad Request"}
     * @response 409 {"message": "Conflict"}
     */
    public function store(ProjectRequest $request): JsonResource
    {
        $dto = ProjectDTO::fromRequest($request);
        $project = $this->projectService->store($dto);

        return new ProjectResource($project);
    }

    /**
     * GET Projects
     *
     * Get projects list.
     *
     * @authenticated
     *
     * @response 200 {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     */
    public function index(): ResourceCollection
    {
        $projects = $this->projectService->index();

        return ProjectResource::collection($projects);
    }

    /**
     * GET Project
     *
     * Get project data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function show(Project $project): JsonResource
    {
        $this->authorize('view', $project);

        return new ProjectResource($project);
    }

    /**
     * PUT Project
     *
     * Update project data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 400 {"message": "Bad Request"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function update(Project $project, ProjectRequest $request): JsonResource
    {
        $this->authorize('update', $project);

        $dto = ProjectDTO::fromRequest($request);
        $project = $this->projectService->update($project, $dto);

        return new ProjectResource($project);
    }

    /**
     * DELETE Project
     *
     * Delete project data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function destroy(Project $project): void
    {
        $this->authorize('delete', $project);
        $this->projectService->destroy($project);
    }
}
