<?php

use App\Services\Logger;
use App\Services\Firewall;
use App\Services\Transistor;
use App\Contracts\UserContext;
use App\Services\ExportService;
use App\Services\ReportService;
use App\Services\SimpleService;
use App\Services\RandomGenerator;
use App\Services\ResolvingService;
use Illuminate\Support\Facades\Route;
use Psr\Container\ContainerInterface;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BindingPrimitiveController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are for handling email verification and the Vue SPA fallback.
|
*/

Route::get('/', function (Transistor $transistor) {
    return $transistor->publish();
});

Route::get('/simple', function (SimpleService $service) {
    return $service->greet(); // No binding needed!
});

Route::get('/checkout', [CheckoutController::class, 'pay']);

Route::get('/report-service', function(ReportService $reportService) {
    return $reportService->generate();
});

Route::get('/random-generator', function(){
    return [app(RandomGenerator::class)->id, app(RandomGenerator::class)->id];
});

Route::get('/instance', function(){
    return app(App\Models\User::class);
});

Route::get('/current-user', function (UserContext $context) {
    return [
        'name' => $context->getUser()->name,
        'email' => $context->getUser()->email,
    ];
});

Route::get('/contextual-test', function (ReportService $report, ExportService $export) {
    $report->run();
    $export->run();
});

Route::get('/binding-primitives',[BindingPrimitiveController::class ,'run']);

Route::get('binding-tagged', [ReportController::class, 'index']);

Route::get('typed-variadics', function (Firewall $firewall) {
        return $firewall->process('This is a damn hell of an pass problem');
        // Returns filtered content
});

Route::get('extending-bindings', function(Logger $logger){
    $logger->log('This is a log message');
});

Route::get('resolving-service', function(ResolvingService $resolvingService){
    return $resolvingService->index();
});

Route::get('/psr-11', function (ContainerInterface $container) {
    // Retrieve the Transistor service from the container using PSR-11 interface
    $service = $container->get(Transistor::class);
    return $service->publish();
    // Use the service...
});
// Handle email verification links
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('http://localhost:5175/dashboard'); // Change if deploying elsewhere
})->middleware(['signed'])->name('verification.verify');

// Optional: fallback to Vue SPA (if using Laravel to serve the app in production)
Route::view('/{any}', 'welcome')->where('any', '.*');
