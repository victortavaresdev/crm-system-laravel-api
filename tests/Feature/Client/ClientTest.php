<?php

namespace Tests\Feature\Client;

use App\Models\{Client, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->client = $this->createClient(null, ['user_id' => $this->user->id]);
    }

    public function test_create_client_with_valid_data_successful(): void
    {
        // Arrange
        $clientData = Client::factory()->raw();

        // Act
        $response = $this
            ->actingAs($this->user)
            ->postJson("{$this->clientURI}/create", $clientData);

        // Assert
        $response
            ->assertStatus(201)
            ->assertJsonCount(9, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_create_client_with_invalid_data_returns_error(): void
    {
        // Arrange
        $clientData = Client::factory()->raw([
            'contact_name' => ''
        ]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->postJson("{$this->clientURI}/create", $clientData);

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_create_client_with_registered_email_returns_error(): void
    {
        // Arrange
        $clientData = Client::factory()->raw([
            'contact_email' => $this->client->contact_email
        ]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->postJson("{$this->clientURI}/create", $clientData);

        // Assert
        $response
            ->assertStatus(409);
    }

    public function test_get_list_of_clients_paginated_by_10_successful(): void
    {
        // Arrange
        $this->createClient(12, ['user_id' => $this->user->id]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->clientURI}");

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonIsArray('data');
    }

    public function test_get_client_data_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->clientURI}/{$this->client->id}");

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_query_client_with_incorrect_id_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->clientURI}/wrong-id");

        // Assert
        $response
            ->assertStatus(404);
    }

    public function test_update_client_with_valid_data_successful(): void
    {
        // Arrange

        // Act
        $response = $this->actingAs($this->user)->patchJson(
            "{$this->clientURI}/{$this->client->id}/update",
            ['contact_name' => 'New client']
        );

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseHas('clients', ['contact_name' => 'New client']);
    }

    public function test_update_client_with_invalid_data_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this->actingAs($this->user)->patchJson(
            "{$this->clientURI}/{$this->client->id}/update",
            ['contact_name' => '']
        );

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_delete_client_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->delete("{$this->clientURI}/{$this->client->id}/delete");

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseMissing('clients', $this->client->toArray())
            ->assertDatabaseCount('clients', 0);
    }
}
