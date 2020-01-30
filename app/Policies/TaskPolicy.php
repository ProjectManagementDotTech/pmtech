<?php

namespace App\Policies;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    //region Public Status Report

    public function update(User $user, Task $task)
    {
        return $user->id === $task->project->workspace->owner_user_id;
    }

    //endregion
}
