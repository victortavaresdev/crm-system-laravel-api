<?php

namespace App\Providers;

use App\Repositories\Client\ClientEloquentORM;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\User\UserEloquentORM;
use App\Repositories\Project\ProjectEloquentORM;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserEloquentORM::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientEloquentORM::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectEloquentORM::class);

        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
