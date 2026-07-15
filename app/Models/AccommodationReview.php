<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccommodationReview extends Model
{
    use HasFactory;

    // Explicitly declare table name since it uses accommodation_reviews
    protected $table = 'accommodation_reviews';

    protected $fillable = [
        'accommodation_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Rental::class, 'accommodation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
