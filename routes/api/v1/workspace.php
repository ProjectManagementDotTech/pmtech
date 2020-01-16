<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::get('workspaces', 'v1\WorkspaceController@index')
        ->name('workspaces.index');
});
