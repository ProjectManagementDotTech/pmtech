<?php


namespace App\Repositories\Contracts;


use App\Project;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * Find all tasks with $name inside $project, or globally within the
     * workspace of the authenticated user, and, by default, paginate the
     * result.
     *
     * @param string $name
     * @param mixed $projectOrWorkspace
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByName(string $name, $projectOrWorkspace,
        bool $paginate = TRUE);

    /**
     * Find all tasks with $name inside $project, or globally within the
     * workspace of the authenticated user.
     *
     * @param string $name
     * @param mixed $projectOrWorkspace
     * @return Task|null
     */
    public function findFirstByName(string $name, $projectOrWorkspace): ?Task;

    //endregion
}
