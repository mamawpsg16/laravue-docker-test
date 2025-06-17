<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'code', 'description', 'head_doctor_id', 
        'is_active', 'operating_hours'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'operating_hours' => 'array',
    ];

    // Relationships
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(DoctorProfile::class, 'doctor_departments');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function headDoctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'head_doctor_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
