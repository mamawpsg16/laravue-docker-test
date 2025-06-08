<?php
namespace App\Models;

use App\Models\Provider;
use App\Models\Appointment;
use Laravel\Scout\Searchable;
use App\Models\ProviderSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hospital extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name', 'address', 'phone', 'email', 'description', 
        'image', 'latitude', 'longitude', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function providers()
    {
        return $this->belongsToMany(Provider::class)->withTimestamps();
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scout searchable array
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
        ];
    }
}
