<?php

namespace Tests\Feature\Project;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Client $client;
    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
        $this->client = $this->createClient();
        $this->project = $this->createProject(null, ['user_id' => $this->user->id]);
    }

    public function test_create_project_with_valid_data_successful(): void
    {
        // Arrange
        $projectData = Project::factory()->raw([
            'assigned_client' => $this->client->contact_name
        ]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->postJson("{$this->projectURI}/create", $projectData);

        // Assert
        $response
            ->assertStatus(201)
            ->assertJsonCount(5, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_create_project_with_invalid_data_returns_error(): void
    {
        // Arrange
        $projectData = Project::factory()->raw([
            'title' => '',
        ]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->postJson("{$this->projectURI}/create", $projectData);

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_get_list_of_projects_paginated_by_10_successful(): void
    {
        // Arrange
        $this->createProject(12, ['user_id' => $this->user->id]);

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson($this->projectURI);

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonIsArray('data');
    }

    public function test_get_project_data_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->getJson("{$this->projectURI}/{$this->project->id}");

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonIsObject('data');
    }

    public function test_update_project_with_valid_data_successful(): void
    {
        // Arrange
        $updatedProject = Project::factory()->raw();

        // Act
        $response = $this
            ->actingAs($this->user)
            ->putJson("{$this->projectURI}/{$this->project->id}/update", $updatedProject);

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseHas('projects', ['title' => $updatedProject['title']]);
    }

    public function test_update_project_with_invalid_data_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->putJson("{$this->projectURI}/{$this->project->id}/update", ['title' => '']);

        // Assert
        $response
            ->assertStatus(422);
    }

    public function test_delete_project_successful(): void
    {
        // Arrange

        // Act
        $response = $this
            ->actingAs($this->user)
            ->delete("{$this->projectURI}/{$this->project->id}/delete");

        // Assert
        $response
            ->assertStatus(200);
        $this
            ->assertDatabaseMissing('projects', $this->project->toArray())
            ->assertDatabaseCount('projects', 0);
    }
}
