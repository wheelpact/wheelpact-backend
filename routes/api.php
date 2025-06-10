<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\FormDataController;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('/test', function (Request $request) {
//     return response()->json(['message' => 'Hello World!']);
// });

// Route::get('v1/posts', [PostController::class, 'index']);
// Route::post('v1/posts', [PostController::class, 'store']);
// Route::get('v1/posts/{post}', [PostController::class, 'show']);
// Route::put('v1/posts/{post}', [PostController::class, 'update']);
// Route::delete('v1/posts/{post}', [PostController::class, 'destroy']);

Route::prefix('post/v1')->group(function () {
    Route::get('posts', [PostController::class, 'index']);
    Route::post('posts', [PostController::class, 'store']);
    Route::get('posts/{post}', [PostController::class, 'show']);
    Route::put('posts/{post}', [PostController::class, 'update']);
    Route::delete('posts/{post}', [PostController::class, 'destroy']);
});


Route::prefix('customerApi/v1')->middleware(['allowed_ip'])->group(function () {

    // Public routes
    Route::post('customerRegister', [CustomerController::class, 'store']);
    Route::post('customergenerateOtp', [CustomerController::class, 'generateOtp']);
    Route::post('customervalidateOtpLogin', [CustomerController::class, 'validateOtpLogin']);

    // Protected routes (requires Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('customerTestDriveRequest', [CustomerController::class, 'getTestDriveRequestList']);
        Route::post('customerTestDriveRequest', [CustomerController::class, 'storeTestDriveRequest']);
        Route::get('customerDetails', [CustomerController::class, 'getCutomer']);
        Route::post('logout', [CustomerController::class, 'logout']);
    });
});


Route::prefix('dealerApi/v1')->middleware(['allowed_ip'])->group(function () {

    // Public routes
    Route::post('dealerLogin', [UserController::class, 'login']);
    Route::post('dealerRegister', [UserController::class, 'register']);


    // Protected routes (requires Sanctum token)
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [UserController::class, 'logout']);
        // Authenticated Dealer Vehicle Routes
        Route::prefix('vehicles')->group(function () {
            // List vehicles
            Route::get('/', [VehiclesController::class, 'index'])->name('dealer.vehicles.index');

            // Create new vehicle
            Route::post('/', [VehiclesController::class, 'store'])->name('dealer.vehicles.store');

            // Upload images for a new vehicle
            Route::post('{vehicle}/images', [VehiclesController::class, 'storeVehicleImages'])->name('dealer.vehicles.images.store');

            // Update existing vehicle details
            Route::put('{vehicle}', [VehiclesController::class, 'updateVehicledetails'])->name('dealer.vehicles.update');

            // Update existing vehicle images
            Route::post('{vehicle}/update-images', [VehiclesController::class, 'updateVehicleImages'])->name('dealer.vehicles.images.update');

            // Soft delete vehicle
            Route::delete('{vehicle}', [VehiclesController::class, 'destroy'])->name('dealer.vehicles.destroy');
        });
    });

    // public data for vehicle forms & front website
    Route::prefix('form-data')->group(function () {

        Route::get('getCountries', [FormDataController::class, 'getCountries'])->name('form.countries');
        Route::get('getStates', [FormDataController::class, 'getStates'])->name('form.states');
        Route::get('getCities', [FormDataController::class, 'getCities'])->name('form.cities');
        Route::get('getRTOsByState', [FormDataController::class, 'getRTOsByState'])->name('form.rtos');

        Route::get('getVehicleFuelTypes', [FormDataController::class, 'getVehicleFuelTypes'])->name('form.fueltypes');
        Route::get('getVehicleTransmissionTypes', [FormDataController::class, 'getVehicleTransmissionTypes'])->name('form.transmission');

        Route::get('/getVehicleMakes', [FormDataController::class, 'getVehicleMakes'])->name('form.makes');
        Route::get('/getVehicleMakeModels', [FormDataController::class, 'getVehicleMakeModels'])->name('form.models');
        Route::get('/getVehicleModelVariants', [FormDataController::class, 'getVehicleModelVariants'])->name('form.variants');
        Route::get('/getVehicleBodyTypes', [FormDataController::class, 'getVehicleBodyTypes'])->name('form.bodytypes');
    });
});
