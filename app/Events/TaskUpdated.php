<?php

namespace App\Events;

use App\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //region Public Construction

    /**
     * WorkspaceUpdated constructor.
     *
     * @param $task
     */
    public function __construct($task)
    {
        if(is_object($task) && get_class($task) == Task::class) {
            $this->taskId = $task->id;
        } else {
            $this->taskId = $task;
        }
    }

    //endregion

    //region Public Status Report

    /**
     * @var string
     */
    public $broadcastQueue = 'broadcasting';

    /**
     * @var int
     */
    public $timeout = 120;

    /**
     * @var int
     */
    public $tries = 5;

    /**
     * @var string
     */
    public $taskId;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Task.' . $this->taskId);
    }

    //endregion
}
