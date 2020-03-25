<?php


namespace App\Repositories\Contracts;


use App\Project;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * Find all tasks with $name inside $project, or globally within the
     * workspace of the authenticated user.
     *
     * @param string $name
     * @param mixed $projectOrWorkspace
     * @return Collection
     */
    public function findByName(string $name, $projectOrWorkspace): Collection;

    //endregion
}
