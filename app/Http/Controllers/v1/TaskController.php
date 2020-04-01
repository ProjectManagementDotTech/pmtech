<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //region Public Construction

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    //endregion

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
        $this->taskRepository->update($task, $request->validated());
        $task->refresh();

        return response('', 204, [
            'ETag' => $task->eTag()
        ]);
    }

    //endregion

    //region Protected Attributes

    /**
     * The task repository.
     *
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    //endregion
}
