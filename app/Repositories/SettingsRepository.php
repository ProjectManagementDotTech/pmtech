<?php

namespace App\Repositories;

use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Settings;

class SettingsRepository implements SettingsRepositoryInterface
{
    use ConstructsRepository, CreatesModel, DeletesModel, FindsModel,
        UpdatesModel;

    //region Public Construction

    /**
     * SettingsRepository constructor.
     */
    public function __construct()
    {
        $this->modelClass = Settings::class;
        $this->usesSoftDeletes = TRUE;
    }

    //endregion
}
