<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are for handling email verification and the Vue SPA fallback.
|
*/

// Handle email verification links
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('http://localhost:5175/dashboard'); // Change if deploying elsewhere
})->middleware(['signed'])->name('verification.verify');

// Optional: fallback to Vue SPA (if using Laravel to serve the app in production)
Route::view('/{any}', 'welcome')->where('any', '.*');
