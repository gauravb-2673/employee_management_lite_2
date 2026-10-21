<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\EmployeeController as V1EmployeeController;
use App\Http\Controllers\Api\V2\EmployeeController as V2EmployeeController;

/*
|--------------------------------------------------------------------------
| API Version 1
|--------------------------------------------------------------------------
|
| Keeps your existing V1 controller and resource routes.
|
*/

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('employees', V1EmployeeController::class);
    });

/*
|--------------------------------------------------------------------------
| API Version 2
|--------------------------------------------------------------------------
|
| Uses the new V2 controller and its different response format.
|
*/

Route::prefix('v2')
    ->name('api.v2.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('employees', V2EmployeeController::class);
    });
