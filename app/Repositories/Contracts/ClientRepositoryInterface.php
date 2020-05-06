<?php

namespace App\Repositories\Contracts;

use App\Client;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all clients with $name inside $workspace. Defaults to paginated
     * results.
     *
     * @param string $name
     * @param Workspace $workspace
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByNameInsideWorkspace(string $name,
        Workspace $workspace, bool $paginate = TRUE);

    /**
     * Find a client with $name inside $workspace.
     *
     * @param string $name
     * @param Workspace $workspace
     * @return Client|null
     */
    public function findFirstByNameInsideWorkspace(string $name,
        Workspace $workspace): ?Client;
}
