<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Client $client)
    {
        return $this->verifyAuth($user, $client);
    }

    public function update(User $user, Client $client)
    {
        return $this->verifyAuth($user, $client);
    }

    public function delete(User $user, Client $client)
    {
        return $this->verifyAuth($user, $client);
    }

    private function verifyAuth(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }
}
