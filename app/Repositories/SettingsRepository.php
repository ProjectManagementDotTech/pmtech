<?php

namespace App\Repositories;

use App\Settings;
use App\User;

class SettingsRepository
{
    //region Static Public Status Report

    /**
     * Create new settings for the given $user.
     *
     * @param User $user
     * @return Settings
     */
    static public function create(User $user): Settings
    {
        return Settings::create([
            'user_id' => $user->id
        ]);
    }

    //endregion
}
