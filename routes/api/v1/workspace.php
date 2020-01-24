<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::get('workspaces', 'v1\WorkspaceController@index')
        ->name('workspaces.index');
    Route::get('workspaces/{workspace}', 'v1\WorkspaceController@show')
        ->name('workspaces.show')
        ->middleware(
            'can:view,workspace'
        );
});
