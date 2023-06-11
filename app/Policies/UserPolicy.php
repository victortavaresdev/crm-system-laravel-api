<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\{HandlesAuthorization, Response};

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $owner): Response
    {
        return $this->verifyAuthorization($user, $owner);
    }

    public function update(User $user, User $owner): Response
    {
        return $this->verifyAuthorization($user, $owner);
    }

    public function delete(User $user, User $owner): Response
    {
        return $this->verifyAuthorization($user, $owner);
    }

    private function verifyAuthorization(User $user, User $owner): Response
    {
        return $user->id === $owner->id ? Response::allow() : Response::deny();
    }
}
