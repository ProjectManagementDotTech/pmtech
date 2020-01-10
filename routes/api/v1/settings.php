<?php

Route::middleware('verified')->group(function () {
    Route::put('settings', 'v1\SettingsController@update')
        ->name('settings.update')
        ->middleware('auth');
});
