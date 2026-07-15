<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\HiredlyScraper;

class InternshipScrapeController extends Controller
{
    public function scrape(Request $request, HiredlyScraper $scraper)
    {
        $category = (string) $request->input('category', 'information-technology');
        $limit    = (int) $request->input('limit', 10);

        $categoryMap = [
            'information-technology' => 'information-technology',
            'engineering'            => 'engineering',
            'business'              => 'business',
            'healthcare'            => 'healthcare',
            'construction'          => 'construction',
            'creative'              => 'creative',
            'admin'                 => 'admin',
        ];

        $slug = $categoryMap[$category] ?? $category;

        $sources = ['hiredly', 'jobsora', 'linkedin'];
        $totalNew = 0;
        $totalUpdated = 0;
        $details = [];
        $failedCount = 0;

        foreach ($sources as $source) {
            $sourceLabel = match ($source) {
                'hiredly' => 'Hiredly',
                'jobsora' => 'Jobsora',
                'linkedin' => 'LinkedIn',
                default => ucfirst($source),
            };

            try {
                // Dynamically reset the time limit to ensure sufficient execution time for each script
                if (function_exists('set_time_limit')) {
                    @set_time_limit(90);
                }

                $result = $scraper->scrape($source, $slug, $limit);
                if (is_array($result)) {
                    $totalNew += $result['new'];
                    $totalUpdated += $result['updated'];
                    $details[] = "{$sourceLabel} ({$result['new']} new, {$result['updated']} updated)";
                } else {
                    $totalNew += $result;
                    $details[] = "{$sourceLabel} ({$result})";
                }
            } catch (\Throwable $e) {
                Log::error("[Scraper] Multi-source scrape failed for: {$sourceLabel}", [
                    'category' => $category,
                    'slug' => $slug,
                    'error' => $e->getMessage(),
                ]);
                $failedCount++;
                $details[] = "{$sourceLabel} (failed)";
            }
        }

        if ($failedCount === count($sources)) {
            return back()->with('error', 'All scraper sources failed to execute. Please check system logs.');
        }

        $detailsStr = implode(', ', $details);
        $totalSaved = $totalNew + $totalUpdated;

        if ($totalSaved === 0) {
            return back()->with('success', "Scraping finished but no new internships were saved. Status: {$detailsStr}");
        }

        return back()->with('success', "Successfully scraped and imported {$totalSaved} internships ({$totalNew} new, {$totalUpdated} updated)! Details: {$detailsStr}");
    }
}
