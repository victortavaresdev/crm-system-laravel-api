<?php

namespace App\DTO\Project;

use App\Http\Requests\Project\UpdateProjectRequest;

class UpdateProjectDTO
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $deadline,
        public ?string $assigned_client,
        public readonly ?string $status
    ) {
    }

    public static function fromRequest(UpdateProjectRequest $request): self
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
