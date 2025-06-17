<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time', 'end_time',
        'slot_duration_minutes', 'break_duration_minutes',
        'max_patients_per_slot', 'is_active',
        'effective_from', 'effective_until'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }
}
