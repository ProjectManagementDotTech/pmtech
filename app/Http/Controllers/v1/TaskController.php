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

    //endregion
}
