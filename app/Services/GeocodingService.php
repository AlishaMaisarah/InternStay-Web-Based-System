<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Get coordinates (lat, lng) for a given address string.
     * Uses OpenStreetMap Nominatim API (Free).
     * 
     * @param string $address
     * @param string|null $propertyName Optional property name to use as fallback if address fails
     * @return array|null ['lat' => float, 'lng' => float] or null on failure
     */
    public function getCoordinates(string $address, ?string $propertyName = null): ?array
    {
        if (empty(trim($address))) {
            return null;
        }

        // Strategy 1: The full normalized address
        $coords = $this->attemptGeocode($this->normalizeAddress($address));
        if ($coords) return $coords;

        // Strategy 2: If we have a property name (e.g., building name), try that + city
        if ($propertyName) {
            $city = $this->extractCity($address);
            $buildingQuery = $propertyName . ($city ? ", $city" : "");
            $coords = $this->attemptGeocode($this->normalizeAddress($buildingQuery));
            if ($coords) return $coords;
        }

        // Strategy 3: Try stripping postal codes and extra punctuation
        $strippedAddress = preg_replace('/\d{5}/', '', $address); // Remove postal code
        $coords = $this->attemptGeocode($this->normalizeAddress($strippedAddress));
        if ($coords) return $coords;

        // Strategy 4: Fallback to just City, State (e.g., "Seremban, Negeri Sembilan")
        $city = $this->extractCity($address);
        if ($city) {
            $statePart = '';
            $parts = array_map('trim', explode(',', $address));
            if (count($parts) >= 1) {
                $last = strtolower($parts[count($parts) - 1]);
                if ($last === 'malaysia' && count($parts) >= 2) {
                    $statePart = $parts[count($parts) - 2];
                } else {
                    $statePart = $parts[count($parts) - 1];
                }
            }
            $fallbackQuery = $city . ($statePart ? ", $statePart" : "");
            $coords = $this->attemptGeocode($this->normalizeAddress($fallbackQuery));
            if ($coords) return $coords;
        }

        // Strategy 5: Premium Local Coordinate Database Fallback (No network, rate-limit proof)
        $coords = $this->getLocalFallbackCoordinates($address);
        if ($coords) {
            Log::info("Geocoding fell back to local registry for: {$address}");
            return $coords;
        }

        return null;
    }

    protected function getLocalFallbackCoordinates(string $address): ?array
    {
        $addressLower = strtolower($address);
        
        // Specific Cities/Towns first
        $registry = [
            'cyberjaya' => ['lat' => 2.9213, 'lng' => 101.6559],
            'putrajaya' => ['lat' => 2.9264, 'lng' => 101.6964],
            'petaling jaya' => ['lat' => 3.1073, 'lng' => 101.6067],
            'subang jaya' => ['lat' => 3.0792, 'lng' => 101.5830],
            'shah alam' => ['lat' => 3.0738, 'lng' => 101.5183],
            'klang' => ['lat' => 3.0449, 'lng' => 101.4451],
            'nilai' => ['lat' => 2.8169, 'lng' => 101.7956],
            'seremban' => ['lat' => 2.7258, 'lng' => 101.9424],
            'georgetown' => ['lat' => 5.4141, 'lng' => 100.3288],
            'johor bahru' => ['lat' => 1.4927, 'lng' => 103.7414],
            'ipoh' => ['lat' => 4.5975, 'lng' => 101.0901],
            'kuantan' => ['lat' => 3.8126, 'lng' => 103.3256],
            'kota bharu' => ['lat' => 6.1254, 'lng' => 102.2381],
            'kuala terengganu' => ['lat' => 5.3117, 'lng' => 103.1324],
            'alor setar' => ['lat' => 6.1210, 'lng' => 100.3601],
            'kangar' => ['lat' => 6.4449, 'lng' => 100.1983],
            'kota kinabalu' => ['lat' => 5.9804, 'lng' => 116.0735],
            'kuching' => ['lat' => 1.5533, 'lng' => 110.3592],
            'melaka' => ['lat' => 2.1896, 'lng' => 102.2501],
            'malacca' => ['lat' => 2.1896, 'lng' => 102.2501],
            'cheras' => ['lat' => 3.1007, 'lng' => 101.7371],
            'puchong' => ['lat' => 3.0236, 'lng' => 101.6190],
            'kepong' => ['lat' => 3.2204, 'lng' => 101.6396],
            
            // Broad States
            'kuala lumpur' => ['lat' => 3.1390, 'lng' => 101.6869],
            'selangor' => ['lat' => 3.0738, 'lng' => 101.5183],
            'penang' => ['lat' => 5.4141, 'lng' => 100.3288],
            'negeri sembilan' => ['lat' => 2.7258, 'lng' => 101.9424],
            'johor' => ['lat' => 1.4927, 'lng' => 103.7414],
            'perak' => ['lat' => 4.5975, 'lng' => 101.0901],
            'pahang' => ['lat' => 3.8126, 'lng' => 103.3256],
            'kelantan' => ['lat' => 6.1254, 'lng' => 102.2381],
            'terengganu' => ['lat' => 5.3117, 'lng' => 103.1324],
            'kedah' => ['lat' => 6.1210, 'lng' => 100.3601],
            'perlis' => ['lat' => 6.4449, 'lng' => 100.1983],
            'sabah' => ['lat' => 5.9804, 'lng' => 116.0735],
            'sarawak' => ['lat' => 1.5533, 'lng' => 110.3592]
        ];

        foreach ($registry as $key => $coords) {
            if (str_contains($addressLower, $key)) {
                return $coords;
            }
        }

        return null;
    }

    protected function attemptGeocode(string $query): ?array
    {
        try {
            // Respect OSM Nominatim strict rate limit of 1 request per second
            usleep(1000000);

            if (!str_contains(strtolower($query), 'malaysia')) {
                $query .= ', Malaysia';
            }

            $response = Http::withHeaders([
                'User-Agent' => 'InternStay-Compass/1.0 (Student Project)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'my',
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && isset($data[0])) {
                    return [
                        'lat' => (float) $data[0]['lat'],
                        'lng' => (float) $data[0]['lon'],
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Geocoding exception for query '{$query}': " . $e->getMessage());
        }

        return null;
    }

    protected function normalizeAddress(string $address): string
    {
        // Remove trailing dots, excessive spaces, and common noise
        $cleaned = preg_replace('/\.\s*$/', '', $address); // Trailing dot
        $cleaned = str_replace('.,', ',', $cleaned);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        // Clean up duplicate comma-separated parts (e.g. "Seremban, Seremban")
        $parts = array_map('trim', explode(',', $cleaned));
        $uniqueParts = [];
        foreach ($parts as $part) {
            if ($part !== '' && !in_array($part, $uniqueParts)) {
                $uniqueParts[] = $part;
            }
        }
        $cleaned = implode(', ', $uniqueParts);
        
        return trim($cleaned);
    }

    protected function extractCity(string $address): ?string
    {
        // Dynamic Extraction: Split address by commas and search backwards (excluding Malaysia and states)
        $parts = array_map('trim', explode(',', $address));
        $count = count($parts);
        if ($count >= 2) {
            $states = ['selangor', 'kuala lumpur', 'penang', 'johor', 'perak', 'kedah', 'negeri sembilan', 'pahang', 'kelantan', 'terengganu', 'perlis', 'sabah', 'sarawak', 'melaka', 'malacca'];
            for ($i = $count - 1; $i >= 0; $i--) {
                $part = $parts[$i];
                $lower = strtolower($part);
                if ($lower === 'malaysia' || in_array($lower, $states)) {
                    continue;
                }
                // Check if the part represents a likely city name
                if (preg_match('/^[A-Za-z\s\-]+$/', $part) && strlen($part) > 3) {
                    return $part;
                }
            }
        }

        // Fallback hardcoded list
        $commonCities = [
            'Kuala Lumpur', 'Petaling Jaya', 'Shah Alam', 'Subang Jaya', 'Kepong', 'Cheras', 'Puchong',
            'Seremban', 'Nilai', 'Melaka', 'Malacca', 'Johor Bahru', 'Ipoh', 'Georgetown', 'Penang',
            'Kuantan', 'Alor Setar', 'Kuala Terengganu', 'Kota Bharu', 'Kota Kinabalu', 'Kuching'
        ];
        foreach ($commonCities as $city) {
            if (stripos($address, $city) !== false) {
                return $city;
            }
        }
        return null;
    }
}
