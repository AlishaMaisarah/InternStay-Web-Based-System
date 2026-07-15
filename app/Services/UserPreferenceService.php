<?php

namespace App\Services;

use App\Models\User;
use App\Models\Internship;
use App\Models\Rental;
use App\Models\UserPreference;

class UserPreferenceService
{
    /**
     * Analyze user's favorites and search history to build/update preferences
     */
    public function analyzeAndUpdatePreferences(User $user): void
    {
        $preferences = $user->preferences ?? new UserPreference(['user_id' => $user->id]);
        
        // Analyze favorite internships
        $favoriteInternships = $user->favorites()
            ->where('favoritable_type', Internship::class)
            ->with('favoritable')
            ->get();
        
        if ($favoriteInternships->isNotEmpty()) {
            $industries = $favoriteInternships
                ->pluck('favoritable.industry')
                ->unique()
                ->filter()
                ->values();
            
            $locations = $favoriteInternships
                ->pluck('favoritable.location')
                ->unique()
                ->filter()
                ->values();
            
            $preferences->preferred_industries = $industries->toArray();
            $preferences->preferred_internship_locations = $locations->toArray();
        }
        
        // Analyze favorite rentals
        $favoriteRentals = $user->favorites()
            ->where('favoritable_type', Rental::class)
            ->with('favoritable')
            ->get();
        
        if ($favoriteRentals->isNotEmpty()) {
            $propertyTypes = $favoriteRentals
                ->pluck('favoritable.property_type')
                ->unique()
                ->filter()
                ->values();
            
            $states = $favoriteRentals
                ->pluck('favoritable.address')
                ->map(fn($addr) => $this->extractState($addr))
                ->unique()
                ->filter()
                ->values();
            
            $preferences->preferred_property_types = $propertyTypes->toArray();
            $preferences->preferred_rental_states = $states->toArray();
        }
        
        // Analyze search history (last 30 days)
        $recentSearches = $user->searchHistory()
            ->where('created_at', '>=', now()->subDays(30))
            ->get();
        
        // Extract common search terms and filters from recent searches
        if ($recentSearches->isNotEmpty()) {
            $internshipSearches = $recentSearches->where('search_type', 'internship');
            if ($internshipSearches->isNotEmpty()) {
                $searchedIndustries = $internshipSearches
                    ->pluck('filters.industry')
                    ->filter()
                    ->unique()
                    ->values();
                
                if ($searchedIndustries->isNotEmpty()) {
                    // Merge with existing preferences
                    $currentIndustries = collect($preferences->preferred_industries ?? []);
                    $preferences->preferred_industries = $currentIndustries
                        ->merge($searchedIndustries)
                        ->unique()
                        ->values()
                        ->toArray();
                }
            }
        }
        
        $preferences->save();
    }
    
    /**
     * Check if a listing matches user preferences
     */
    public function matchesPreferences(User $user, $listing): bool
    {
        $preferences = $user->preferences;
        if (!$preferences) return false;
        
        if ($listing instanceof Internship) {
            if (!$preferences->notify_internships) return false;
            
            // If no preferences set, match all
            if (empty($preferences->preferred_industries) && empty($preferences->preferred_internship_locations)) {
                return true;
            }
            
            $matchesIndustry = empty($preferences->preferred_industries) || 
                in_array($listing->industry, $preferences->preferred_industries);
            
            $matchesLocation = empty($preferences->preferred_internship_locations) ||
                $this->locationMatches($listing->location, $preferences->preferred_internship_locations);
            
            return $matchesIndustry && $matchesLocation;
        }
        
        if ($listing instanceof Rental) {
            if (!$preferences->notify_rentals) return false;
            
            // If no preferences set, match all
            if (empty($preferences->preferred_property_types) && 
                empty($preferences->preferred_rental_states) && 
                !$preferences->max_rental_price) {
                return true;
            }
            
            $matchesType = empty($preferences->preferred_property_types) ||
                in_array($listing->property_type, $preferences->preferred_property_types);
            
            $matchesPrice = !$preferences->max_rental_price ||
                $listing->rent_amount <= $preferences->max_rental_price;
            
            $matchesState = empty($preferences->preferred_rental_states) ||
                $this->stateMatches($listing->address, $preferences->preferred_rental_states);
            
            return $matchesType && $matchesPrice && $matchesState;
        }
        
        return false;
    }
    
    /**
     * Extract state from address string
     */
    private function extractState(string $address): ?string
    {
        $states = [
            'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Melaka', 'Malacca',
            'Negeri Sembilan', 'Pahang', 'Penang', 'Pulau Pinang', 'Perak', 
            'Perlis', 'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
        ];
        
        foreach ($states as $state) {
            if (stripos($address, $state) !== false) {
                return $state;
            }
        }
        
        return null;
    }
    
    /**
     * Check if location matches any of the preferred locations
     */
    private function locationMatches(string $location, array $preferredLocations): bool
    {
        foreach ($preferredLocations as $preferred) {
            if (stripos($location, $preferred) !== false || stripos($preferred, $location) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Check if address contains any of the preferred states
     */
    private function stateMatches(string $address, array $preferredStates): bool
    {
        foreach ($preferredStates as $state) {
            if (stripos($address, $state) !== false) {
                return true;
            }
        }
        return false;
    }
}
