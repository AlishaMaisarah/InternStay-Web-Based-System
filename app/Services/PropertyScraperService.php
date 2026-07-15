<?php

namespace App\Services;

use App\Models\Rental;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class PropertyScraperService
{
    public function scrape(string $state, string $city, int $limit = 10, string $source = 'propertyguru'): array|int
    {
        // Use the .venv python consistently for all scripts
        $pythonPath = base_path('.venv/Scripts/python.exe');
        if (!file_exists($pythonPath)) {
            // Fallback
             $pythonPath = 'C:\Python313\python.exe';
        }

        $scriptPath = base_path('scripts/property_scraper.py');
        
        Log::info("Running scraper for {$city}, {$state} on {$source} using venv: {$pythonPath}");
        
        // Pass essential environment variables for Windows/Selenium
        $env = array_merge($_ENV, $_SERVER);
        
        $pathVal = getenv('PATH') ?: getenv('Path') ?: '';
        if ($pathVal !== '') {
            $env['PATH'] = $pathVal;
            $env['Path'] = $pathVal;
        }
        if (!isset($env['LOCALAPPDATA'])) $env['LOCALAPPDATA'] = getenv('LOCALAPPDATA') ?: 'C:\Users\HP\AppData\Local';
        if (!isset($env['APPDATA'])) $env['APPDATA'] = getenv('APPDATA') ?: 'C:\Users\HP\AppData\Roaming';
        if (!isset($env['USERPROFILE'])) $env['USERPROFILE'] = getenv('USERPROFILE') ?: 'C:\Users\HP';
        if (!isset($env['SYSTEMROOT'])) $env['SYSTEMROOT'] = getenv('SYSTEMROOT') ?: 'C:\Windows';
        if (!isset($env['PROGRAMFILES'])) $env['PROGRAMFILES'] = getenv('PROGRAMFILES') ?: 'C:\Program Files';
        if (!isset($env['PROGRAMFILES(X86)'])) $env['PROGRAMFILES(X86)'] = getenv('PROGRAMFILES(X86)') ?: 'C:\Program Files (x86)';
        
        // Add user site-packages to PYTHONPATH
        $env['PYTHONPATH'] = 'C:\Users\HP\AppData\Roaming\Python\Python313\site-packages';

        if ($source === 'ibilik') {
            $scriptPath = base_path('scripts/ibilik_scraper.py');
            // Use the .venv python where packages are installed
            $pythonExe = base_path('.venv/Scripts/python.exe');
            if (!file_exists($pythonExe)) {
                // Fallback to global python if venv not found
                $pythonExe = 'C:\Python313\python.exe';
            }
            
            // iBilik scraper only takes state-slug and limit
            // iBilik scraper handles state/city hierarchy with slashes
            $stateSlug = strtolower(str_replace([', ', ',', ' '], ['/', '/', '-'], trim($state)));
            
            $command = [
                $pythonExe, 
                $scriptPath, 
                $stateSlug, 
                (string)$limit
            ];
        } else {
            $command = [
                $pythonPath, 
                $scriptPath, 
                '--state', $state, 
                '--city', $city, 
                '--limit', $limit,
                '--source', $source
            ];
        }

        $result = Process::env($env)->run($command);

        if (strlen($result->errorOutput()) > 0) {
            Log::debug("Scraper Stderr: " . $result->errorOutput());
        }

        if ($result->failed()) {
            Log::error("Scraper failed (Exit Code: {$result->exitCode()}): " . $result->errorOutput());
            return -1;
        }

        Log::info("Scraper output length: " . strlen($result->output()));

        $jsonOutput = $result->output();
        $decoded = json_decode($jsonOutput, true);
        
        if ($decoded === null) {
            Log::warning("Invalid JSON returned from scraper.");
            return -1;
        }

        // Handle iBilik's nested JSON structure
        if ($source === 'ibilik') {
            if (isset($decoded['error']) && $decoded['error'] !== null) {
                Log::error("iBilik Scraper Error: " . $decoded['error']);
                return -1;
            }
            $listings = $decoded['items'] ?? [];
        } else {
            $listings = $decoded;
        }
        
        if (empty($listings)) {
            Log::warning("No listings found on {$source}.");
            return 0;
        }

        $new = 0;
        $updated = 0;
        foreach ($listings as $data) {
            // Parse rent amount
            if (isset($data['price'])) {
                // iBilik format: "669" (already cleaned)
                $rentAmount = (float) $data['price'];
            } else {
                // Existing format: "RM 2,500 /mo"
                preg_match('/[\d,]+/', $data['rent_amount'] ?? '', $matches);
                $rentAmount = isset($matches[0]) ? (float) str_replace(',', '', $matches[0]) : 0;
            }

            // Determine property type
            if (isset($data['property_type']) && $data['property_type'] && $data['property_type'] !== 'Room') {
                $type = $data['property_type'];
            } else {
                $desc = strtolower(($data['description'] ?? '') . ' ' . ($data['property_name'] ?? ''));
                $type = 'Apartment';
                if (str_contains($desc, 'house') || str_contains($desc, 'terrace') || str_contains($desc, 'bungalow')) {
                    $type = 'House';
                } elseif (str_contains($desc, 'condo') || str_contains($desc, 'serviced')) {
                    $type = 'Condominium';
                } elseif (str_contains($desc, 'studio')) {
                    $type = 'Studio';
                } elseif (str_contains($desc, 'room') || (isset($data['property_type']) && $data['property_type'] === 'Room')) {
                    $isShared = false;
                    $sharingKeywords = ['share', 'sharing', 'shared', 'twin', 'roommate', 'co-living', 'coliving', 'buddy', 'room-sharing', '2 pax', 'two pax', 'triple'];
                    foreach ($sharingKeywords as $keyword) {
                        if (str_contains($desc, $keyword)) {
                            $isShared = true;
                            break;
                        }
                    }
                    $type = $isShared ? 'Shared Room' : 'Single Room';
                }
            }

            // Ensure address contains the state for better geocoding
            $addr = $data['address'] ?? ($data['location'] ?? 'Unknown Address');
            $desc = $data['description'] ?? '';
            
            // Fix: If scraper put the description into the address field (common for iBilik)
            if (strlen($addr) > 100) {
                $desc = $addr . "\n\n" . $desc; // Move long text to description
                $addr = ucfirst($state);   // Reset address to just the State
            }

            $targetState = ucfirst($state);
            if (!str_contains(strtolower($addr), strtolower($targetState))) {
                $addr .= ", " . $targetState;
            }

            $imageUrl = $data['image_url'] ?? null;
            if ($imageUrl && (str_contains(strtolower($imageUrl), 'image-fallback') || str_contains(strtolower($imageUrl), 'fallback') || str_contains(strtolower($imageUrl), 'placeholder'))) {
                $imageUrl = null;
            }

            $model = Rental::updateOrCreate(
                ['source_url' => $data['source_url'] ?? 'unknown-' . uniqid()],
                [
                    'property_name' => $data['property_name'] ?? 'Unknown Property',
                    'address' => $addr,
                    'rent_amount' => $rentAmount,
                    'currency' => 'MYR',
                    'bedrooms' => $data['bedrooms'] ?? ($data['beds'] ?? null),
                    'bathrooms' => $data['bathrooms'] ?? ($data['baths'] ?? null),
                    'description' => $desc,
                    'source' => $source === 'ibilik' ? 'iBilik' : ($data['source'] ?? $source),
                    'image_url' => $imageUrl,
                    'is_available' => true,
                    'property_type' => $type,
                ]
            );

            if ($model->wasRecentlyCreated) {
                $new++;
            } else {
                $updated++;
            }
        }

        return [
            'new' => $new,
            'updated' => $updated,
            'total' => count($listings),
        ];
    }
}
