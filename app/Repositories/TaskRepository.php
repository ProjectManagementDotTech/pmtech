<?php

namespace App\Repositories;

use App\Project;
use App\Task;
use Ramsey\Uuid\Uuid;

class TaskRepository
{
    //region Static Public Access

    /**
     * Archive the given task.
     *
     * @param Task $task
     * @throws \Exception
     */
    static public function archive(Task $task)
    {
        /*
         * TODO Make sure there are no un-archived timesheet entries against
         *   the task
         */

        $task->delete();
    }

    /**
     * Delete the given task.
     *
     * @param Task $task
     */
    static public function delete(Task $task)
    {
        /*
         * TODO Make sure there are no timesheet entries against the task
         */

        $task->forceDelete();
    }

    /**
     * Restore, from archive, the given task.
     *
     * @param Task $task
     */
    static public function restore(Task $task)
    {
        /*
         * TODO Make sure to restore related timesheet entries
         */

        $task->restore();
    }

    /**
     * Update the given task with the provided data, except for the `project_id`
     * and `id` attributes.
     *
     * @param Task $task
     * @param array $data
     */
    static public function update(Task $task, array $data)
    {
        foreach($data as $key => $value) {
            if($key != 'project_id' && $key != 'id') {
                $task->$key = $value;
            }
        }

        $task->save();

//        event(new TaskUpdated($task));
    }

    //endregion

    //region Static Public Status Report

    /**
     * Get the task identified by the given $name in the given $project.
     *
     * @param string $name
     * @param Project $project
     * @return Task|null
     */
    static public function byName(string $name, Project $project): ?Task
    {
        return Task::query()
            ->where('project_id', $project->id)
            ->where('name', $name)
            ->first();
    }

    /**
     * Create a new task based on the given data.
     *
     * @param array $data
     * @return Task
     * @throws \Exception
     */
    static public function create(array $data): Task
    {
        $data['id'] = Uuid::uuid4()->toString();
        $project = Project::find($data['project_id']);
        $data['wbs'] = $project->tasks()->count() + 1;

        $task = Task::create($data);

//        event(new ProjectUpdated($data['project_id']));

        return $task;
    }

    /**
     * Get the task identified by $id.
     *
     * @param string $id
     * @return Task|null
     */
    static public function find(string $id): ?Task
    {
        return Task::find($id);
    }

    /**
     * Restore, from archive, a Workspace model given its ID.
     *
     * @param string $id
     * @return Task|null
     */
    static public function restoreById(string $id): ?Task
    {
        $task = Task::withTrashed()->where('id', $id)->first();
        if($task)
            $task->restore();

        return $task;
    }

    //endregion
}
