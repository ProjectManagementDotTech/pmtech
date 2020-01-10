<?php

Route::middleware(['verified', 'auth'])->group(function () {
    Route::get('projects/{project}', 'v1\ProjectController@show')
        ->name('projects.show');
    Route::get('workspaces/{workspace}/projects',
        'v1\WorkspaceController@indexProjects')
        ->name('workspaces.projectIndex');
    Route::post('workspaces/{workspace}/projects',
        'v1\WorkspaceController@createProject')
        ->name('workspaces.projectCreate');
});

