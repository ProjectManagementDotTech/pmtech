<?php

namespace App\Repositories\Contracts;

use App\Invitation;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;

interface InvitationRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * Find the invitation matching $emailAddress.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function findByEmail(string $emailAddress): ?Invitation;

    /**
     * Find the invitation matching $nonce.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function findByNonce(string $nonce): ?Invitation;

    /**
     * Find all invitations for $workspace.
     *
     * @param Workspace $workspace
     * @return Collection
     */
    public function findByWorkspace(Workspace $workspace): Collection;

    //endregion
}
