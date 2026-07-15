<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunPipeline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-pipeline {--quick : Only scrape 1 result per source for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the full automation pipeline (Scrape -> Geocode -> Notify)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('quick') ? 1 : 10;
        $this->info("🚀 Starting Master Automation Pipeline (Limit: {$limit})...");

        // 1. Scrape Rentals (iBilik)
        $this->info("\n--- Step 1: Scraping iBilik ---");
        $states = ['melaka', 'selangor', 'negeri-sembilan', 'kuala lumpur', 'johor', 'pahang', 'perak', 'penang', 'terengganu', 'sabah', 'sarawak', 'kedah', 'kelantan', 'perlis'];
        foreach ($states as $state) {
            $this->call('scrape:ibilik', ['state' => $state]);
        }

        // 2. Scrape Rentals (PropertyGuru)
        $this->info("\n--- Step 2: Scraping PropertyGuru ---");
        $pgStates = [
            ['state' => 'melaka', 'city' => 'jasin'],
            ['state' => 'negeri sembilan', 'city' => 'seremban'],
            ['state' => 'negeri sembilan', 'city' => 'port dickson'],
            ['state' => 'negeri sembilan', 'city' => 'nilai'],
            ['state' => 'selangor', 'city' => 'shah alam'],
            ['state' => 'selangor', 'city' => 'petaling jaya'],
            ['state' => 'selangor', 'city' => 'subang jaya'],
            ['state' => 'selangor', 'city' => 'bangi'],
            ['state' => 'selangor', 'city' => 'cyberjaya'],
            ['state' => 'selangor', 'city' => 'puchong'],
            ['state' => 'selangor', 'city' => 'klang'],
            ['state' => 'kuala lumpur', 'city' => 'cheras'],
            ['state' => 'kuala lumpur', 'city' => 'kuala lumpur'],
            ['state' => 'johor', 'city' => 'pasir gudang'],
            ['state' => 'johor', 'city' => 'muar'],
            ['state' => 'johor', 'city' => 'kluang'],
            ['state' => 'johor', 'city' => 'johor bahru'],
            ['state' => 'kedah', 'city' => 'alor setar'],
            ['state' => 'kedah', 'city' => 'sungai petani'],
            ['state' => 'kelantan', 'city' => 'kota bharu'],
            ['state' => 'kelantan', 'city' => 'tumpat'],
            ['state' => 'kelantan', 'city' => 'pasir mas'],
            ['state' => 'pahang', 'city' => 'kuantan'],
            ['state' => 'perak', 'city' => 'ipoh'],
            ['state' => 'perlis', 'city' => 'arau'],
            ['state' => 'penang', 'city' => 'bayan lepas'],
            ['state' => 'penang', 'city' => 'butterworth'],
            ['state' => 'penang', 'city' => 'seberang jaya'],
            ['state' => 'sabah', 'city' => 'kota kinabalu'],
            ['state' => 'sarawak', 'city' => 'kuching'],
            ['state' => 'terengganu', 'city' => 'dungun'],
            ['state' => 'terengganu', 'city' => 'kemaman'],
        ];
        foreach ($pgStates as $pg) {
            $this->call('scrape:propertyguru', [
                'state' => $pg['state'],
                'city' => $pg['city']
            ]);
        }

        // 3. Scrape Rentals (iProperty)
        $this->info("\n--- Step 2: Scraping iProperty ---");
        $pgStates = [
            ['state' => 'melaka', 'city' => 'bandaraya melaka'],
            ['state' => 'negeri sembilan', 'city' => 'senawang'],
            ['state' => 'selangor', 'city' => 'shah alam'],
            ['state' => 'selangor', 'city' => 'petaling jaya'],
            ['state' => 'johor', 'city' => 'pasir gudang'],
            ['state' => 'johor', 'city' => 'muar'],
            ['state' => 'kedah', 'city' => 'alor setar'],
            ['state' => 'kedah', 'city' => 'kulim'],
            ['state' => 'kelantan', 'city' => 'kota bharu'],
            ['state' => 'kelantan', 'city' => 'pasir mas'],
            ['state' => 'pahang', 'city' => 'kuantan'],
            ['state' => 'perak', 'city' => 'ipoh'],
            ['state' => 'penang', 'city' => 'bayan lepas'],
            ['state' => 'penang', 'city' => 'penang'],
            ['state' => 'sabah', 'city' => 'kota kinabalu'],
            ['state' => 'sarawak', 'city' => 'miri'],
            ['state' => 'terengganu', 'city' => 'dungun'],
        ];
        foreach ($pgStates as $pg) {
            $this->call('scrape:propertyguru', [
                'state' => $pg['state'],
                'city' => $pg['city']
            ]);
        }


        // 4. Scrape Internships
        $this->info("\n--- Step 4: Scraping Internships ---");
        $categories = ['information-technology', 'engineering', 'business', 'healthcare', 'finance', 'marketing', 'education', 'design', 'sales', 'customer-service', 'it', 'design', 'architecture', 'quantity surveying'];
        foreach ($categories as $cat) {
            $this->call('internships:scrape', [
                'source' => 'hiredly',
                'category' => $cat,
                'limit' => $limit
            ]);
        }

        // 5. Scrape Internships
        $this->info("\n--- Step 5: Scraping Internships ---");
        $categories = ['information-technology', 'engineering', 'business', 'healthcare', 'finance', 'marketing', 'education', 'design', 'sales', 'customer-service', 'it', 'design', 'architecture', 'quantity surveying'];
        foreach ($categories as $cat) {
            $this->call('internships:scrape', [
                'source' => 'jobsora',
                'category' => $cat,
                'limit' => $limit
            ]);
        }

        // 6. Scrape Internships
        $this->info("\n--- Step 6: Scraping Internships ---");
        $categories = ['information-technology', 'engineering', 'business', 'healthcare', 'finance', 'marketing', 'education', 'design', 'sales', 'customer-service', 'it', 'design', 'architecture', 'quantity surveying'];
        foreach ($categories as $cat) {
            $this->call('internships:scrape', [
                'source' => 'linkedin',
                'category' => $cat,
                'limit' => $limit
            ]);
        }

        // 7. Geocode Missing Data
        $this->info("\n--- Step 7: Geocoding Missing Coordinates ---");
        $this->call('data:geocode');

        // 8. Send Notifications
        $this->info("\n--- Step 8: Sending Daily Notifications ---");
        $this->call('notifications:send-daily-digest', ['--force' => true]);

        $this->info("\n✅ Master Pipeline finished successfully!");
    }
}
