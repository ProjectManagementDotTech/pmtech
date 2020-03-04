<?php

namespace App\Providers;

use App\Repositories\ClientRepository;
use App\Repositories\Contracts\ClientRepository as ClientRepositoryContract;
use App\Repositories\Contracts\InvitationRepository as
    InvitationRepositoryContract;
use App\Repositories\InvitationRepository;
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
        $this->app->singleton(ClientRepositoryContract::class,
            ClientRepository::class);
        $this->app->singleton(InvitationRepositoryContract::class,
            InvitationRepository::class
        );
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
