<?php

namespace App\Providers;

use App\Repositories\AnalyticsRepository;
use App\Repositories\ClientRepository;
use App\Repositories\Contracts\AnalyticsRepository as
    AnalyticsRepositoryContract;
use App\Repositories\Contracts\ClientRepository as ClientRepositoryContract;
use App\Repositories\Contracts\InvitationRepository as
    InvitationRepositoryContract;
use App\Repositories\Contracts\SettingsRepository as
    SettingsRepositoryInterface;
use App\Repositories\Contracts\UserRepository as UserRepositoryInterface;
use App\Repositories\InvitationRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
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
        $this->app->singleton(UserRepositoryInterface::class,
            UserRepository::class);
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
