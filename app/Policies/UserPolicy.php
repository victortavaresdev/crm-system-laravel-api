<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $owner)
    {
        return $this->verifyAuth($user, $owner);
    }

    public function update(User $user, User $owner)
    {
        return $this->verifyAuth($user, $owner);
    }

    public function delete(User $user, User $owner)
    {
        return $this->verifyAuth($user, $owner);
    }

    private function verifyAuth(User $user, User $owner)
    {
        return $user->id === $owner->id;
    }
}
