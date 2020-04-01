<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::get('tasks/{task}', 'v1\TaskController@show')
        ->name('tasks.show')
        ->middleware(
            'cache.headers:etag'
        );
    Route::put('tasks/{task}', 'v1\TaskController@update')
        ->name('tasks.update')
        ->middleware(
            'can:update,task',
            'verify.etag'
        );
    Route::get('projects/{project}/tasks',
        'v1\ProjectController@indexTasks')
        ->name('projects.taskIndex')
        ->middleware(
            'cache.collection.etag'
        );
    Route::post('projects/{project}/tasks',
        'v1\ProjectController@createTask')
        ->name('projects.taskCreate');
});
