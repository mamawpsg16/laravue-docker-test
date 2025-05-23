<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    return redirect('http://localhost:5174/dashboard');
})->middleware(['signed'])->name('verification.verify');


Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return redirect('/'); // Or wherever your SPA login route is
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('your@email.com')
                ->subject('Test Email');
    });

    return 'Email sent!';
});

Route::get('/', function () {
    return view('welcome');
});
