<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;

class AssignUsersToOwnedWorkspaces extends Command
{
    //region Public Access

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = UserRepository::verifiedUsers();

        $this->output->progressStart(count($users));
        foreach($users as $user) {
            foreach($user->ownedWorkspaces as $workspace) {
                $user->workspaces()->attach($workspace->id);
            }
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
    protected $signature = 'user:assign-to-owned-workspaces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign each user to their owned workspaces';

    //endregion
}
