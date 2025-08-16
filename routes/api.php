<?php

use App\Enums\DeliveryStatusEnum;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::resource('/user', UserController::class);
    Route::post('/user/{user}/address', [AddressController::class, 'createUserAddress']);
    Route::patch('/address/{address}', [AddressController::class, 'update']);
    Route::resource('/driver', DriverController::class);
    Route::resource('/customer', CustomerController::class);
    Route::post('/customer/{customer}/address', [AddressController::class, 'createCustomerAddress']);
    Route::resource('/package', PackageController::class);
    Route::get('/report', ReportController::class);
});
