<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();
        
        if ($user->role === 'doctor') {
            $doctorId = $user->doctorProfile->id;
            
            $stats = [
                'today_appointments' => Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', $today)
                    ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
                    ->count(),
                'completed_today' => Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', $today)
                    ->where('status', 'completed')
                    ->count(),
                'upcoming_appointments' => Appointment::where('doctor_id', $doctorId)
                    ->where('appointment_date', '>', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'total_patients' => Appointment::where('doctor_id', $doctorId)
                    ->distinct('patient_id')
                    ->count(),
            ];
        } elseif ($user->role === 'patient') {
            $stats = [
                'upcoming_appointments' => Appointment::where('patient_id', $user->id)
                    ->where('appointment_date', '>=', $today)
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'completed_appointments' => Appointment::where('patient_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
                'cancelled_appointments' => Appointment::where('patient_id', $user->id)
                    ->where('status', 'cancelled')
                    ->count(),
            ];
        } else {
            // Admin stats
            $stats = [
                'total_appointments' => Appointment::count(),
                'today_appointments' => Appointment::where('appointment_date', $today)->count(),
                'total_patients' => User::where('role', 'patient')->count(),
                'total_doctors' => User::where('role', 'doctor')->count(),
                'active_doctors' => DoctorProfile::where('is_available', true)->count(),
                'total_departments' => Department::where('is_active', true)->count(),
                'revenue_today' => Appointment::where('appointment_date', $today)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount'),
                'revenue_month' => Appointment::whereMonth('appointment_date', now()->month)
                    ->where('payment_status', 'paid')
                    ->sum('total_amount'),
            ];
        }
        
        return response()->json($stats);
    }
    
    public function todayAppointments(Request $request)
    {
        $user = auth()->user();
        $today = now()->toDateString();
        
        $query = Appointment::with(['patient', 'doctor.user', 'service'])
            ->where('appointment_date', $today);
        
        if ($user->role === 'doctor') {
            $query->where('doctor_id', $user->doctorProfile->id);
        } elseif ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }
        
        $appointments = $query->orderBy('start_time')->get();
        
        return response()->json($appointments);
    }
}