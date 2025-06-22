<?php

use Illuminate\Http\Request;
use App\Services\RequestTracker;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api and grouped under middleware.
| Your frontend will use /api/v1/... for all API calls.
|
*/

Route::prefix('v1')->group(function () {

    Route::get('/track', function (RequestTracker $a) {
        $b = app(RequestTracker::class);

        return [
            'a_id' => $a->id,
            'b_id' => $b->id,
            'same_instance' => $a === $b, // ✅ true for scoped binding
        ];
    });
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Authenticated routes (IMPORTANT: includes 'web' for session auth)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix('documents')->group(function () {
            Route::post('/upload', [DocumentController::class, 'store']);
            Route::get('/index', [DocumentController::class, 'index']);
            
        });
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        Route::post('/email/verification-notification', function (Request $request) {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified.']);
            }

            $request->user()->sendEmailVerificationNotification();
            return response()->json(['message' => 'Verification link sent!']);
        })->middleware('throttle:6,1')->name('verification.send');

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);

        // Department routes
        Route::apiResource('departments', DepartmentController::class);
        Route::get('/departments/{department}/services', [DepartmentController::class, 'services']);
        Route::get('/departments/{department}/doctors', [DepartmentController::class, 'doctors']);

        // Service routes
        Route::apiResource('services', ServiceController::class);

        // Doctor routes
        Route::apiResource('doctors', DoctorController::class);
        Route::get('/doctors/{doctor}/availability', [DoctorController::class, 'availability']);
        Route::get('/doctors/{doctor}/schedule', [DoctorController::class, 'schedule']);
        Route::post('/doctors/{doctor}/schedule', [DoctorController::class, 'updateSchedule']);
        Route::get('/doctors/{doctor}/leaves', [DoctorController::class, 'leaves']);
        Route::post('/doctors/{doctor}/leaves', [DoctorController::class, 'createLeave']);

        // Appointment routes
        Route::apiResource('appointments', AppointmentController::class);
        Route::get('/appointments/{appointment}/history', [AppointmentController::class, 'history']);
        Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm']);
        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
        Route::patch('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule']);
        Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete']);

        // Availability and scheduling
        Route::get('/availability', [AvailabilityController::class, 'index']);
        Route::get('/availability/slots', [AvailabilityController::class, 'getAvailableSlots']);

        // Dashboard routes
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('/dashboard/appointments/today', [DashboardController::class, 'todayAppointments']);
        Route::get('/dashboard/appointments/upcoming', [DashboardController::class, 'upcomingAppointments']);

        // Notification routes
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

        // Search routes
        Route::get('/search/doctors', [SearchController::class, 'doctors']);
        Route::get('/search/appointments', [SearchController::class, 'appointments']);
        Route::get('/search/patients', [SearchController::class, 'patients']);

        // Admin-only Reports
        Route::middleware('role:admin')->group(function () {
            Route::get('/reports/appointments', [ReportController::class, 'appointments']);
            Route::get('/reports/revenue', [ReportController::class, 'revenue']);
            Route::get('/reports/doctors', [ReportController::class, 'doctors']);
            Route::get('/reports/departments', [ReportController::class, 'departments']);
        });
    });
});
