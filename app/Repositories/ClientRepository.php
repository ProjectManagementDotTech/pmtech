<?php

namespace App\Repositories;

use App\Client;
use App\Repositories\Contracts\ClientRepository as ClientRepositoryContract;
use App\Workspace;
use Illuminate\Database\Eloquent\Model;

class ClientRepository implements ClientRepositoryContract
{
    //region Status Report

    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return Client::create($attributes);
    }

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
