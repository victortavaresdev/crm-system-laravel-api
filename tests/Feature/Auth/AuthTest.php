<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_authenticated_user_get_profile_data(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->get("{$this->authURI}/profile");

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(8);
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_user_account(): void
    {
        // Arrange
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("{$this->userURI}/{$user2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_user_client_data(): void
    {
        // Arrange
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        $client2 = $this->createClient(null, ['user_id' => $user2->id]);

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("{$this->clientURI}/{$client2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_user_project_data(): void
    {
        // Arrange
        $user1 = $this->createUser();
        $user2 = $this->createUser();

        $client2 = $this->createClient(null, ['user_id' => $user2->id]);
        $project2 = $this->createProject(null, ['user_id' => $user2->id, 'client_id' => $client2->id]);

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("{$this->projectURI}/{$project2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_private_endpoints(): void
    {
        // Arrange

        // Act
        $responseClients = $this->get("{$this->clientURI}", $this->header);
        $responseProjects = $this->get("{$this->projectURI}", $this->header);

        // Assert
        $responseClients
            ->assertStatus(401);
        $responseProjects
            ->assertStatus(401);
    }
}
