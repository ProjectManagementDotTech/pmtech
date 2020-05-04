<?php

Route::middleware(['verified', 'auth:sanctum'])->group(function () {
    Route::get('/workspaces/{workspace}/timesheet_entries',
        'v1\TimesheetEntryController@index')
        ->name('timesheet_entries.index');
    Route::get('/workspaces/{workspace}/timesheet_entries/export',
        'v1\TimesheetEntryController@export')
        ->name('timesheet_entries.export');
    Route::get('/timesheet_entries/running',
        'v1\TimesheetEntryController@showRunning')
        ->name('timesheet_entries.showRunning');
    Route::get('/timesheet_entries/{timesheetEntry}',
        'v1\TimesheetEntryController@show')
        ->name('timesheet_entries.show');
    Route::post('/timesheet_entries', 'v1\TimesheetEntryController@create')
        ->name('timesheet_entries.create');
    Route::put('/timesheet_entries/{timesheetEntry}',
        'v1\TimesheetEntryController@update')
        ->name('timesheet_entries.update')
        ->middleware(
            'can:update,timesheetEntry'
        );
});
