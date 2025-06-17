<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 'name', 'code', 'description',
        'duration_minutes', 'base_price', 'preparation_instructions',
        'requires_fasting', 'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'requires_fasting' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}