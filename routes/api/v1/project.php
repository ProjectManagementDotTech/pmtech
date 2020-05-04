<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::get('projects/{project}', 'v1\ProjectController@show')
        ->name('projects.show');
    Route::put('projects/{project}', 'v1\ProjectController@update')
        ->name('projects.update')
        ->middleware(
            'can:edit,project'
        );
    Route::get('workspaces/{workspace}/projects',
        'v1\WorkspaceController@indexProjects')
        ->name('workspaces.projectIndex');
    Route::post('workspaces/{workspace}/projects',
        'v1\WorkspaceController@createProject')
        ->name('workspaces.projectCreate');
});

