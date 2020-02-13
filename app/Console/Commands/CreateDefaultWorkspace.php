<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Console\Command;

class CreateDefaultWorkspace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workspace:create-default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default workspace for verified users ' .
        'that have no owned workspace(s)';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $users = UserRepository::verifiedUsers();

        $this->output->progressStart(count($users));
        foreach($users as $user) {
            if($user->ownedWorkspaces()->count() == 0) {
                WorkspaceRepository::create([
                    'owner_user_id' => $user->id,
                    'name' => 'Default'
                ]);
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }
}
