<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::put('settings', 'v1\SettingsController@update')
        ->name('settings.update');
});
