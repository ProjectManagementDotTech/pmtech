<?php

namespace App\Events;

use App\Workspace;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkspaceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //region Public Construction

    /**
     * WorkspaceUpdated constructor.
     *
     * @param Workspace $workspace
     */
    public function __construct($workspace)
    {
        if(is_object($workspace) && get_class($workspace) == Workspace::class) {
            $this->workspaceId = $workspace->id;
        } else {
            $this->workspaceId = $workspace;
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
     * @var mixed
     */
    public $workspaceId;

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Workspace.' . $this->workspaceId);
    }

    //endregion
}
