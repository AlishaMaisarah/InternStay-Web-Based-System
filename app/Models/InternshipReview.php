<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
