<?php

namespace App\Repositories;

use App\Repositories\Contracts\SettingsRepository as
    SettingsRepositoryInterface;
use App\Settings;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository implements SettingsRepositoryInterface
{
    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return Settings::create($attributes);
    }

    //endregion

    /**
     * @inheritDoc
     */
    public function find($id): ?Model
    {
        return Settings::find($id);
    }
}
