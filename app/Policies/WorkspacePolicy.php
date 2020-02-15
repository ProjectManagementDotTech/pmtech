<?php

namespace App\Policies;

use App\User;
use App\Workspace;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    //region Public Status Report

    /**
     * Can $user archive $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function archive(User $user, Workspace $workspace)
    {
        return $workspace->owner_user_id == $user->id;
    }

    /**
     * Can $user delete $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function delete(User $user, Workspace $workspace)
    {
        return $workspace->owner_user_id == $user->id;
    }

    /**
     * Can $user view $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function view(User $user, Workspace $workspace)
    {
        /* BR000021 */
        return $workspace->users()->where('user_id', $user->id)->count() == 1;
    }

    //endregion
}
