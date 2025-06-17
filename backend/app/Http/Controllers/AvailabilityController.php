<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorLeave;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;

class AvailabilityController extends Controller
{
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'date' => 'required|date|after_or_equal:today',
            'service_id' => 'nullable|exists:services,id'
        ]);
        
        $doctor = DoctorProfile::findOrFail($validated['doctor_id']);
        $date = Carbon::parse($validated['date']);
        $dayOfWeek = $date->dayOfWeek;
        
        // Get doctor's schedule for this day
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where(function ($query) use ($date) {
                $query->whereNull('effective_from')
                      ->orWhere('effective_from', '<=', $date);
            })
            ->where(function ($query) use ($date) {
                $query->whereNull('effective_until')
                      ->orWhere('effective_until', '>=', $date);
            })
            ->first();
        
        if (!$schedule) {
            return response()->json(['slots' => []]);
        }
        
        // Check if doctor is on leave
        $isOnLeave = DoctorLeave::where('doctor_id', $doctor->id)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('status', 'approved')
            ->where('is_full_day', true)
            ->exists();
        
        if ($isOnLeave) {
            return response()->json(['slots' => []]);
        }
        
        // Generate time slots
        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $slotDuration = $schedule->slot_duration_minutes;
        
        while ($startTime->lt($endTime)) {
            $slotTime = $startTime->format('H:i');
            
            // Check if slot is available
            $isBooked = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $date)
                ->where('start_time', $slotTime)
                ->whereNotIn('status', ['cancelled'])
                ->exists();
            
            // Check partial leave
            $isPartialLeave = DoctorLeave::where('doctor_id', $doctor->id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->where('status', 'approved')
                ->where('is_full_day', false)
                ->where('start_time', '<=', $slotTime)
                ->where('end_time', '>=', $slotTime)
                ->exists();
            
            if (!$isBooked && !$isPartialLeave) {
                $slots[] = [
                    'time' => $slotTime,
                    'formatted_time' => $startTime->format('g:i A'),
                    'available' => true
                ];
            }
            
            $startTime->addMinutes($slotDuration + $schedule->break_duration_minutes);
        }
        
        return response()->json(['slots' => $slots]);
    }
}
