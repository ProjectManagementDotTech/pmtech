<?php

namespace App\Repositories\Interfaces;

use App\Invitation;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;

interface InvitationRepository extends Repository
{
    /**
     * Find the invitation matching the given email address.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function byEmail(string $emailAddress): ?Invitation;

    /**
     * Find the invitation matching the given nonce.
     *
     * @param string $emailAddress
     * @return Invitation|null
     */
    public function byNonce(string $nonce): ?Invitation;

    /**
     * Find all invitations for $workspace.
     *
     * @param Workspace $workspace
     * @return Collection
     */
    public function byWorkspace(Workspace $workspace): Collection;
}
