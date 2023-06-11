<?php

namespace App\Policies;

use App\Models\{Project, User};
use Illuminate\Auth\Access\{HandlesAuthorization, Response};

class ProjectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Project $project): Response
    {
        return $this->verifyAuthorization($user, $project);
    }

    public function update(User $user, Project $project): Response
    {
        return $this->verifyAuthorization($user, $project);
    }

    public function delete(User $user, Project $project): Response
    {
        return $this->verifyAuthorization($user, $project);
    }

    private function verifyAuthorization(User $user, Project $project): Response
    {
        return $user->id === $project->user_id ? Response::allow() : Response::deny();
    }
}
