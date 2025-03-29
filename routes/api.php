<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;


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
        // Route::get('customerTestDriveRequest', [CustomerController::class, 'getTestDriveRequestList']);
        // Route::post('customerTestDriveRequest', [CustomerController::class, 'storeTestDriveRequest']);
        // Route::get('customerDetails', [CustomerController::class, 'getCutomer']);
        // Route::post('logout', [CustomerController::class, 'logout']);
    });
});