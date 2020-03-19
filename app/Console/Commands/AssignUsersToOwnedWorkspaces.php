<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\UserRepository as UserRepositoryInterface;
use Illuminate\Console\Command;

class AssignUsersToOwnedWorkspaces extends Command
{
    //region Public Constructor

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    //region Public Access

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->userRepository->verified();

        $this->output->progressStart(count($users));
        foreach($users as $user) {
            foreach($user->ownedWorkspaces as $workspace) {
                $user->attachToWorkspace($workspace);
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    //endregion

    //region Protected Attributes

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign each user to their owned workspaces';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-to-owned-workspaces';

    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    //endregion
}
