<?php

namespace App\Repositories;

use App\Client;
use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Workspace;
use Illuminate\Database\Eloquent\Model;

class ClientRepository implements ClientRepositoryContract
{
    use ConstructsRepository, CreatesModel, DeletesModel, FindsModel,
        UpdatesModel;

    //region Public Construction

    /**
     * ClientRepository constructor.
     */
    public function __construct()
    {
        $this->modelClass = Client::class;
        $this->usesSoftDeletes = TRUE;
    }

    //endregion

    //region Status Report

    /**
     * @inheritDoc
     */
    public function byNameInsideWorkspace(string $name, Workspace $workspace):
        ?Client
    {
        return Client::where('name', $name)
            ->where('workspace_id', $workspace->id)
            ->first();
    }

    //endregion
}
