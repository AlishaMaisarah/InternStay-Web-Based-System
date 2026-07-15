<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Internship;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class ReScrapeDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internships:re-scrape-descriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-scrape and clean job descriptions for all existing internships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $python = base_path('.venv\\Scripts\\python.exe');
        if (!file_exists($python)) {
            $python = 'C:\\Python313\\python.exe'; // fallback
            if (!file_exists($python)) {
                $python = 'python'; // global
            }
        }
        $script = base_path('scripts\\clean_and_scrape.py');

        if (!file_exists($script)) {
            $this->error("Python script not found at: {$script}");
            return 1;
        }

        $internships = Internship::all();
        $total = $internships->count();
        $this->info("Found {$total} internships to process.");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;
        $cleanedOnly = 0;

        foreach ($internships as $internship) {
            $url = $internship->source_url;
            $scraped = false;

            // 1. Try to scrape the live URL first (only if url exists and is valid)
            if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                $process = new Process([$python, $script, '--url', $url]);
                $process->setTimeout(30);
                $process->run();

                if ($process->isSuccessful()) {
                    $res = json_decode($process->getOutput(), true);
                    if ($res && isset($res['status']) && $res['status'] === 'success' && !empty($res['description'])) {
                        $internship->description = $res['description'];
                        $internship->save();
                        $scraped = true;
                        $updated++;
                    }
                }
            }

            // 2. Fallback: If scraping failed or url wasn't valid, clean the existing description in the DB
            if (!$scraped) {
                $existingText = $internship->description ?: '';
                if ($existingText !== '' && $existingText !== '-') {
                    $process = new Process([$python, $script, '--text', $existingText]);
                    $process->setTimeout(15);
                    $process->run();

                    if ($process->isSuccessful()) {
                        $res = json_decode($process->getOutput(), true);
                        if ($res && isset($res['status']) && $res['status'] === 'success' && !empty($res['description'])) {
                            $internship->description = $res['description'];
                            $internship->save();
                            $cleanedOnly++;
                        }
                    }
                }
            }

            $bar->advance();
            
            // Short pause to avoid rate limiting
            usleep(200000); // 0.2 seconds
        }

        $bar->finish();
        $this->newLine();
        $this->info("Completed! Re-scraped live descriptions for {$updated} listings, and cleaned existing descriptions for {$cleanedOnly} listings.");
        
        return 0;
    }
}
