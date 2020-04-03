<?php

namespace App\Providers;

use App\Repositories\AnalyticsRepository;
use App\Repositories\ClientRepository;
use App\Repositories\Contracts\AnalyticsRepositoryInterface as
    AnalyticsRepositoryContract;
use App\Repositories\Contracts\ClientRepositoryInterface as ClientRepositoryContract;
use App\Repositories\Contracts\InvitationRepositoryInterface as
    InvitationRepositoryContract;
use App\Repositories\Contracts\SettingsRepositoryInterface as
    SettingsRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface as UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use App\Repositories\InvitationRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AnalyticsRepositoryContract::class,
            AnalyticsRepository::class);
        $this->app->singleton(ClientRepositoryContract::class,
            ClientRepository::class);
        $this->app->singleton(InvitationRepositoryContract::class,
            InvitationRepository::class);
        $this->app->singleton(SettingsRepositoryInterface::class,
            SettingsRepository::class);
        $this->app->singleton(TaskRepositoryInterface::class,
            TaskRepository::class);
        $this->app->singleton(UserRepositoryInterface::class,
            UserRepository::class);
        $this->app->singleton(WorkspaceRepositoryInterface::class,
            WorkspaceRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
