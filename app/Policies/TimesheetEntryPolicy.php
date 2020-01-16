<?php

namespace App\Policies;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\TimesheetEntry;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimesheetEntryPolicy
{
    use HandlesAuthorization;

    //region Public Status Report

    /**
     * Can the user create a timesheet entry?
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $requestInput = request()->input();

        /* BR000006 */
        if(isset($requestInput['task_id'])) {
            $task = TaskRepository::find($requestInput['task_id']);
            if($task) {
                if(
                    !$user->projects()
                        ->where('id', $task->project_id)
                        ->count()
                ) {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        if(isset($requestInput['project_id'])) {
            if(
                !$user->projects()
                    ->where('id', $requestInput['project_id'])
                    ->count()
            ) {
                return FALSE;
            }

            $project = ProjectRepository::find($requestInput['project_id']);
            /* BR000007 */
            if(
                !$user->workspaces()
                    ->where('id', $project->workspace_id)
                    ->count()
            ) {
                return FALSE;
            }
        }

        /* BR000007 */
        if(isset($requestInput['workspace_id'])) {
            if(
                !$user->workspaces()
                    ->where('id', $requestInput['workspace_id'])
                    ->count()
            ) {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Is user authorized to update the timesheet entry?
     *
     * @param User $user
     * @param TimesheetEntry $timesheetEntry
     * @return bool
     */
    public function update(User $user, TimesheetEntry $timesheetEntry)
    {
        return $user->id === $timesheetEntry->user_id;
    }

    //endregion
}
