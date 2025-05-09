<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //  Your other API routes that require authentication go here
    // Route::get('/users', [YourController::class, 'index']);
    // Route::get('/users/{id}', [YourController::class, 'show']);
    // Route::post('/users', [YourController::class, 'store']);
    // Route::put('/users/{id}', [YourController::class, 'update']);
    // Route::delete('/users/{id}', [YourController::class, 'destroy']);
});


