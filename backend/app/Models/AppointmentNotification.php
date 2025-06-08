<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'type', 'event', 'sent', 'sent_at', 'scheduled_for'
    ];

    protected $casts = [
        'sent' => 'boolean',
        'sent_at' => 'datetime',
        'scheduled_for' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('sent', false);
    }

    public function scopeDue($query)
    {
        return $query->where('scheduled_for', '<=', now())
                    ->where('sent', false);
    }
}