<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreTaskRequest;
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

    public function update(StoreTaskRequest $request, Task $task)
    {
        $task->fill($request->validated())->save();

        return response('', 204);
    }

    //endregion
}
