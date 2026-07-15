<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;
use App\Models\Internship;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckListingsAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listings:check-availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically check scraped source URLs and mark closed, occupied, or unavailable listings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting automatic listings availability check...");

        // 1. Process Rentals
        $activeRentals = Rental::where('is_closed', 0)
            ->where('is_available', 1)
            ->get();

        $this->info("Checking {$activeRentals->count()} active rentals...");
        $rentalsClosedCount = 0;

        foreach ($activeRentals as $rental) {
            if ($this->shouldSkipUrl($rental->source_url)) {
                continue;
            }

            if ($this->isListingClosed($rental->source_url)) {
                $rental->update([
                    'is_closed' => true,
                    'is_available' => false
                ]);
                $rentalsClosedCount++;
                $this->warn("Marked Rental closed: {$rental->property_name}");
                Log::info("Automated listings check: Marked Rental ID {$rental->id} as closed due to expired source URL.");
            }
        }

        // 2. Process Internships
        $activeInternships = Internship::where('is_closed', 0)->get();

        $this->info("Checking {$activeInternships->count()} active internships...");
        $internshipsClosedCount = 0;

        foreach ($activeInternships as $internship) {
            if ($this->shouldSkipUrl($internship->source_url)) {
                continue;
            }

            if ($this->isListingClosed($internship->source_url)) {
                $internship->update([
                    'is_closed' => true
                ]);
                $internshipsClosedCount++;
                $this->warn("Marked Internship closed: {$internship->internship_name}");
                Log::info("Automated listings check: Marked Internship ID {$internship->id} as closed due to expired source URL.");
            }
        }

        $this->info("Availability check completed!");
        $this->info("Rentals marked closed: {$rentalsClosedCount}");
        $this->info("Internships marked closed: {$internshipsClosedCount}");
    }

    /**
     * Check if the URL belongs to a local environment or is a mock URL.
     */
    private function shouldSkipUrl(?string $url): bool
    {
        if (empty($url)) {
            return true;
        }

        $urlLower = strtolower($url);

        // Skip internal/mock/manual urls
        if (
            str_contains($urlLower, '127.0.0.1') || 
            str_contains($urlLower, 'localhost') || 
            str_contains($urlLower, 'mock') || 
            str_contains($urlLower, 'unknown') ||
            filter_var($url, FILTER_VALIDATE_URL) === false
        ) {
            return true;
        }

        return false;
    }

    /**
     * Fetch the URL and check if the listing is closed/expired.
     */
    private function isListingClosed(string $url): bool
    {
        try {
            // Send request with a standard user-agent so we are not blocked
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->timeout(8)->get($url);

            // If 404, it is definitely closed
            if ($response->status() === 404) {
                return true;
            }

            // Check redirect status
            if ($response->redirect()) {
                // If it redirects to a generic error/home/listing-expired page
                $redirectUrl = $response->header('Location');
                if ($redirectUrl && (str_contains($redirectUrl, 'expired') || str_contains($redirectUrl, 'not-found') || str_contains($redirectUrl, 'error'))) {
                    return true;
                }
            }

            $html = $response->body();

            // 1. Extract and check page title
            $title = 'Unknown';
            $headPart = '';
            $bodyPos = strpos($html, '<body');
            if ($bodyPos !== false) {
                $headPart = substr($html, 0, $bodyPos);
            } else {
                $headPart = $html;
            }

            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $headPart, $matches)) {
                $title = trim($matches[1]);
            } else {
                if (preg_match_all('/<title[^>]*>(.*?)<\/title>/is', $html, $allMatches)) {
                    $title = trim(end($allMatches[1]));
                }
            }

            $titleLower = strtolower(html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($titleLower === 'page not found' || $titleLower === 'not found' || $titleLower === '404 not found') {
                return true;
            }

            // 2. Clean HTML content to avoid matching attributes, JS data, or styles
            $cleanHtml = preg_replace([
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/<style\b[^>]*>(.*?)<\/style>/is',
            ], '', $html);

            $text = strip_tags($cleanHtml);
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $textLower = strtolower($text);

            // Target phrases that websites use for expired/closed listings
            $closedPhrases = [
                'listing is no longer active',
                'no longer available',
                'this listing has expired',
                'position closed',
                'listing has been removed',
                'page not found',
                'occupied',
                'closed',
                'expired',
                'job is no longer accepting applications',
                'this job application is closed',
                'no longer accepting applications',
                'property is rented',
                'already rented',
            ];

            foreach ($closedPhrases as $phrase) {
                $pattern = '/\b' . preg_quote($phrase, '/') . '\b/i';
                if (preg_match($pattern, $textLower)) {
                    return true;
                }
                // Also check the page title against the phrases using word boundaries
                if (preg_match($pattern, $titleLower)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            // Network errors or timeout: do not mark as closed to avoid false positives
            return false;
        }
    }
}
