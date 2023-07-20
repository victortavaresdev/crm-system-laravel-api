<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_correct_credentials_returns_token(): void
    {
        // Arrange
        $user = $this->createUser();

        // Act
        $response = $this->post("{$this->authURI}/login", [
            'email' => $user->email,
            'password' => 'test_password',
        ]);

        // Assert
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'accessToken',
            ]);
    }

    public function test_login_with_incorrect_credentials_returns_error(): void
    {
        // Arrange

        // Act
        $response = $this->post("{$this->authURI}/login", [
            'email' => 'wrong@gmail.com',
            'password' => 'wrong_password',
        ]);

        // Assert
        $response
            ->assertStatus(400);
    }
}
