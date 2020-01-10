<?php

namespace App\Policies;

use App\TimesheetEntry;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimesheetEntryPolicy
{
    use HandlesAuthorization;

    //region Public Status Report

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
