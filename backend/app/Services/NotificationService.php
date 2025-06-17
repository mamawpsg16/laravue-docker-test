<?php
namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function sendAppointmentReminder($appointment, $hoursBeforeAppointment = 24)
    {
        $scheduledAt = Carbon::parse($appointment->appointment_date . ' ' . $appointment->start_time)
            ->subHours($hoursBeforeAppointment);
        
        if ($scheduledAt->isFuture()) {
            Notification::create([
                'user_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'type' => 'appointment_reminder',
                'title' => 'Appointment Reminder',
                'message' => "You have an appointment tomorrow at {$appointment->start_time->format('g:i A')} with Dr. {$appointment->doctor->user->full_name}",
                'channel' => 'email',
                'scheduled_at' => $scheduledAt
            ]);
        }
    }
    
    public function sendBulkReminders()
    {
        $tomorrow = now()->addDay()->toDateString();
        
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->where('appointment_date', $tomorrow)
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->get();
        
        foreach ($appointments as $appointment) {
            $this->sendAppointmentReminder($appointment, 24);
        }
        
        return $appointments->count();
    }
}