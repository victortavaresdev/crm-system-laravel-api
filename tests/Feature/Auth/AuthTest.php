<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser(null, [
            'email' => 'teste@gmail.com',
            'password' => 'teste123',
        ]);
    }

    public function test_login_user_with_correct_credentials(): void
    {
        // Arrange

        // Act
        $response = $this->post("{$this->authURI}/login", [
            'email' => 'teste@gmail.com',
            'password' => 'teste123'
        ]);

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'accessToken'
            ]);
    }

    public function test_login_user_with_incorrect_credentials_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this->post("{$this->authURI}/login", [
            'email' => 'teste@gmail.com',
            'password' => 'testeteste'
        ]);

        // Assert
        $response
            ->assertStatus(400);
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

    public function test_user_can_receive_password_reset_link_with_registered_email(): void
    {
        // Arrange
        Notification::fake();

        // Act
        $response = $this->post("{$this->authURI}/forgot-password", ['email' => $this->user->email]);

        // Assert
        Notification::assertSentTo($this->user, ResetPassword::class);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_user_cannot_receive_password_reset_link_with_incorrect_email(): void
    {
        // Arrange
        Notification::fake();

        // Act
        $response = $this->post("{$this->authURI}/forgot-password", ['email' => 'wrong@gmail.com']);

        // Assert
        Notification::assertNothingSent();
        $response
            ->assertStatus(400);
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        // Arrange
        Notification::fake();
        $user = $this->user;

        $this->post("{$this->authURI}/forgot-password", ['email' => $user->email]);

        // Act
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post("{$this->authURI}/reset-password", [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);

            // Assert
            $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'message'
                ]);

            return true;
        });
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_user_account(): void
    {
        // Arrange
        $user1 = $this->createUser(null, ['email' => 'teste1@gmail.com']);
        $user2 = $this->createUser(null, ['email' => 'teste2@gmail.com']);

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("/api/v1/users/{$user2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_client_data(): void
    {
        // Arrange
        $user1 = $this->createUser(null, ['email' => 'teste1@gmail.com']);
        $user2 = $this->createUser(null, ['email' => 'teste2@gmail.com']);

        $client2 = $this->createClient(null, ['user_id' => $user2->id]);

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("/api/v1/clients/{$client2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_authenticated_user_is_not_authorized_to_access_another_project_data(): void
    {
        // Arrange
        $user1 = $this->createUser(null, ['email' => 'teste1@gmail.com']);
        $user2 = $this->createUser(null, ['email' => 'teste2@gmail.com']);

        $client2 = $this->createClient(null, ['user_id' => $user2->id]);
        $project2 = $this->createProject(null, ['user_id' => $user2->id, 'client_id' => $client2->id]);

        // Act
        $response = $this
            ->actingAs($user1)
            ->get("/api/v1/projects/{$project2->id}");

        // Assert
        $response
            ->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_private_endpoints(): void
    {
        // Arrange

        // Act
        $responseClients = $this->get('/api/v1/clients', $this->header);
        $responseProjects = $this->get('/api/v1/projects', $this->header);

        // Assert
        $responseClients
            ->assertStatus(401);
        $responseProjects
            ->assertStatus(401);
    }
}
