<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::resource('/user', UserController::class);
    Route::resource('/driver', DriverController::class);
    Route::resource('/customer', CustomerController::class);
    Route::resource('/delivery', DeliveryController::class);
});
