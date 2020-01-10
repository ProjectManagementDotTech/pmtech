<?php

namespace App\Repositories;

use App\TimesheetEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
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
        Log::info(__METHOD__);
        ob_start();
        var_dump($data);
        Log::debug('  $data: ' . ob_get_contents());
        ob_end_clean();

        /*
         * Cannot create timesheet entries without user ID or description (even
         * if the description is empty...)
         */
        if(!isset($data['user_id']) || !isset($data['description'])) {
            return NULL;
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
            $task = TaskRepository::get($data['task_id']);
            $data['project_id'] = $task->project_id;
        }
        if(isset($data['project_id']) && !isset($data['workspace_id'])) {
            $project = ProjectRepository::get($data['project_id']);
            $data['workspace_id'] = $project->workspace_id;
        }

        /*
         * Create a nice ID
         */
        $data['id'] = Uuid::uuid4()->toString();

        $result = TimesheetEntry::create($data);

        return $result;
    }

    static public function filter(array $filterData): Collection
    {
        $result = TimesheetEntry::query();
        foreach($filterData as $key => $value) {
            if($key == 'ended_at') {
                if($value) {
                    $result = $result->where($key, '<=', $value);
                } else {
                    $result = $result->where($key, $value);
                }
            } elseif($key == 'started_at') {
                if($value) {
                    $result = $result->where($key, '>=', $value);
                } else {
                    $result = $result->where($key, $value);
                }
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
