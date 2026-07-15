<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_of_study',
        'notification_frequency',
        'notify_internships',
        'notify_rentals',
        'preferred_industries',
        'preferred_internship_locations',
        'preferred_property_types',
        'preferred_rental_states',
        'max_rental_price',
        'last_notified_at',
    ];

    protected $casts = [
        'notify_internships' => 'boolean',
        'notify_rentals' => 'boolean',
        'preferred_industries' => 'array',
        'preferred_internship_locations' => 'array',
        'preferred_property_types' => 'array',
        'preferred_rental_states' => 'array',
        'max_rental_price' => 'decimal:2',
        'last_notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
