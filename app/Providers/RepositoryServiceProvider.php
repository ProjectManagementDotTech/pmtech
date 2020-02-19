<?php

namespace App\Providers;

use App\Repositories\Interfaces\InvitationRepository as
    InvitationRepositoryInterface;
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
        $this->app->singleton(
            InvitationRepositoryInterface::class, InvitationRepository::class
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
