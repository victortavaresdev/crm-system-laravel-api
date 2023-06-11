<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\{Client, Project, User};
use App\Policies\{ClientPolicy, ProjectPolicy, UserPolicy};
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Client::class => ClientPolicy::class,
        Project::class => ProjectPolicy::class
    ];

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return 'https://example.com/reset-link?token=' . $token;
        });
    }
}
