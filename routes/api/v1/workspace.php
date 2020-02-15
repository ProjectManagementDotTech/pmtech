<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::get('workspaces', 'v1\WorkspaceController@index')
        ->name('workspaces.index');
    Route::post('workspaces', 'v1\WorkspaceController@create')
        ->name('workspaces.create');
    Route::get('workspaces/{workspace}', 'v1\WorkspaceController@show')
        ->name('workspaces.show')
        ->middleware(
            'can:view,workspace'
        );
    Route::post('workspaces/{workspace}/archive',
        'v1\WorkspaceController@archive')
        ->name('workspaces.archive')
        ->middleware(
            'can:archive,workspace'
        );
});
