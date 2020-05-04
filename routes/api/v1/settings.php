<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::put('settings', 'v1\SettingsController@update')
        ->name('settings.update');
});
