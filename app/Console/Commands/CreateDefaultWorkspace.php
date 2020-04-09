<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use Illuminate\Console\Command;

class CreateDefaultWorkspace extends Command
{
    //region Public Construction

    public function __construct(UserRepositoryInterface $userRepository,
        WorkspaceRepositoryInterface $workspaceRepository)
    {
        $this->userRepository = $userRepository;
        $this->workspaceRepository = $workspaceRepository;

        parent::__construct();
    }

    //endregion

    //region Public Access

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $users = $this->userRepository->verified();

        $this->output->progressStart(count($users));
        foreach($users as $user) {
            if($user->ownedWorkspaces()->count() == 0) {
                $this->workspaceRepository->create([
                    'owner_user_id' => $user->id,
                    'name' => 'Default'
                ]);
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
    protected $description = 'Create a default workspace for verified users ' .
    'that have no owned workspace(s)';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workspace:create-default';

    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * The workspace repository.
     *
     * @var WorkspaceRepositoryInterface
     */
    protected $workspaceRepository;

    //endregion
}
