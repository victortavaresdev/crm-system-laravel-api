<?php

namespace Tests;

use App\Models\{Client, Project, User};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public string $authURI = '/api/v1/auth';
    public string $userURI = '/api/v1/users';
    public string $clientURI = '/api/v1/clients';
    public string $projectURI = '/api/v1/projects';

    public array $header = ['Accept' => 'application/json'];

    public function createUser(int|null $count = null, array $items = []): User
    {
        return User::factory($count)->create($items);
    }

    public function createClient(int|null $count = null, array $items = []): Collection|Client
    {
        return Client::factory($count)->create($items);
    }

    public function createProject(int|null $count = null, array $items = []): Collection|Project
    {
        return Project::factory($count)->create($items);
    }
}
