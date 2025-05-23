<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

 
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

    //  Your other API routes that require authentication go here
    // Route::get('/users', [YourController::class, 'index']);
    // Route::get('/users/{id}', [YourController::class, 'show']);
    // Route::post('/users', [YourController::class, 'store']);
    // Route::put('/users/{id}', [YourController::class, 'update']);
    // Route::delete('/users/{id}', [YourController::class, 'destroy']);
});


