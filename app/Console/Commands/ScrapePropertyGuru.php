<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PropertyScraperService;

class ScrapePropertyGuru extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:propertyguru {state} {city?}';

    protected $description = 'Scrape rental listings from PropertyGuru live (no manual HTML)';

    public function handle(PropertyScraperService $scraper)
    {
        $state = $this->argument('state');
        // Default city to state name if not provided (e.g. Scrape whole state)
        $city = $this->argument('city') ?: ' '; 

        $this->info("Starting live PropertyGuru scraper for State: {$state} | City: {$city}...");
        
        // Call service with 'propertyguru' source
        $result = $scraper->scrape($state, $city, 10, 'propertyguru');
        
        if (is_array($result)) {
            $new = $result['new'];
            $updated = $result['updated'];
            $total = $result['total'];
            if ($new > 0 || $updated > 0) {
                $this->info("SUCCESS! Scraped {$total} listings ({$new} new, {$updated} updated).");
                $this->info("Check your Admin Panel.");
            } else {
                $this->warn("No listings found or updated for State: {$state} | City: {$city} on PropertyGuru at this time.");
            }
        } else {
            if ($result > 0) {
                $this->info("SUCCESS! Scraped and imported {$result} listings.");
                $this->info("Check your Admin Panel.");
            } elseif ($result === 0) {
                $this->warn("No listings found for State: {$state} | City: {$city} on PropertyGuru at this time.");
            } else {
                $this->error("Failed to scrape. PropertyGuru has strong anti-bot. Check the open browser window or logs.");
            }
        }
    }
}
