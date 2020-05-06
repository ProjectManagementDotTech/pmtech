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

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function findAllByEmail(string $emailAddress, bool $paginate = TRUE)
    {
        if($paginate) {
            return Invitation::where('email', $emailAddress)->paginate();
        } else {
            return Invitation::where('email', $emailAddress)->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllByNonce(string $nonce, bool $paginate = TRUE)
    {
        if($paginate) {
            return Invitation::where('nonce', $nonce)->paginate();
        } else {
            return Invitation::where('nonce', $nonce)->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllByWorkspace(Workspace $workspace,
        bool $paginate = TRUE)
    {
        if($paginate) {
            return Invitation::where('workspace_id', $workspace->id)->paginate();
        } else {
            return Invitation::where('workspace_id', $workspace->id)->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findFirstByEmail(string $emailAddress): ?Invitation
    {
        return Invitation::where('email', $emailAddress)->first();
    }

    /**
     * @inheritDoc
     */
    public function findFirstByNonce(string $nonce): ?Invitation
    {
        return Invitation::where('nonce', $nonce)->first();
    }

    /**
     * @inheritDoc
     */
    public function findFirstByWorkspace(Workspace $workspace): ?Invitation
    {
        return Invitation::where('workspace_id', $workspace->id)->first();
    }

    //endregion
}
