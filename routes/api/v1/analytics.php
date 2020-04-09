<?php

Route::middleware(['verified', 'auth:airlock'])->group(function () {
    Route::post('analytics', 'v1\AnalyticsController@create')
        ->name('analytics.create');
});
