<?php

namespace App\Policies;

use App\Models\{Client, User};
use Illuminate\Auth\Access\{HandlesAuthorization, Response};

class ClientPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Client $client): Response
    {
        return $this->verifyAuthorization($user, $client);
    }

    public function update(User $user, Client $client): Response
    {
        return $this->verifyAuthorization($user, $client);
    }

    public function delete(User $user, Client $client): Response
    {
        return $this->verifyAuthorization($user, $client);
    }

    private function verifyAuthorization(User $user, Client $client): Response
    {
        return $user->id === $client->user_id ? Response::allow() : Response::deny();
    }
}
