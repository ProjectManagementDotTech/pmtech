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
use Illuminate\Database\Eloquent\Builder;

class ClientRepository implements ClientRepositoryInterface
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
    public function findAllByNameInsideWorkspace(string $name,
        Workspace $workspace, bool $paginate = TRUE)
    {
        if($paginate) {
            return $this->builderByNameInsideWorkspace($name, $workspace)
                ->paginate();
        } else {
            return $this->builderByNameInsideWorkspace($name, $workspace)
                ->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findFirstByNameInsideWorkspace(string $name,
        Workspace $workspace): ?Client
    {
        return $this->builderByNameInsideWorkspace($name, $workspace)->first();
    }

    //endregion

    //region Protected Implementation

    /**
     * Create a Builder that will look for any name matching %$name% inside
     * $workspace.
     *
     * @param string $name
     * @param Workspace $workspace
     * @return Builder
     */
    protected function builderByNameInsideWorkspace(string $name,
        Workspace $workspace): Builder
    {
        return Client::query()
            ->where('name', 'like', '%' . $name . '%')
            ->where('workspace_id', $workspace->id);
    }

    //endregion
}
