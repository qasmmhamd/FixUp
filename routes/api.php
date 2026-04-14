<?php

use App\Http\Controllers\DashboardAdmin\EnterWorkersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('/upgrade-account', [App\Http\Controllers\Auth\AccountUpgradeController::class, 'updatedata'])->middleware('auth:sanctum');
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/admin/dashboard', [EnterWorkersController::class, 'store']);

});
Route::get('/test', function () {
    return Auth::user()->role;
})->middleware('auth:sanctum');

require __DIR__.'/auth.php';
