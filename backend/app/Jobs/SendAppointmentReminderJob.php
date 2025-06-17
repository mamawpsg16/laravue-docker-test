<?php

namespace App\Jobs;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use App\Services\NotificationService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAppointmentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        private Appointment $appointment,
        private string $reminderType = 'day_before'
    ) {}
    
    public function handle(NotificationService $notificationService)
    {
        $hoursBeforeAppointment = match($this->reminderType) {
            'day_before' => 24,
            'hour_before' => 1,
            'week_before' => 168,
            default => 24
        };
        
        $notificationService->sendAppointmentReminder(
            $this->appointment,
            $hoursBeforeAppointment
        );
    }
}
