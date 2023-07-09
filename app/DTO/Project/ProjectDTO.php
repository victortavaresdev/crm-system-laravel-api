<?php

namespace App\DTO\Project;

use App\Http\Requests\Project\ProjectRequest;

class ProjectDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $deadline,
        public readonly ?string $assigned_client,
        public readonly string $status
    ) {
    }

    public static function fromRequest(ProjectRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            deadline: $request->validated('deadline'),
            assigned_client: $request->validated('assigned_client'),
            status: $request->validated('status'),
        );
    }
}
