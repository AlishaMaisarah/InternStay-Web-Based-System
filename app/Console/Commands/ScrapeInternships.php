<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HiredlyScraper;

class ScrapeInternships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internships:scrape {source=all} {category=information-technology} {limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape internship listings live';

    /**
     * Execute the console command.
     */
    public function handle(HiredlyScraper $scraper)
    {
        $source = $this->argument('source');
        $category = $this->argument('category');
        $limit = (int) $this->argument('limit');

        $sources = ($source === 'all') 
            ? ['hiredly', 'jobsora', 'linkedin']
            : [$source];

        $this->info("Starting scraper pipeline for sources: " . implode(', ', $sources) . " | Category: {$category} | Limit: {$limit}...");

        foreach ($sources as $src) {
            $this->info("Running {$src} scraper...");
            try {
                $result = $scraper->scrape($src, $category, $limit);

                if (is_array($result)) {
                    $new = $result['new'];
                    $updated = $result['updated'];
                    $total = $result['total'];
                    if ($new > 0 || $updated > 0) {
                        $this->info("SUCCESS! [{$src}] Scraped {$total} internships ({$new} new, {$updated} updated).");
                    } else {
                        $this->warn("SUCCESS! [{$src}] Scrape finished but no new or updated internships were saved.");
                    }
                } else {
                    if ($result > 0) {
                        $this->info("SUCCESS! [{$src}] Scraped and imported {$result} internships.");
                    } else {
                        $this->warn("SUCCESS! [{$src}] Scrape finished but no new internships were saved.");
                    }
                }
            } catch (\Exception $e) {
                $this->error("ERROR! [{$src}] Scrape failed: " . $e->getMessage());
            }
        }
    }
}
