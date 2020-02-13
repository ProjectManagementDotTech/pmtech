<?php

namespace App\Observers;

use App\Events\WorkspaceUpdated;
use App\Workspace;

class WorkspaceObserver
{
    /**
     * Handle the workspace "updated" event.
     *
     * @param  \App\Workspace  $workspace
     * @return void
     */
    public function updated(Workspace $workspace)
    {
        event(new WorkspaceUpdated($workspace));
    }
}
