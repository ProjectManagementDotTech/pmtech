<?php

namespace App\Console\Commands;

use App\Repositories\ProjectRepository;
use Illuminate\Console\Command;

class AssignWorkspaceOwnersToProjects extends Command
{
    //region Public Access

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projects = ProjectRepository::all(TRUE);

        $this->output->progressStart($projects->count());

        foreach($projects as $project) {
            $workspace = $project->workspace;
            $user = $workspace->ownerUser;
            /*
             * Make sure we do not have the association multiple times in the
             * pivot table.
             */
            $project->users()->detach($user->id);
            $project->users()->attach($user->id);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    //endregion

    //region Protected Attributes

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:assign-workspace-owners';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the workspace owner to each project';

    //endregion
}
