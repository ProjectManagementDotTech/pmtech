<?php

namespace App\Observers;

use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Repositories\SettingsRepository;
use App\User;

class UserObserver
{
    //region Public Construction

    /**
     * UserObserver constructor.
     *
     * @param SettingsRepositoryInterface $settingsRepository
     */
    public function __construct(SettingsRepositoryInterface $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    //endregion

    //region Public Status Report

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
