<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);


// Route::get('profile', [UserController::class , 'profile'])->middleware('auth:sanctum');
// Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('profile', [UserController::class , 'profile'])->middleware('auth:sanctum');
});


Route::apiResource('products', ProductController::class);
