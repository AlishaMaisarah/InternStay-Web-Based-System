<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PropertyScraperService;

class ScrapeIProperty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:iproperty {state} {city?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape rental listings from iProperty live using dynamic browser automation';

    /**
     * Execute the console command.
     */
    public function handle(PropertyScraperService $scraper)
    {
        $state = $this->argument('state');
        // Default city to state name if not provided
        $city = $this->argument('city') ?: ' '; 

        $this->info("Starting live iProperty scraper for State: {$state} | City: {$city}...");
        
        // Call service with 'iproperty' source
        $result = $scraper->scrape($state, $city, 10, 'iproperty');
        
        if (is_array($result)) {
            $new = $result['new'];
            $updated = $result['updated'];
            $total = $result['total'];
            if ($new > 0 || $updated > 0) {
                $this->info("SUCCESS! Scraped {$total} listings ({$new} new, {$updated} updated).");
                $this->info("Check your Admin Panel -> Rental Accommodation.");
            } else {
                $this->warn("No listings found or updated for State: {$state} | City: {$city} on iProperty at this time.");
            }
        } else {
            if ($result > 0) {
                $this->info("SUCCESS! Scraped and imported {$result} listings.");
                $this->info("Check your Admin Panel -> Rental Accommodation.");
            } elseif ($result === 0) {
                $this->warn("No listings found for State: {$state} | City: {$city} on iProperty at this time.");
            } else {
                $this->error("Failed to scrape iProperty. Check logs or standard error output.");
            }
        }
    }
}
