<?php

namespace App\Repositories;

use App\Invitation;
use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;

class InvitationRepository implements InvitationRepositoryInterface
{
    use ConstructsRepository, CreatesModel, DeletesModel, FindsModel,
        UpdatesModel;

    //region Public Construction

    /**
     * InvitationRepository constructor.
     */
    public function __construct()
    {
        $this->modelClass = Invitation::class;
        $this->usesSoftDeletes = FALSE;
    }

    //endregion

    /**
     * @inheritDoc
     */
    public function findByEmail(string $emailAddress): ?Invitation
    {
        return Invitation::where('email', $emailAddress)->first();
    }

    /**
     * @inheritDoc
     */
    public function findByNonce(string $nonce): ?Invitation
    {
        return Invitation::where('nonce', $nonce)->first();
    }

    /**
     * @inheritDoc
     */
    public function findByWorkspace(Workspace $workspace): Collection
    {
        return Invitation::where('workspace_id', $workspace->id)->get();
    }
}
