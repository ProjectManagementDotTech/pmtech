<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::get('clients/{client}', 'v1\ClientController@show')
        ->name('clients.show');
    Route::get('/workspaces/{workspace}/clients',
        'v1\WorkspaceController@indexClients')
        ->name('workspaces.indexClients');
    Route::post('workspaces/{workspace}/clients',
        'v1\WorkspaceController@createClient')
        ->name('workspaces.createClient');
});
