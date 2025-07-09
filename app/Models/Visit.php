<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'visitor_name',
        'visitor_phone',
        'visit_date',
        'start_time',
        'end_time',
        'car_model',
        'car_color',
        'license_plate',
        'notes',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    protected $hidden = [
        'resident_id',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
