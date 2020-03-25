<?php

namespace App\Repositories\Contracts;

use App\Client;
use App\Workspace;

interface ClientRepositoryInterface extends RepositoryInterface
{
    /**
     * Find a client with $name inside $workspace.
     *
     * @param string $name
     * @param Workspace $workspace
     * @return Client|null
     */
    public function byNameInsideWorkspace(string $name, Workspace $workspace):
        ?Client;
}
