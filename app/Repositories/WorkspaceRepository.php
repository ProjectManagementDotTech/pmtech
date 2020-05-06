<?php

namespace App\Repositories;

use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use App\User;
use App\Workspace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WorkspaceRepository implements WorkspaceRepositoryInterface
{
    use ConstructsRepository, CreatesModel {
        create as traitCreate;
    }
    use DeletesModel, FindsModel, UpdatesModel;

    //region Public Construction

    /**
     * TaskRepository constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->modelClass = Workspace::class;
        $this->userRepository = $userRepository;
        $this->usesSoftDeletes = TRUE;
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function create(array $attributes = []): Model
    {
        $user = $this->userRepository->find($attributes['owner_user_id']);
        if(!$user) {
            throw new \Exception('Cannot find user #' .
                $attributes['owner_user_id']);
        }

        $result = $this->traitCreate($attributes);
        $user->attachToWorkspace($result);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function findAllByName(string $name, bool $paginated = TRUE)
    {
        $result = Workspace::where('name', $name);
        if($paginated) {
            return $result->paginate();
        } else {
            return $result->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllByNameAndUser(string $name, User $user,
        bool $paginated = TRUE)
    {
        $result = Workspace::where('owner_user_id', $user->id)
            ->where('name', $name);
        if($paginated) {
            return $result->paginate();
        } else {
            return $result->get();
        }
    }

    /**
     * All the workspaces owned by the same owner as the given $workspace,
     * except for the given $workspace.
     *
     * @param Workspace $workspace
     * @return Collection
     */
    public function findAllOtherWorkspacesFromSameOwner(Workspace $workspace):
        Collection
    {
        return $workspace->ownerUser->ownedWorkspaces()
            ->where('id', '<>', $workspace->id)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findFirstByName(string $name): ?Workspace
    {
        return Workspace::where('name', $name)->first();
    }

    /**
     * @inheritDoc
     */
    public function findFirstByNameAndUser(string $name, User $user):
        ?Workspace
    {
        return Workspace::where('owner_user_id', $user->id)
            ->where('name', $name)
            ->first();
    }

    //endregion

    //region Protected Attributes

    /**
     * The user repository.
     *
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    //endregion
}
