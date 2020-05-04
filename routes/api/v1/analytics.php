<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::post('analytics', 'v1\AnalyticsController@create')
        ->name('analytics.create');
});
