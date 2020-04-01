<?php

namespace App\Providers;

use App\Observers\ProjectObserver;
use App\Observers\TaskObserver;
use App\Observers\TimesheetEntryObserver;
use App\Observers\UserObserver;
use App\Observers\WorkspaceObserver;
use App\Project;
use App\Task;
use App\TimesheetEntry;
use App\User;
use App\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_DEBUG')) {
            DB::listen(function ($query) {
                File::append(storage_path('logs/query.log'),
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' .
                    PHP_EOL);
            });
        }

        Project::observe(ProjectObserver::class);
        Task::observe(TaskObserver::class);
        TimesheetEntry::observe(TimesheetEntryObserver::class);
        User::observe(UserObserver::class);
        Workspace::observe(WorkspaceObserver::class);
    }
}
