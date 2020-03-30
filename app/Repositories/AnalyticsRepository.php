<?php

namespace App\Repositories;

use App\Analytics;
use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\AnalyticsRepositoryInterface;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    use ConstructsRepository, CreatesModel, DeletesModel, FindsModel,
        UpdatesModel;

    //region Public Construction

    /**
     * TaskRepository constructor.
     */
    public function __construct()
    {
        $this->modelClass = Analytics::class;
        $this->usesSoftDeletes = FALSE;
    }

    //endregion
}
