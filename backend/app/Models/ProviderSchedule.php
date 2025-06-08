<?php

namespace App\Models;

use App\Models\Hospital;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id', 'hospital_id', 'day_of_week', 
        'start_time', 'end_time', 'break_start', 'break_end', 'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
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
