<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::get('workspaces', 'v1\WorkspaceController@index')
        ->name('workspaces.index')
        ->middleware(
            'cache.collection.etag'
        );
    Route::post('workspaces', 'v1\WorkspaceController@create')
        ->name('workspaces.create');
    Route::delete('workspaces/{workspace}', 'v1\WorkspaceController@delete')
        ->name('workspaces.delete')
        ->middleware(
            'can:delete,workspace',
            'verify.etag'
        );
    Route::get('workspaces/{workspace}', 'v1\WorkspaceController@show')
        ->name('workspaces.show')
        ->middleware(
            'can:view,workspace',
            'cache.headers:etag'
        );
    Route::put('workspaces/{workspace}', 'v1\WorkspaceController@update')
        ->name('workspaces.update')
        ->middleware(
            'can:edit,workspace',
            'verify.etag'
        );
    Route::post('workspaces/{workspace}/archive',
        'v1\WorkspaceController@archive')
        ->name('workspaces.archive')
        ->middleware(
            'can:archive,workspace',
            'verify.etag'
        );
    Route::post('workspaces/{workspace}/invite',
        'v1\WorkspaceController@invite')
        ->name('workspaces.invite')
        ->middleware(
            'can:invite,workspace'
        );
    Route::get('workspaces/{workspace}/members',
        'v1\WorkspaceController@indexMembers')
        ->name('workspaces.members')
        ->middleware(
            'can:indexMembers,workspace'
        );
    Route::delete('workspaces/{workspace}/members/{member}',
        'v1\WorkspaceController@removeMember')
        ->name('workspaces.removeMember')
        ->middleware(
            'can:removeMember,workspace'
        );
    Route::get('workspaces/{workspace}/balance',
        'v1\WorkspaceController@balance')
        ->name('workspaces.balance')
        ->middleware(
            'can:edit,workspace'
        );
    Route::post('workspaces/{workspace}/transfer/{newOwner}', 'v1\WorkspaceController@transferOwnership')
        ->name('workspaces.transferOwnership')
        ->middleware(
            'can:transferOwnership,workspace,newOwner'
        );
});
