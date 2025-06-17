<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'service', 'department']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date')) {
            $query->where('appointment_date', $request->date);
        }
        
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        
        // Role-based filtering
        if (auth()->user()->role === 'doctor') {
            $query->where('doctor_id', auth()->user()->doctorProfile->id);
        } elseif (auth()->user()->role === 'patient') {
            $query->where('patient_id', auth()->id());
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
                             ->orderBy('start_time', 'desc')
                             ->paginate(20);
        
        return response()->json($appointments);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'patient_notes' => 'nullable|string|max:1000',
            'priority' => 'in:normal,urgent,emergency'
        ]);
        
        // Check if slot is available
        $isAvailable = $this->checkSlotAvailability(
            $validated['doctor_id'],
            $validated['appointment_date'],
            $validated['start_time']
        );
        
        if (!$isAvailable) {
            return response()->json([
                'message' => 'Selected time slot is not available'
            ], 422);
        }
        
        // Get service details for duration and pricing
        $service = Service::findOrFail($validated['service_id']);
        $endTime = Carbon::parse($validated['start_time'])
                         ->addMinutes($service->duration_minutes)
                         ->format('H:i');
        
        $appointment = Appointment::create([
            ...$validated,
            'end_time' => $endTime,
            'department_id' => $service->department_id,
            'total_amount' => $service->base_price,
            'created_by' => auth()->id()
        ]);
        
        // Send notification
        $this->sendAppointmentNotification($appointment, 'appointment_created');
        
        return response()->json($appointment->load(['patient', 'doctor.user', 'service']), 201);
    }
    
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        return response()->json($appointment->load([
            'patient', 'doctor.user', 'service', 'department',
            'createdBy', 'cancelledBy', 'histories.changedBy'
        ]));
    }
    
    public function confirm(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        if ($appointment->status !== 'scheduled') {
            return response()->json([
                'message' => 'Only scheduled appointments can be confirmed'
            ], 422);
        }
        
        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
        
        // Log the change
        $this->logAppointmentChange($appointment, 'status', 'scheduled', 'confirmed', 'Appointment confirmed');
        
        // Send notification
        $this->sendAppointmentNotification($appointment, 'appointment_confirmed');
        
        return response()->json($appointment);
    }
    
    public function cancel(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $this->authorize('update', $appointment);
        
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return response()->json([
                'message' => 'Cannot cancel completed or already cancelled appointment'
            ], 422);
        }
        
        $appointment->update([
            'status' => 'cancelled',
            'cancelled_by' => auth()->id(),
            'cancellation_reason' => $validated['reason']
        ]);
        
        // Log the change
        $this->logAppointmentChange($appointment, 'status', $appointment->getOriginal('status'), 'cancelled', $validated['reason']);
        
        // Send notification
        $this->sendAppointmentNotification($appointment, 'appointment_cancelled');
        
        return response()->json($appointment);
    }
    
    public function complete(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'doctor_notes' => 'nullable|string|max:2000'
        ]);
        
        $this->authorize('update', $appointment);
        
        if ($appointment->status !== 'in_progress') {
            return response()->json([
                'message' => 'Only in-progress appointments can be completed'
            ], 422);
        }
        
        $appointment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'doctor_notes' => $validated['doctor_notes'] ?? $appointment->doctor_notes
        ]);
        
        // Log the change
        $this->logAppointmentChange($appointment, 'status', 'in_progress', 'completed', 'Appointment completed');
        
        // Send notification
        $this->sendAppointmentNotification($appointment, 'appointment_completed');
        
        return response()->json($appointment);
    }
    
    private function checkSlotAvailability($doctorId, $date, $time)
    {
        // Check if doctor has existing appointment at this time
        $existingAppointment = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('start_time', $time)
            ->whereNotIn('status', ['cancelled'])
            ->exists();
            
        if ($existingAppointment) {
            return false;
        }
        
        // Check if doctor is on leave
        $isOnLeave = DoctorLeave::where('doctor_id', $doctorId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('status', 'approved')
            ->where(function ($query) use ($time) {
                $query->where('is_full_day', true)
                      ->orWhere(function ($q) use ($time) {
                          $q->where('start_time', '<=', $time)
                            ->where('end_time', '>=', $time);
                      });
            })
            ->exists();
            
        if ($isOnLeave) {
            return false;
        }
        
        // Check if within doctor's schedule
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
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
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->exists();
            
        return $schedule;
    }
    
    private function logAppointmentChange($appointment, $field, $oldValue, $newValue, $reason)
    {
        AppointmentHistory::create([
            'appointment_id' => $appointment->id,
            'field_changed' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => auth()->id(),
            'reason' => $reason
        ]);
    }
    
    private function sendAppointmentNotification($appointment, $type)
    {
        // Send to patient
        Notification::create([
            'user_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'type' => $type,
            'title' => $this->getNotificationTitle($type),
            'message' => $this->getNotificationMessage($type, $appointment),
            'channel' => 'system'
        ]);
        
        // Send to doctor
        Notification::create([
            'user_id' => $appointment->doctor->user_id,
            'appointment_id' => $appointment->id,
            'type' => $type,
            'title' => $this->getNotificationTitle($type),
            'message' => $this->getNotificationMessage($type, $appointment),
            'channel' => 'system'
        ]);
    }
    
    private function getNotificationTitle($type)
    {
        return match($type) {
            'appointment_created' => 'New Appointment Scheduled',
            'appointment_confirmed' => 'Appointment Confirmed',
            'appointment_cancelled' => 'Appointment Cancelled',
            'appointment_completed' => 'Appointment Completed',
            default => 'Appointment Update'
        };
    }
    
    private function getNotificationMessage($type, $appointment)
    {
        $date = $appointment->appointment_date->format('M j, Y');
        $time = $appointment->start_time->format('g:i A');
        
        return match($type) {
            'appointment_created' => "Your appointment has been scheduled for {$date} at {$time}",
            'appointment_confirmed' => "Your appointment on {$date} at {$time} has been confirmed",
            'appointment_cancelled' => "Your appointment on {$date} at {$time} has been cancelled",
            'appointment_completed' => "Your appointment on {$date} at {$time} has been completed",
            default => "Your appointment has been updated"
        };
    }
}