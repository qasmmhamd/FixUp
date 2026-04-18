<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Filters\WorkerController;
use App\Http\Controllers\Auth\AccountUpgradeController;
use App\Http\Controllers\Auth\RegisteredWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersController;
use App\Http\Controllers\Filters\WorkersFiltersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersServiesController;

// Authenticated User

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/register-worker', [RegisteredWorkersController::class, 'store']);

    Route::post('/upgrade-account', [AccountUpgradeController::class, 'updatedata']);

   
    // Admin Routes
    
    
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::put('/worker/{worker}', [ManagingWorkersController::class, 'update']);
        Route::get('workers/filters', [WorkersFiltersController::class, 'index']);
        Route::delete('/worker/{worker}', [ManagingWorkersController::class, 'delete']);
        Route::apiResource('services', ManagingWorkersServiesController::class);


    });

});

// Auth Routes

require __DIR__ . '/auth.php';