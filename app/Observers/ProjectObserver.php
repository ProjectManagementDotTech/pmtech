<?php

namespace App\Observers;

use App\Events\WorkspaceUpdated;
use App\Project;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Project  $project
     * @return void
     */
    public function created(Project $project)
    {
        event(new WorkspaceUpdated($project->workspace_id));
    }
}
