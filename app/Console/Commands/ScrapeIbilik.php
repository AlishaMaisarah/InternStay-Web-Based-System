<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PropertyScraperService;

class ScrapeIbilik extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:ibilik {state}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape rental listings from iBilik for a given state';

    /**
     * Execute the console command.
     */
    public function handle(PropertyScraperService $scraper)
    {
        $state = $this->argument('state');
        $this->info("Starting scraper for {$state} via CLI...");
        
        // Use 'ibilik' source. City is ignored by ibilik scraper but required by method signature.
        $result = $scraper->scrape($state, 'Any', 10, 'ibilik');
        
        if (is_array($result)) {
            $new = $result['new'];
            $updated = $result['updated'];
            $total = $result['total'];
            if ($new > 0 || $updated > 0) {
                $this->info("SUCCESS! Scraped {$total} listings ({$new} new, {$updated} updated).");
                $this->info("Check your Admin Panel -> Rental Accommodation.");
            } else {
                $this->warn("No listings found or updated for '{$state}' at this time.");
            }
        } else {
            if ($result > 0) {
                $this->info("SUCCESS! Scraped and imported {$result} listings.");
                $this->info("Check your Admin Panel -> Rental Accommodation.");
            } elseif ($result === 0) {
                $this->warn("No listings found for '{$state}' at this time.");
                $this->info("Verified correct URL slug generated for hierarchical search.");
            } else {
                $this->error("Failed to scrape. Check if a browser window opened (if visible mode) or check logs.");
            }
        }

    }
}
