<?php

namespace App\Policies;

use App\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //region Public Status Report

    /**
     * Can $user edit $project?
     *
     * @param User $user
     * @param Project $project
     * @return bool
     */
    public function edit(User $user, Project $project)
    {
        return $project->workspace->owner_user_id == $user->id;
    }

    //endregion
}
