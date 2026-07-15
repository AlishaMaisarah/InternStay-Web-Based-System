<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;

class PublicInternshipController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $state = trim((string) $request->get('state', ''));
        $industry = trim((string) $request->get('industry', ''));
        $sort = trim((string) $request->get('sort', 'highest_match'));

        $user = auth()->user();
        $preferences = $user ? $user->preferences : null;
        $hasPreferences = false;
        if ($preferences) {
            $hasPreferences = !empty($preferences->course_of_study) || !empty($preferences->preferred_industries);
        }
        $exploreMode = $request->get('explore') == '1';

        $states = collect([
            'Johor',
            'Kedah',
            'Kelantan',
            'Kuala Lumpur',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Penang',
            'Perak',
            'Perlis',
            'Putrajaya',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu',
        ])->sort()->values();

        // dropdown options (only industries that exist in DB)
        $industries = \App\Models\Internship::where('is_suspended', false)
            ->select('industry')
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');

        $favoriteInternshipIds = $user
            ? $user->favorites()->where('favoritable_type', Internship::class)->pluck('favoritable_id')->toArray() 
            : [];

        // Track search for logged-in users
        if ($user && ($search !== '' || $industry !== '' || $state !== '')) {
            \App\Models\UserSearchHistory::create([
                'user_id' => $user->id,
                'search_type' => 'internship',
                'search_query' => $search,
                'filters' => [
                    'industry' => $industry,
                    'state' => $state,
                ],
            ]);
        }

        $recommendedInternships = collect();
        $groupedRecommendations = collect();

        if ($hasPreferences && !$exploreMode) {
            // Personalization Mode: Dynamically filter by stored preferences
            $query = \App\Models\Internship::where('is_suspended', false);

            if ($search !== '') {
                $query->where(function ($sub) use ($search) {
                    $sub->where('internship_name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('industry', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            }

            if ($state !== '') {
                $query->where('location', 'like', "%{$state}%");
            } else {
                // Apply preferred internship locations if set
                $prefLocations = $preferences->preferred_internship_locations ?? [];
                if (!empty($prefLocations)) {
                    $query->where(function ($sub) use ($prefLocations) {
                        foreach ($prefLocations as $loc) {
                            $sub->orWhere('location', 'like', "%{$loc}%");
                        }
                    });
                }
            }

            // Apply preferred industries if set
            $prefIndustries = $preferences->preferred_industries ?? [];
            if (!empty($prefIndustries)) {
                $query->whereIn('industry', $prefIndustries);
            }

            $matchingInternships = $query->orderBy('is_closed', 'asc')
                ->orderByDesc('id')
                ->get();

            foreach ($matchingInternships as $internship) {
                $courseScore = 0;
                if (!empty($preferences->course_of_study)) {
                    $courseScore = $this->calculateCourseSimilarityScore($internship, $preferences->course_of_study);
                }

                $industryScore = 0;
                if (!empty($prefIndustries)) {
                    $prefIndustriesLower = array_map('strtolower', $prefIndustries);
                    if (in_array(strtolower($internship->industry), $prefIndustriesLower)) {
                        $industryScore = 100;
                    }
                }

                // Weighted score: 70% Course, 30% Industries
                if (!empty($preferences->course_of_study) && !empty($prefIndustries)) {
                    $score = round(($courseScore * 0.7) + ($industryScore * 0.3));
                } elseif (!empty($preferences->course_of_study)) {
                    $score = $courseScore;
                } else {
                    $score = $industryScore;
                }

                $internship->similarity_score = $score;

                // Threshold of 70% for recommendations
                if ($score >= 70) {
                    $recommendedInternships->push($internship);
                }
            }

            // Sorting
            if ($sort === 'latest') {
                $recommendedInternships = $recommendedInternships->sort(function ($a, $b) {
                    if ($a->is_closed !== $b->is_closed) {
                        return $a->is_closed ? 1 : -1;
                    }
                    return $b->id <=> $a->id;
                });
            } else {
                // Highest Match default sorting
                $recommendedInternships = $recommendedInternships->sort(function ($a, $b) {
                    if ($a->is_closed !== $b->is_closed) {
                        return $a->is_closed ? 1 : -1;
                    }
                    if ($b->similarity_score !== $a->similarity_score) {
                        return $b->similarity_score <=> $a->similarity_score;
                    }
                    return $b->id <=> $a->id;
                });

                // Group by match percentage (rounded to nearest 5%)
                $groupedRecommendations = $recommendedInternships->groupBy(function ($item) {
                    return round($item->similarity_score / 5) * 5;
                })->sortKeysDesc();
            }

            // In personalization mode, $internships will only represent these recommended internships
            // to satisfy: "Display only recommended internships."
            $internships = $recommendedInternships;

        } else {
            // Guest, Incomplete Preferences, or Explore Mode
            $query = \App\Models\Internship::where('is_suspended', false);

            if ($search !== '') {
                $query->where(function ($sub) use ($search) {
                    $sub->where('internship_name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('industry', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            }

            if ($industry !== '') {
                $query->where('industry', $industry);
            }

            if ($state !== '') {
                $query->where('location', 'like', "%{$state}%");
            }

            $internships = $query->orderBy('is_closed', 'asc')
                ->orderByDesc('id')
                ->get();

            // Original course-based recommendations banner logic (only up to 4 recommendations)
            if ($user && $preferences && !empty($preferences->course_of_study)) {
                $courseOfStudy = $preferences->course_of_study;
                foreach ($internships as $internship) {
                    $courseScore = $this->calculateCourseSimilarityScore($internship, $courseOfStudy);
                    $internship->similarity_score = $courseScore;
                    if ($courseScore >= 70) {
                        $recommendedInternships->push($internship);
                    }
                }

                $recommendedInternships = $recommendedInternships->sort(function ($a, $b) {
                    if ($a->is_closed !== $b->is_closed) {
                        return $a->is_closed ? 1 : -1;
                    }
                    return $b->similarity_score <=> $a->similarity_score;
                })->take(4);
            }
        }

        $courseOfStudy = $preferences ? $preferences->course_of_study : null;

        return view('public.internships.index', compact(
            'internships',
            'search',
            'industry',
            'state',
            'industries',
            'states',
            'favoriteInternshipIds',
            'recommendedInternships',
            'courseOfStudy',
            'hasPreferences',
            'exploreMode',
            'groupedRecommendations',
            'sort',
            'preferences'
        ));
    }


    public function show(Internship $internship, \App\Services\GeocodingService $geocodingService)
    {
        if ($internship->is_suspended) {
            abort(404);
        }

        $internship->load(['reviews.user']);

        // 1. Geocode on the fly if missing (useful for newly scraped data)
        if ($internship->lat === null || $internship->lng === null) {
            $coords = $geocodingService->getCoordinates($internship->location);
            if ($coords) {
                $internship->update($coords);
            }
        }

        $nearbyRentals = collect();

        if ($internship->lat && $internship->lng) {
            $lat = (float) $internship->lat;
            $lng = (float) $internship->lng;
            $radiusKm = 15; // Search within 15km

            $nearbyRentals = \App\Models\Rental::query()
                ->where('is_available', 1)
                ->whereNotNull('lat')
                ->whereNotNull('lng')
                ->select('rentals.*')
                ->selectRaw("
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(lat)) *
                        cos(radians(lng) - radians(?)) +
                        sin(radians(?)) * sin(radians(lat))
                    )) AS distance
                ", [$lat, $lng, $lat])
                ->having('distance', '<=', $radiusKm)
                ->orderBy('distance')
                ->limit(5)
                ->limit(5)
                ->get();
        }

        // Fallback: If no rentals found via coordinates (or internship has no coords), try text matching
        if ($nearbyRentals->isEmpty() && $internship->location) {
            $locationName = $internship->location;
            // Clean location name (e.g. remove "Malaysia", trim)
            $locationName = trim(str_replace('Malaysia', '', $locationName));
            
            if (!empty($locationName)) {
                $nearbyRentals = \App\Models\Rental::query()
                    ->where('is_available', 1)
                    ->where(function($q) use ($locationName) {
                        $q->where('address', 'LIKE', "%{$locationName}%")
                          ->orWhere('property_name', 'LIKE', "%{$locationName}%");
                    })
                    ->limit(5)
                    ->get();
            }
        }

        $favoriteInternshipIds = auth()->check() 
            ? auth()->user()->favorites()->where('favoritable_type', Internship::class)->pluck('favoritable_id')->toArray() 
            : [];

        return view('public.internships.show', compact('internship', 'nearbyRentals', 'favoriteInternshipIds'));
    }

    private function calculateCourseSimilarityScore($internship, $courseOfStudy)
    {
        if (empty($courseOfStudy)) {
            return 0;
        }

        $courseLower = strtolower(trim($courseOfStudy));
        
        // Extract terms, allowing 2-letter acronyms like "IT", "CS", "AI"
        $courseTerms = array_filter(array_map('trim', explode(' ', $courseLower)), function($term) {
            return strlen($term) >= 2; 
        });

        // Comprehensive Semantic Category Mappings
        $semanticMappings = [
            [
                'triggers' => ['computing','computer','software','information technology','netcentric','network','cyber','data','programming','computer science','tech','ai','it','cs'], 
                'targets' => ['web','developer','software','computer','network','programmer','tech','data','cyber','information technology','app', 'systems', 'ai']
            ],
            [
                'triggers' => ['business','finance','accounting','commerce','marketing','management','human resources','admin','economy','banking'], 
                'targets' => ['business','finance','accounting','marketing','sales','audit','admin','human resource', 'management', 'executive']
            ],
            [
                'triggers' => ['engineering','mechanical','electrical','chemical','mechatronics','robotics'], 
                'targets' => ['engineer','engineering','mechanical','electrical','chemical','manufacturing','technician']
            ],
            [
                'triggers' => ['quantity surveying','quantity surveyor','surveying','surveyor','construction','architecture','building','civil','estate management','property'], 
                'targets' => ['quantity surveyor','quantity surveyors','quantity surveying','surveyor','surveyors','surveying','construction','building','architecture','estimator','contract manager','civil']
            ],
            [
                'triggers' => ['design','graphic','multimedia','creative','art','animation','media','video'], 
                'targets' => ['design','designer','creative','art','graphic','multimedia','animation','ui','ux','media','video','editor']
            ],
        ];

        $nameLower = strtolower($internship->internship_name ?? '');
        $industryLower = strtolower($internship->industry ?? '');
        $descLower = strtolower($internship->description ?? '');

        $textToSearch = $nameLower . ' ' . $industryLower . ' ' . $descLower;
        
        // 1. Keyword Count Match (Stem-aware: e.g. "surveying" matches "surveyor" / "surveyors")
        $matchCount = 0;
        foreach ($courseTerms as $term) {
            $stem = (strlen($term) > 5) ? substr($term, 0, 5) : $term;
            if (preg_match('/\b' . preg_quote($term, '/') . '/i', $textToSearch) || preg_match('/\b' . preg_quote($stem, '/') . '/i', $textToSearch)) {
                $matchCount++;
            }
        }
        $keywordScore = count($courseTerms) > 0 ? round(($matchCount / count($courseTerms)) * 100) : 0;

        // 2. Fuzzy Match on Industry and Name
        similar_text($courseLower, $industryLower, $fuzzyIndustryScore);
        similar_text($courseLower, $nameLower, $fuzzyNameScore);

        // 3. Semantic Category Boost
        $semanticScore = 0;
        foreach ($semanticMappings as $mapping) {
            $courseMatchesCategory = false;
            foreach ($mapping['triggers'] as $trigger) {
                if (preg_match('/\b' . preg_quote($trigger, '/') . '\b/i', $courseLower) || str_contains($courseLower, $trigger)) {
                    $courseMatchesCategory = true;
                    break;
                }
            }

            if ($courseMatchesCategory) {
                $targetMatches = 0;
                foreach ($mapping['targets'] as $target) {
                    if (preg_match('/\b' . preg_quote($target, '/') . '\b/i', $nameLower) || 
                        preg_match('/\b' . preg_quote($target, '/') . '\b/i', $industryLower) ||
                        str_contains($nameLower, $target) || str_contains($industryLower, $target)) {
                        $targetMatches++;
                    }
                }
                if ($targetMatches > 0) {
                    $semanticScore = max($semanticScore, 80 + min(15, $targetMatches * 5));
                }
            }
        }
        
        $bestScore = max($keywordScore, $fuzzyIndustryScore, $fuzzyNameScore, $semanticScore);
        return min(100, round($bestScore));
    }
}

