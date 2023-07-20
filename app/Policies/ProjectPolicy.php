<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Project $project)
    {
        return $this->verifyAuth($user, $project);
    }

    public function update(User $user, Project $project)
    {
        return $this->verifyAuth($user, $project);
    }

    public function delete(User $user, Project $project)
    {
        return $this->verifyAuth($user, $project);
    }

    private function verifyAuth(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }
}
