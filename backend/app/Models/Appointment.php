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
    

     // Define valid statuses
     const STATUS_PENDING = 'pending';
     const STATUS_CONFIRMED = 'confirmed';
     const STATUS_COMPLETED = 'completed';
     const STATUS_CANCELLED = 'cancelled';
     const STATUS_NO_SHOW = 'no_show';
 
     const VALID_STATUSES = [
         self::STATUS_PENDING,
         self::STATUS_CONFIRMED,
         self::STATUS_COMPLETED,
         self::STATUS_CANCELLED,
         self::STATUS_NO_SHOW,
     ];

    protected $fillable = [
        'client_id', 'provider_id', 'hospital_id', 'service_id',
        'appointment_date', 'start_time', 'end_time', 'status',
        'notes', 'client_notes', 'provider_notes', 'total_amount',
        'booking_reference'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
    ];

    // Boot method to generate booking reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            $appointment->booking_reference = 'APT-' . strtoupper(Str::random(8));
        });
    }

    // Validation rules
    public static function validationRules()
    {
        return [
            'client_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:providers,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:' . implode(',', self::VALID_STATUSES),
            'total_amount' => 'required|numeric|min:0',
        ];
    }

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppointmentNotification::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
                    ->whereIn('status', ['confirmed', 'pending']);
    }

    public function scopeToday($query)
    {
        return $query->where('appointment_date', now()->toDateString());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    // Helper methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->appointment_date > now()->addHours(24);
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->appointment_date > now()->addHours(24);
    }

      // Status checking methods
    public function isPending() { return $this->status === self::STATUS_PENDING; }
    public function isConfirmed() { return $this->status === self::STATUS_CONFIRMED; }
    public function isCompleted() { return $this->status === self::STATUS_COMPLETED; }
    public function isCancelled() { return $this->status === self::STATUS_CANCELLED; }
    public function isNoShow() { return $this->status === self::STATUS_NO_SHOW; }
}
