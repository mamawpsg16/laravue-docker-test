<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'start_date', 'end_date', 'start_time', 'end_time',
        'type', 'reason', 'is_full_day', 'status', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_full_day' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
