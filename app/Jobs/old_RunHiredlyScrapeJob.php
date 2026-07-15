<?php

namespace App\Jobs;

use App\Services\HiredlyScraper;
use App\Services\MockInternshipScraper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunHiredlyScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $categorySlug = 'information-technology'
    ) {}

    /**
     * THIS is where your code goes
     */
    public function handle(): void
    {
        Log::info('RunHiredlyScrapeJob started', [
            'category' => $this->categorySlug
        ]);

        // 1) Try REAL external source (Hiredly)
        $inserted = app(HiredlyScraper::class)
            ->scrape($this->categorySlug, 10);

        // 2) If blocked / JS-rendered -> fallback to mock (NO HTTP call)
        if ($inserted === 0) {
            Log::warning('Hiredly inserted 0. Falling back to mock source.');

            $inserted = app(MockInternshipScraper::class)->scrape();
        }

        Log::info('RunHiredlyScrapeJob finished', [
            'inserted' => $inserted
        ]);
    }

}
