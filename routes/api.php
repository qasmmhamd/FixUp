<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Filters\WorkerController;
use App\Http\Controllers\Auth\AccountUpgradeController;
use App\Http\Controllers\Auth\RegisteredWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersController;
use App\Http\Controllers\Filters\WorkersFiltersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersServiesController;
use App\Http\Controllers\DashboardAdmin\ManagingCareersController;

// Authenticated User

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/register-worker', [RegisteredWorkersController::class, 'store']);
    Route::apiResource('workers', ManagingWorkersController::class)->only(['index', 'show']);
    Route::post('/upgrade-account', [AccountUpgradeController::class, 'updatedata']);
    Route::get('services', [ManagingWorkersServiesController::class, 'index']);

   
    // Admin Routes
    
    
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        Route::apiResource('/worker',ManagingWorkersController::class);
        Route::get('workers/filters', [WorkersFiltersController::class, 'index']);
        Route::apiResource('services', ManagingWorkersServiesController::class);
        Route::apiResource('careers', ManagingCareersController::class);
        

    });

});

// Auth Routes

require __DIR__ . '/auth.php';