<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\PropertyScraperService;


class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_name',
        'property_type',
        'address',
        'rent_amount',
        'currency',
        'bedrooms',
        'bathrooms',
        'description',
        //'contact_name',
        //'contact_phone',
        //'contact_email',
        'is_available',
        'source',
        'source_url',
        'image_url',
        'lat',
        'lng',
        'is_closed',
    ];

    public function favorites()
    {
        return $this->morphMany(\App\Models\Favorite::class, 'favoritable');
    }

    public function scrapeReal(PropertyScraperService $scraper, string $state, string $city)
    {
        $count = $scraper->scrape($state, $city, 15);

        return redirect()->route('rentals.index')
            ->with('success', "Scraped {$count} live rental listings from PropertyGuru successfully.");
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\AccommodationReview::class, 'accommodation_id')->latest();
    }


}
