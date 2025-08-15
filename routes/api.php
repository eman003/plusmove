<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::resource('/user', UserController::class);
    Route::resource('/driver', DriverController::class);
    Route::resource('/customer', CustomerController::class);
    Route::resource('/delivery', DeliveryController::class);
    Route::resource('/package', PackageController::class);
    Route::get('/reports', ReportController::class);
});
