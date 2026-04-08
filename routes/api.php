<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});

Route::post('/upgrade-account', [App\Http\Controllers\Auth\AccountUpgradeController::class, 'updatedata'])->middleware('auth:sanctum');


require __DIR__.'/auth.php';
