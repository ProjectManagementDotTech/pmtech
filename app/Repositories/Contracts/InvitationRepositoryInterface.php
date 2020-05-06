<?php

namespace App\Repositories\Contracts;

use App\Invitation;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvitationRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * Find all invitations matching $emailAddress and, by default, paginate the
     * result.
     *
     * @param string $emailAddress
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByEmail(string $emailAddress, bool $paginate = TRUE);

    /**
     * Find all invitations matching $nonce and, by default, paginate the
     * result.
     *
     * @param string $emailAddress
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByNonce(string $nonce, bool $paginate = TRUE);

    /**
     * Find all invitations for $workspace and, by default, paginate the result.
     *
     * @param Workspace $workspace
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByWorkspace(Workspace $workspace,
        bool $paginate = TRUE);

    /**
     * Find the first invitation matching $emailAddress.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function findFirstByEmail(string $emailAddress): ?Invitation;

    /**
     * Find the invitation matching $nonce.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function findFirstByNonce(string $nonce): ?Invitation;

    /**
     * Find the first invitation for $workspace.
     *
     * @param Workspace $workspace
     * @return Collection
     */
    public function findFirstByWorkspace(Workspace $workspace): ?Invitation;

    //endregion
}
