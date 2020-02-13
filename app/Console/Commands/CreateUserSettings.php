<?php

namespace App\Console\Commands;

use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;

class CreateUserSettings extends Command
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
            if(!$user->settings) {
                SettingsRepository::create($user);
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
    protected $signature = 'user:create-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Settings model for each verified user ' .
        'if that user does not have Settings yet';

    //endregion
}
