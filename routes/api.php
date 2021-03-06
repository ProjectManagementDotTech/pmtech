<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(base_path('/routes/api/v1/analytics.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/client.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/error.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/project.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/settings.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/task.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/timesheet_entry.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/user.php'));
Route::prefix('v1')->group(base_path('/routes/api/v1/workspace.php'));
