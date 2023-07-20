<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createUser();
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
                'message',
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
            ->assertStatus(404);
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
                'password_confirmation' => 'password',
            ]);

            // Assert
            $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                ]);

            return true;
        });
    }
}
