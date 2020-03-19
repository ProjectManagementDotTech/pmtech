<?php

namespace App\Repositories;

use App\Invitation;
use App\Repositories\Contracts\InvitationRepository as
    InvitationRepositoryInterface;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class InvitationRepository implements InvitationRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return Invitation::create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function byEmail(string $emailAddress): ?Invitation
    {
        return Invitation::where('email', $emailAddress)->first();
    }

    /**
     * @inheritDoc
     */
    public function byNonce(string $nonce): ?Invitation
    {
        return Invitation::where('nonce', $nonce)->first();
    }

    /**
     * @inheritDoc
     */
    public function byWorkspace(Workspace $workspace): Collection
    {
        return Invitation::where('workspace_id', $workspace->id)->get();
    }

    /**
     * @inheritDoc
     */
    public function find($id): ?Model
    {
        return Invitation::find($id);
    }
}
