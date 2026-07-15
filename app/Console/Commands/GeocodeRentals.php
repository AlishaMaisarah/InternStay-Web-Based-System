<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeocodeRentals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentals:geocode {--force : Force update even if lat/lng exists}';

    protected $description = 'Geocode rentals that are missing latitude and longitude';

    public function handle(\App\Services\GeocodingService $geoService)
    {
        $rentals = \App\Models\Rental::query();
        
        if (!$this->option('force')) {
            $rentals->whereNull('lat')->orWhereNull('lng');
        }
        
        $rentals = $rentals->get();
        
        $count = $rentals->count();
        $this->info("Found {$count} rentals to geocode.");
        
        $bar = $this->output->createProgressBar($count);
        
        foreach ($rentals as $rental) {
            $address = $rental->address;
            
            // Clean up address garbage from scraper
            $address = str_ireplace(['Location:', 'View listing', 'Room for rent'], '', $address);
            $address = preg_replace('/[^\p{L}\p{N}\s,.-]/u', '', $address); // Remove emojis
            $address = trim($address);

            if (empty($address)) continue;
            
            $coords = $geoService->getCoordinates($address, $rental->property_name);
            
            if ($coords) {
                $rental->update($coords);
            } else {
                $this->warn("\nFailed to geocode: {$rental->property_name} ({$address})");
            }
            
            $bar->advance();
            // Respect API rate limits (Nominatim limit is 1 req/sec)
            sleep(1);
        }
        
        $bar->finish();
        $this->info("\nGeocoding completed!");
    }
}
