<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeocodeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:geocode';
    protected $description = 'Geocode all rentals and internships that are missing coordinates';

    public function handle(\App\Services\GeocodingService $geocodingService)
    {
        $this->info("Starting geocoding process...");

        // 1. Geocode Rentals
        $rentals = \App\Models\Rental::whereNull('lat')->orWhereNull('lng')->get();
        $this->info("Found " . $rentals->count() . " rentals to geocode.");

        foreach ($rentals as $rental) {
            $this->info("Geocoding Rental: " . $rental->property_name);
            $coords = $geocodingService->getCoordinates($rental->address);
            
            if ($coords) {
                $rental->update($coords);
                $this->info("  Success: " . $coords['lat'] . ", " . $coords['lng']);
            } else {
                $this->error("  Failed to geocode address: " . $rental->address);
            }
            
            // Respect Nominatim rate limit (1 request per second)
            sleep(1);
        }

        // 2. Geocode Internships
        $internships = \App\Models\Internship::whereNull('lat')->orWhereNull('lng')->get();
        $this->info("Found " . $internships->count() . " internships to geocode.");

        foreach ($internships as $internship) {
            $this->info("Geocoding Internship: " . $internship->internship_name);
            $coords = $geocodingService->getCoordinates($internship->location);
            
            if ($coords) {
                $internship->update($coords);
                $this->info("  Success: " . $coords['lat'] . ", " . $coords['lng']);
            } else {
                $this->error("  Failed to geocode location: " . $internship->location);
            }
            
            sleep(1);
        }

        $this->info("Geocoding process completed.");
    }
}
