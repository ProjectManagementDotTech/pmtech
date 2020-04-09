<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return $user->id === $id;
});

Broadcast::channel('App.Workspace.{workspace}', function($user, \App\Workspace $workspace) {
    return $user->workspaces()
        ->where('workspace_id', $workspace->id)
        ->first() !== NULL;
});

Broadcast::channel('App.Task.{task}', function($user, \App\Task $task) {
    $project = $task->project;
    return $user->workspaces()
        ->where('workspace_id', $project->workspace_id)
        ->first() !== NULL;
});
