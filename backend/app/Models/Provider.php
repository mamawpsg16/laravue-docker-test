<?php

namespace App\Models;

use App\Models\User;
use App\Models\Service;
use App\Models\Hospital;
use App\Models\Appointment;
use Laravel\Scout\Searchable;
use App\Models\ProviderSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id', 'specialization', 'license_number', 'bio', 
        'experience_years', 'consultation_fee', 'qualifications', 'is_available'
    ];

    protected $casts = [
        'qualifications' => 'array',
        'is_available' => 'boolean',
        'consultation_fee' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Provider works at multiple hospitals
    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class)->withTimestamps();
    }
    // Provider offers multiple services with custom pricing
    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('custom_price')->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(ProviderSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', 'like', "%{$specialization}%");
    }

    // Scout searchable array
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'specialization' => $this->specialization,
            'bio' => $this->bio,
            'experience_years' => $this->experience_years,
        ];
    }

    // Helper methods
    public function getAvailableTimeSlotsForDate($date, $hospitalId)
    {
        $schedule = $this->schedules()
            ->where('hospital_id', $hospitalId)
            ->where('day_of_week', date('w', strtotime($date)))
            ->first();

        if (!$schedule) {
            return [];
        }

        // Get existing appointments for the date
        $appointments = $this->appointments()
            ->where('appointment_date', $date)
            ->where('hospital_id', $hospitalId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        // Generate available time slots (logic implementation)
        return $this->generateTimeSlots($schedule, $appointments);
    }

    private function generateTimeSlots($schedule, $appointments)
    {
        // Implementation for generating available time slots
        // This would include logic to create 30-minute slots and exclude booked times
        $slots = [];
        // ... time slot generation logic
        return $slots;
    }
}
