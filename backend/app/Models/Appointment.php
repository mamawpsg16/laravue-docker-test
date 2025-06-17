<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use App\Models\Hospital;
use App\Models\Provider;
use Illuminate\Support\Str;
use App\Models\AppointmentNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number', 'patient_id', 'doctor_id', 'service_id',
        'department_id', 'appointment_date', 'start_time', 'end_time',
        'status', 'priority', 'patient_notes', 'doctor_notes', 'symptoms',
        'total_amount', 'payment_status', 'confirmed_at', 'completed_at',
        'created_by', 'cancelled_by', 'cancellation_reason'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function histories()
    {
        return $this->hasMany(AppointmentHistory::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
                    ->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', now()->toDateString());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Boot method for auto-generating appointment number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            $appointment->appointment_number = static::generateAppointmentNumber();
        });
    }

    private static function generateAppointmentNumber()
    {
        $prefix = 'APT';
        $date = now()->format('Ymd');
        $lastAppointment = static::whereDate('created_at', now()->toDateString())
                                ->latest('id')
                                ->first();
        
        $sequence = $lastAppointment ? 
            (int) substr($lastAppointment->appointment_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
