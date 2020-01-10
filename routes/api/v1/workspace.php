<?php

Route::middleware('verified')->group(function () {
    Route::get('workspaces', 'v1\WorkspaceController@index')
        ->name('workspaces.index')
        ->middleware('auth');
});
