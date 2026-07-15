<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Send custom email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'onboarding_completed',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'onboarding_completed' => 'boolean',
        'onboarding_completed_at' => 'datetime',
    ];

    public function favorites()
    {
        return $this->hasMany(\App\Models\Favorite::class);
    }

    public function preferences()
    {
        return $this->hasOne(\App\Models\UserPreference::class);
    }

    public function searchHistory()
    {
        return $this->hasMany(\App\Models\UserSearchHistory::class);
    }

    public function internshipReviews()
    {
        return $this->hasMany(\App\Models\InternshipReview::class);
    }

    public function accommodationReviews()
    {
        return $this->hasMany(\App\Models\AccommodationReview::class);
    }

    public function companyProfile()
    {
        return $this->hasOne(\App\Models\CompanyProfile::class);
    }

    public function internships()
    {
        return $this->hasMany(\App\Models\Internship::class);
    }

    public function isCompany()
    {
        return $this->role === 'company';
    }

    public function isApprovedCompany()
    {
        return $this->isCompany() && 
            $this->companyProfile && 
            $this->companyProfile->verification_status === 'Approved';
    }

    public function isPendingCompany()
    {
        return $this->isCompany() && 
            $this->companyProfile && 
            $this->companyProfile->verification_status === 'Pending';
    }

    public function isRejectedCompany()
    {
        return $this->isCompany() && 
            $this->companyProfile && 
            $this->companyProfile->verification_status === 'Rejected';
    }
}
