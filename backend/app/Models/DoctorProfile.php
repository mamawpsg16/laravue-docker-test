<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'license_number', 'specializations', 'experience_years',
        'qualifications', 'consultation_fee', 'about', 'languages_spoken',
        'is_available', 'accepts_emergency'
    ];

    protected $casts = [
        'specializations' => 'array',
        'qualifications' => 'array',
        'languages_spoken' => 'array',
        'consultation_fee' => 'decimal:2',
        'is_available' => 'boolean',
        'accepts_emergency' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'doctor_departments')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function leaves()
    {
        return $this->hasMany(DoctorLeave::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->whereJsonContains('specializations', $specialization);
    }
}
