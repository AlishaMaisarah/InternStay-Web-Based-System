<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'internship_name',
        'company',
        'industry',
        'location',
        'source',
        'source_url',
        'contact_email',
        'contact_phone',
        'contact_person',
        'description',
        'lat',
        'lng',
        'is_closed',
        'is_suspended',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function favorites()
    {
        return $this->morphMany(\App\Models\Favorite::class, 'favoritable');
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\InternshipReview::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('is_suspended', false)->where('is_closed', false);
    }
}

