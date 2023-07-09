<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_create_user_with_valid_data_successful(): void
    {
        // Arrange
        $userData = User::factory()->raw();

        // Act
        $response = $this
            ->postJson("{$this->userURI}/create", $userData);

        // Assert
        $response
            ->assertStatus(201)
            ->assertJsonCount(6, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_create_user_with_invalid_data_returns_error(): void
    {
        // Arrange
        $userData = User::factory()->raw([
            'first_name' => '',
        ]);

        // Act
        $response = $this
            ->postJson("{$this->userURI}/create", $userData);

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_create_user_with_registered_email_returns_error(): void
    {
        // Arrange
        $userData = User::factory()->raw([
            'email' => $this->user->email,
        ]);

        // Act
        $response = $this
            ->postJson("{$this->userURI}/create", $userData);

        // Assert
        $response
            ->assertStatus(409);
    }

    public function test_get_user_data_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->userURI}/{$this->user->id}");

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_query_user_with_incorrect_id_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->userURI}/wrongid");

        // Assert
        $response
            ->assertStatus(404);
    }

    public function test_update_user_with_valid_data_successful(): void
    {
        // Arrange
        $updatedUser = User::factory()->raw();

        // Act
        $response = $this
            ->actingAs($this->user)
            ->putJson(
                "{$this->userURI}/{$this->user->id}/update",
                $updatedUser
            );

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseHas('users', ['first_name' => $updatedUser['first_name']]);
    }

    public function test_update_user_with_invalid_data_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->putJson(
                "{$this->userURI}/{$this->user->id}/update",
                ['first_name' => '']
            );

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_delete_user_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->delete("{$this->userURI}/{$this->user->id}/delete");

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseMissing('users', $this->user->toArray())
            ->assertDatabaseCount('users', 0);
    }
}
