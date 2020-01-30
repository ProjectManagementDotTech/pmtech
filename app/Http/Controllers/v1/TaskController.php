<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //region Public Status Report

    /**
     * Show the task.
     *
     * @param Task $task
     * @return Task
     */
    public function show(Task $task)
    {
        return $task;
    }

    public function update(Task $task, Request $request)
    {
        if(($newName = $request->input('name', NULL)) !== NULL) {
            $task->name = $newName;
        }
        if(($newWbs = $request->input('wbs', NULL)) !== NULL) {
            $task->wbs = $newWbs;
        }

        $task->save();

        return response('', 204);
    }

    //endregion
}
