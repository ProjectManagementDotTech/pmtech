<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface WorkspaceRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * All Workspace models matching all the $criteria.
     *
     * @param array $criteria
     * @return Collection
     */
    public function all(array $criteria = []): Collection;

    /**
     * The first Workspace model matching all the $criteria.
     *
     * @param array $criteria
     * @return Collection
     */
    public function first(array $criteria = []): ?Model;

    //endregion
}
