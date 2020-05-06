<?php

namespace App\Repositories\Contracts;

use App\User;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkspaceRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * All Workspace models matching $name, and, by default, paginate the
     * result.
     *
     * @param string $name
     * @param bool $paginated
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByName(string $name, bool $paginated = TRUE);

    /**
     * All Workspace models matching $name and $user, and, by default, paginate
     * the result.
     *
     * @param string $name
     * @param User $user
     * @param bool $paginated
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByNameAndUser(string $name, User $user,
        bool $paginated = TRUE);

    /**
     * All the workspaces owned by the same owner as $workspace, except for
     * $workspace.
     *
     * @param Workspace $workspace
     * @return mixed
     */
    public function findAllOtherWorkspacesFromSameOwner(Workspace $workspace):
        Collection;

    /**
     * The first Workspace model matching $name.
     *
     * @param string $name
     * @return Workspace|null
     */
    public function findFirstByName(string $name): ?Workspace;

    /**
     * The first Workspace model matching $name and $user.
     *
     * @param string $name
     * @param User $user
     * @return Workspace|null
     */
    public function findFirstByNameAndUser(string $name, User $user):
        ?Workspace;

    //endregion
}
