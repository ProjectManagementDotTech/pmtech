<?php

namespace App\Policies;

use App\User;
use App\Workspace;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

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
     * Can $user edit $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function edit(User $user, Workspace $workspace)
    {
        return $workspace->owner_user_id == $user->id;
    }

    /**
     * Can $user see all members of $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function indexMembers(User $user, Workspace $workspace)
    {
        return $workspace->owner_user_id == $user->id;
    }

    /**
     * Can $user invite new members into $workspace?
     *
     * @param User $user
     * @param Workspace $workspace
     * @return bool
     */
    public function invite(User $user, Workspace $workspace)
    {
        return $workspace->owner_user_id == $user->id;
    }

    /**
     * Can $user transfer ownership of $workspace to $newOwner?
     *
     * @param User $user
     * @param Workspace $workspace
     * @param User $newOwner
     * @return bool
     */
    public function transferOwnership(User $user, Workspace $workspace,
        User $newOwner)
    {
        Log::info(__METHOD__ . '(User $user, Workspace $workspace, User ' .
            '$newOwner)');
        Log::info('    User $user: ' . $user->id);
        Log::info('    Workspace $workspace: ' . $workspace->id);
        Log::info('    User $newOwner: ' . $newOwner->id);
        Log::debug('');
        Log::debug('    Workspace owned by user? $workspace->owner_user_id ' .
            '== $user->id? ' . $workspace->owner_user_id . ' == ' . $user->id);
        Log::debug('    newOwner part of the workspace? ' .
            $workspace->users()->where('user_id', $newOwner->id)->count());

        return $workspace->owner_user_id == $user->id &&
            $workspace->users()->where('user_id', $newOwner->id)->count() == 1;
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
