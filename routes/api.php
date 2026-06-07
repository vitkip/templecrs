<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PersonnelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Buddhist EMS
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.')->group(function () {

    // ─── Authentication ──────────────────────────────────────────────────
    Route::post('auth/login', [AuthTokenController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthTokenController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me',      [AuthTokenController::class, 'me'])->name('auth.me');
    });

    // ─── Public read-only ────────────────────────────────────────────────
    Route::get('personnel/statistics',  [PersonnelController::class, 'statistics'])->name('personnel.statistics');
    Route::get('personnel',             [PersonnelController::class, 'index'])->name('personnel.index');
    Route::get('personnel/{personnel}', [PersonnelController::class, 'show'])->name('personnel.show');

    Route::apiResource('departments', DepartmentController::class)->only(['index', 'show']);

    // ─── Protected: write / delete (ຕ້ອງການ Bearer token) ───────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('personnel',               [PersonnelController::class, 'store'])->name('personnel.store');
        Route::put('personnel/{personnel}',    [PersonnelController::class, 'update'])->name('personnel.update');
        Route::patch('personnel/{personnel}',  [PersonnelController::class, 'update']);
        Route::delete('personnel/{personnel}', [PersonnelController::class, 'destroy'])->name('personnel.destroy');
    });
});
