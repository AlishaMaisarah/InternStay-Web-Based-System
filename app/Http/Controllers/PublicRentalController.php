<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class PublicRentalController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Read filters FIRST (before using them)
        $q = trim((string) $request->get('q', ''));
        $state = trim((string) $request->get('state', ''));   // ✅ FIX
        $property_type = trim((string) $request->get('property_type', ''));
        $max_price = $request->get('max_price');
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radiusKm = (int) $request->get('radius', 50);

        $query = Rental::query();

        // ✅ Text search
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('property_name', 'like', "%{$q}%")
                    ->orWhere('property_type', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%");
            });
        }

        // ✅ State dropdown filter (based on address text)
        if ($state !== '') {
            $query->where('address', 'like', "%{$state}%");
        }

        // ✅ Property type dropdown filter
        if ($property_type !== '') {
            $query->where('property_type', $property_type);
        }

        // ✅ Max Price filter
        if ($max_price !== null && $max_price !== '') {
            $query->where('rent_amount', '<=', (float) $max_price);
        }

        // ✅ Near-me (radius filter + order by distance, closed/occupied listings at the bottom)
        if ($lat !== null && $lng !== null && $lat !== '' && $lng !== '') {
            $lat = (float) $lat;
            $lng = (float) $lng;

            $query->whereNotNull('lat')
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
                ->orderBy('is_closed', 'asc')
                ->orderBy('is_available', 'desc')
                ->orderBy('distance');
        } else {
            $query->orderBy('is_closed', 'asc')
                ->orderBy('is_available', 'desc')
                ->orderByDesc('id');
        }

        $rentals = $query->get();

        // ✅ Complete list of all Malaysian states and federal territories
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

        // Get distinct property types
        $property_types = Rental::whereNotNull('property_type')
            ->where('property_type', '!=', '')
            ->distinct()
            ->orderBy('property_type')
            ->pluck('property_type');

        // Track search for logged-in users
        if (auth()->check() && ($q !== '' || $state !== '' || $property_type !== '')) {
            \App\Models\UserSearchHistory::create([
                'user_id' => auth()->id(),
                'search_type' => 'rental',
                'search_query' => $q,
                'filters' => [
                    'state' => $state,
                    'property_type' => $property_type,
                    'radius' => $radiusKm,
                ],
            ]);
        }

        // Fetch favorite IDs for the current user
        $favoriteRentalIds = auth()->check() 
            ? auth()->user()->favorites()->where('favoritable_type', Rental::class)->pluck('favoritable_id')->toArray() 
            : [];

        // ✅ Recommendation Logic based on Preferences (Strictly Budget-based)
        /*$recommendedRentals = collect();
        $preferences = auth()->check() ? auth()->user()->preferences : null;

        if ($preferences) {
            $maxBudget = (float) $preferences->max_rental_price;

            // Only compute recommendations if budget is set
            if ($maxBudget > 0) {
                foreach ($rentals as $rental) {
                    $rentAmount = (float) $rental->rent_amount;
                    if ($rentAmount <= $maxBudget) {
                        $score = 100;
                    } else {
                        $overPenalty = (($rentAmount - $maxBudget) / $maxBudget) * 100;
                        $score = max(0, 100 - $overPenalty);
                    }

                    $similarityPercentage = round($score);

                    if ($similarityPercentage >= 60) {
                        $rental->similarity_score = $similarityPercentage;
                        $recommendedRentals->push($rental);
                    }
                }

                // Sort recommendations: available first (not closed/occupied), then similarity_score descending
                $recommendedRentals = $recommendedRentals->sort(function ($a, $b) {
                    $aClosed = $a->is_closed || !$a->is_available;
                    $bClosed = $b->is_closed || !$b->is_available;
                    if ($aClosed !== $bClosed) {
                        return $aClosed ? 1 : -1;
                    }
                    return $b->similarity_score <=> $a->similarity_score;
                })->take(4);
            }
        }*/

        // ✅ Return view with everything used in Blade
        return view('public.rentals.index', compact(
            'rentals',
            'q',
            'state',
            'states',
            'property_type',
            'property_types',
            'max_price',
            'lat',
            'lng',
            'radiusKm',
            'favoriteRentalIds',
            /*'recommendedRentals'*/
        ));
    }

    // ✅ This fixes /accommodation/{rental}
    public function show(Rental $rental)
    {
        $rental->load(['reviews.user']);

        $favoriteRentalIds = auth()->check() 
            ? auth()->user()->favorites()->where('favoritable_type', Rental::class)->pluck('favoritable_id')->toArray() 
            : [];

        return view('public.rentals.show', compact('rental', 'favoriteRentalIds'));
    }
}
