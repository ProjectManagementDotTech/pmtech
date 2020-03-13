<?php

namespace App\Observers;

use App\Repositories\Contracts\SettingsRepository as
    SettingsRepositoryInterface;
use App\Repositories\SettingsRepository;
use App\User;

class UserObserver
{
    //region Public Construction

    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    //endregion

    //region Public Access

    /**
     * $user was stored in the database. Create a Settings model for $user.
     * @param User $user
     * @return bool
     */
    public function updating(User $user)
    {
        if(
            $user->email_verified_at !== NULL &&
            $user->getOriginal('email_verified_at') == NULL
        ) {
            $this->settingsRepository->create(['user_id' => $user->id]);
        }

        return TRUE;
    }

    //endregion

    //region Protected Attributes

    /**
     * @var SettingsRepository
     */
    protected $settingsRepository;

    //endregion
}
