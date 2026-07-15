<?php

namespace App\Services;

use App\Models\Internship;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class HiredlyScraper
{
    public function scrape(string $source, string $categorySlug = 'information-technology', int $limit = 15): array
    {
        $python = base_path('.venv\\Scripts\\python.exe');
        
        $scriptName = match ($source) {
            'jobsora' => 'scripts\\jobsora_scraper.py',
            'linkedin' => 'scripts\\linkedin_scraper.py',
            default => 'scripts\\hiredly_selenium.py',
        };
        $script = base_path($scriptName);

        if (!file_exists($python)) {
            throw new \Exception("Python venv not found: {$python}");
        }

        if (!file_exists($script)) {
            throw new \Exception("Scraper script not found: {$script}");
        }

        Log::info("[{$source}] python + script", ['python' => $python, 'script' => $script]);

        $process = new Process([$python, $script, $categorySlug, (string)$limit], base_path());
        $process->setTimeout(180);
        $process->run();

        if (!$process->isSuccessful()) {
            $err = trim($process->getErrorOutput());
            $out = trim($process->getOutput());
            throw new \Exception(
                "Python scraper failed.\nSTDERR:\n{$err}\n\nSTDOUT:\n{$out}"
            );
        }

        $raw = trim($process->getOutput());
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            throw new \Exception("Invalid JSON from scraper. Output: " . mb_substr($raw, 0, 500));
        }

        if (!empty($data['error'])) {
            throw new \Exception("Scraper error: " . $data['error']);
        }

        $items = $data['items'] ?? [];
        if (!is_array($items) || count($items) === 0) {
            return 0;
        }

        $saved = 0;
        $updated = 0;

        foreach ($items as $it) {
            // Unescape HTML entities (like &#x27; or &amp;) dynamically
            $title = html_entity_decode(trim((string)($it['title'] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if ($title === '') continue;

            $company = html_entity_decode(trim((string)($it['company'] ?? '-')) ?: '-', ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $location = html_entity_decode(trim((string)($it['location'] ?? '-')) ?: '-', ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $desc = html_entity_decode(trim((string)($it['description'] ?? '-')) ?: '-', ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $url = trim((string)($it['source_url'] ?? ''));
            
            // Always use the industry mapped from the user's search category to ensure consistency.
            // The internal scraper 'industry' guess (if any) is ignored in favor of the user's filter intent.
            $industry = $this->industryFromSlug($categorySlug);

            // Map frontend source value to DB Display value
            $sourceDisplay = match ($source) {
                'jobsora' => 'Jobsora',
                'linkedin' => 'LinkedIn',
                default => 'Hiredly'
            };

            $model = Internship::updateOrCreate(
                [
                    'internship_name' => $title,
                    'company' => $company,
                ],
                [
                    'industry' => $industry,
                    'location' => $location,
                    'source' => $sourceDisplay,
                    'source_url' => $url,
                    'description' => $desc,
                    //'contact_email' => '-',
                    //'contact_phone' => '-',
                ]
            );

            if ($model->wasRecentlyCreated) {
                $saved++;
            } else {
                $updated++;
            }
        }

        Log::info("[{$source}] saved", ['new' => $saved, 'updated' => $updated]);
        return [
            'new' => $saved,
            'updated' => $updated,
            'total' => count($items),
        ];
    }

    public function industryFromSlug(string $categorySlug): string
    {
        $slug = strtolower(trim($categorySlug));

        return match ($slug) {
            'information-technology', 'it' => 'IT/Information Technology',
            'engineering' => 'Engineering',
            'business', 'finance', 'marketing', 'sales', 'customer-service' => 'Business/Accounting/Finance',
            'healthcare' => 'Healthcare/Medical',
            'construction', 'architecture', 'quantity surveying', 'quantity-surveying' => 'Build/Architecture/Construction',
            'creative', 'design' => 'Creative/Design',
            'admin', 'human-resources', 'human resource', 'education' => 'Admin/Human Resource',
            default => match (true) {
                // Fuzzy matches in case other slugs are introduced
                stripos($slug, 'tech') !== false || $slug === 'it' => 'IT/Information Technology',
                stripos($slug, 'engineer') !== false => 'Engineering',
                stripos($slug, 'business') !== false || stripos($slug, 'finance') !== false || stripos($slug, 'account') !== false || stripos($slug, 'market') !== false || stripos($slug, 'sale') !== false => 'Business/Accounting/Finance',
                stripos($slug, 'health') !== false || stripos($slug, 'medic') !== false => 'Healthcare/Medical',
                stripos($slug, 'construct') !== false || stripos($slug, 'architect') !== false || stripos($slug, 'survey') !== false => 'Build/Architecture/Construction',
                stripos($slug, 'creative') !== false || stripos($slug, 'design') !== false => 'Creative/Design',
                stripos($slug, 'admin') !== false || stripos($slug, 'resource') !== false || stripos($slug, 'educat') !== false => 'Admin/Human Resource',
                default => 'Business/Accounting/Finance' // Fallback
            }
        };
    }
}

