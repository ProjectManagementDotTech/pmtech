<?php

namespace App\Repositories;

use App\TimesheetEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class TimesheetEntryRepository
{
    //region Static Public Access

    /**
     * Archive the given timesheet entry.
     *
     * @param TimesheetEntry $timesheetEntry
     * @throws \Exception
     */
    static public function archive(TimesheetEntry $timesheetEntry)
    {
        $timesheetEntry->delete();
    }

    /**
     * Delete the given timesheet entry.
     *
     * @param TimesheetEntry $timesheetEntry
     * @throws \Exception
     */
    static public function delete(TimesheetEntry $timesheetEntry)
    {
        $timesheetEntry->forceDelete();
    }

    /**
     * Restore the given timesheet entry.
     *
     * @param TimesheetEntry $timesheetEntry
     */
    static public function restore(TimesheetEntry $timesheetEntry)
    {
        $timesheetEntry->restore();
    }

    /**
     * Update the given timesheet entry with the provided data, except for the
     * `user_id` and `id` attributes.
     *
     * @param TimesheetEntry $timesheetEntry
     * @param array $data
     */
    static public function update(TimesheetEntry $timesheetEntry, array $data)
    {
        foreach($data as $key => $value) {
            if($key !== 'duration' && $key != 'id' && $key != 'user_id') {
                $timesheetEntry->$key = $value;
            }
        }

        $timesheetEntry->save();
    }

    //endregion

    //region Static Public Status Report

    static public function create(array $data): ?TimesheetEntry
    {
        /*
         * Cannot create timesheet entries without user ID or description (even
         * if the description is empty...)
         */
        if(!isset($data['user_id']) || !isset($data['description'])) {
            return NULL;
        }

        if(isset($data['ended_at']) && isset($data['started_at'])) {
            $endedAt = $data['ended_at'];
            $startedAt = $data['started_at'];
            if(is_string($endedAt)) {
                $endedAt = Carbon::createFromFormat('Y-m-d H:i:s', $endedAt);
            }
            if(is_string($startedAt)) {
                $startedAt = Carbon::createFromFormat('Y-m-d H:i:s', $startedAt);
            }

            /* BR000016 */
            if($endedAt < $startedAt) {
                $data['ended_at'] = $startedAt;
                $data['started_at'] = $endedAt;
            }
        }
        /*
         * Set the started_at attribute, if it is not set
         */
        if(!isset($data['started_at'])) {
            $data['started_at'] = Carbon::now();
        }

        /*
         * Make sure we set task_id and / or project_id from the corresponding
         * objects
         */
        if(isset($data['project'])) {
            $data['project_id'] = $data['project']['id'];
        }
        if(isset($data['task'])) {
            $data['task_id'] = $data['task']['id'];
        }

        /*
         * Set project_id and / or workspace_id from the provided input
         */
        if(isset($data['task_id']) && !isset($data['project_id'])) {
            $task = TaskRepository::find($data['task_id']);
            $data['project_id'] = $task->project_id;
        }
        if(isset($data['project_id']) && !isset($data['workspace_id'])) {
            $project = ProjectRepository::find($data['project_id']);
            $data['workspace_id'] = $project->workspace_id;
        }

        /* BR000008 */
        if(!isset($data['workspace_id'])) {
            $user = UserRepository::find($data['user_id']);
            if(!$user->ownedWorkspaces()->count()) {
                /* BR000020 */
                return NULL;
            }
            $data['workspace_id'] = $user->ownedWorkspaces[0]->id;
        }

        /*
         * Create a nice ID
         */
        $data['id'] = Uuid::uuid4()->toString();

        $result = TimesheetEntry::create($data);

        return $result;
    }

    /**
     * Return a collection of timesheet entries based on the filterData.
     *
     * @param array $filterData
     * @return Collection
     */
    static public function filter(array $filterData): Collection
    {
        $result = TimesheetEntry::query();
        foreach($filterData as $key => $value) {
            if($key == 'ended_at') {
                if($value) {
                    $result = $result->where($key, '<=', $value);
                } else {
                    $result = $result->whereNull($key);
                }
            } elseif($key == 'started_at') {
                if($value) {
                    $result = $result->where($key, '>=', $value);
                } else {
                    $result = $result->whereNull($key);
                }
            } elseif($key == 'between') {
                $result = $result->where('started_at', '<=', $value)
                    ->where('ended_at', '>=', $value);
            } elseif($key == 'existing_between') {
                $result = $result->where('started_at', '>=', $value[0])
                    ->where('ended_at', '<=', $value[1]);
            } else {
                $result = $result->where($key, $value);
            }
        }

        return $result
            ->orderBy('ended_at', 'desc')
            ->get();
    }

    //endregion
}
