<?php

namespace App\Repositories;

use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
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
     */
    public function all(array $criteria = []): Collection
    {
        $result = Workspace::query();
        foreach($criteria as $key => $value) {
            $result = $result->where($key, $value);
        }

        return $result->get();
    }

    /**
     * All the workspaces owned by the same owner as the given $workspace,
     * except for the given $workspace.
     *
     * @param Workspace $workspace
     * @return mixed
     */
    public function allFromSameOwnerExcept(Workspace $workspace)
    {
        return $workspace->ownerUser->ownedWorkspaces()
            ->where('id', '<>', $workspace->id)
            ->get();
    }

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
    public function first(array $criteria = []): ?Model
    {
        $result = Workspace::query();
        foreach($criteria as $key => $value) {
            $result = $result->where($key, $value);
        }

        return $result->first();
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
