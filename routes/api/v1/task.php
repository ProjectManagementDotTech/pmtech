<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::get('tasks/{task}', 'v1\TaskController@show')
        ->name('tasks.show');
    Route::get('projects/{project}/tasks',
        'v1\ProjectController@indexTasks')
        ->name('projects.taskIndex');
    Route::post('projects/{project}/tasks',
        'v1\ProjectController@createTask')
        ->name('projects.taskCreate');
});
