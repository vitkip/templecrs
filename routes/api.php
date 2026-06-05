<?php

use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PersonnelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Buddhist EMS
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.')->group(function () {

    // Personnel
    Route::get('personnel/statistics', [PersonnelController::class, 'statistics']);
    Route::apiResource('personnel', PersonnelController::class);

    // Departments
    Route::apiResource('departments', DepartmentController::class)->only(['index', 'show']);
});
