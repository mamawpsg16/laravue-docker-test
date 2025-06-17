<?php
namespace App\Services;

use App\Models\Appointment;

class AppointmentService
{
    public function findConflicts($doctorId, $date, $startTime, $endTime, $excludeAppointmentId = null)
    {
        $query = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<', $startTime)
                         ->where('end_time', '>', $endTime);
                  });
            });
        
        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }
        
        return $query->get();
    }
    
    public function generateTimeSlots($schedule, $date, $excludeBooked = true)
    {
        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $slotDuration = $schedule->slot_duration_minutes;
        $breakDuration = $schedule->break_duration_minutes;
        
        while ($startTime->lt($endTime)) {
            $slotEndTime = $startTime->copy()->addMinutes($slotDuration);
            
            if ($slotEndTime->lte($endTime)) {
                $isAvailable = true;
                
                if ($excludeBooked) {
                    $conflicts = $this->findConflicts(
                        $schedule->doctor_id,
                        $date,
                        $startTime->format('H:i:s'),
                        $slotEndTime->format('H:i:s')
                    );
                    
                    $isAvailable = $conflicts->isEmpty();
                }
                
                $slots[] = [
                    'start_time' => $startTime->format('H:i'),
                    'end_time' => $slotEndTime->format('H:i'),
                    'formatted_time' => $startTime->format('g:i A') . ' - ' . $slotEndTime->format('g:i A'),
                    'available' => $isAvailable,
                    'duration_minutes' => $slotDuration
                ];
            }
            
            $startTime->addMinutes($slotDuration + $breakDuration);
        }
        
        return $slots;
    }
}