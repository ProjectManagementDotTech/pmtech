<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\SettingsRepository as
    SettingsRepositoryInterface;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;

class CreateUserSettings extends Command
{
    //region Public Construction

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;

        parent::__construct();
    }

    //endregion

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
            if(!$user->settings) {
                $this->settingsRepository->create(['user_id' => $user->id]);
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
    protected $description = 'Create a Settings model for each verified user ' .
    'if that user does not have Settings yet';

    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-settings';

    //endregion
}
