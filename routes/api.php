<?php

/*
|--------------------------------------------------------------------------
| API Routes - FixUp Worker Management System
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Auth\AccountUpgradeController;
use App\Http\Controllers\Auth\RegisteredWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingAreasController;
use App\Http\Controllers\DashboardAdmin\ManagingCareersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersController;
use App\Http\Controllers\DashboardAdmin\ManagingWorkersServiesController;
use App\Http\Controllers\Filters\WorkersFiltersController;
use App\Http\Controllers\Profile\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes that require authentication via Laravel Sanctum.
| All routes in this group are protected and require a valid API token.
|
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Get current user's complete profile
    Route::get('/profile', [UserController::class, 'show']);
    
    // Update current user's profile information
    Route::put('/update-profile', [UserController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | Worker Registration Routes
    |--------------------------------------------------------------------------
    */
    
    // Register as a worker (create worker profile)
    Route::post('/register-worker', [RegisteredWorkersController::class, 'store']);
    
   
    // Get available services for workers

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    |
    | Routes that require admin role access.
    | Only users with 'admin' role can access these endpoints.
    |
    */
    Route::middleware('role:admin')->prefix('admin')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Worker Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for workers
        Route::apiResource('/worker', ManagingWorkersController::class);
        
        // Filter and search workers
       // Route::get('workers/filters', [WorkersFiltersController::class, 'index']);
        
        /*
        |--------------------------------------------------------------------------
        | Service Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for services
        Route::apiResource('services', ManagingWorkersServiesController::class);
        
        /*
        |--------------------------------------------------------------------------
        | Career Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for careers/professions
        Route::apiResource('careers', ManagingCareersController::class);
        
        /*
        |--------------------------------------------------------------------------
        | Area Management Routes
        |--------------------------------------------------------------------------
        */
        
        // Full CRUD operations for geographical areas
        Route::apiResource('areas', ManagingAreasController::class);

    });

});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Include the authentication routes (login, register, logout, etc.)
| from the separate auth.php file for better organization.
|
*/
 Route::get('services', [ManagingWorkersServiesController::class, 'index']);
 Route::get('careers', [ManagingCareersController::class,'index']);
 Route::get('areas', [ManagingAreasController::class,'index']);

require __DIR__ . '/auth.php';
