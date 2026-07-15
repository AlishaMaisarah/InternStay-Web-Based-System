<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Internship;
use App\Models\Rental;
use App\Services\UserPreferenceService;

class UserPreferenceController extends Controller
{
    public function edit(UserPreferenceService $preferenceService)
    {
        $user = auth()->user();
        
        // Auto-analyze preferences from favorites if preferences don't exist yet
        if (!$user->preferences) {
            $preferenceService->analyzeAndUpdatePreferences($user);
            $user->load('preferences');
        }
        
        $preferences = $user->preferences ?? new UserPreference([
            'notification_frequency' => 'daily',
            'notify_internships' => true,
            'notify_rentals' => true,
        ]);
        
        // Get available options for dropdowns
        $industries = Internship::select('industry')
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry');
        
        $states = collect([
            'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Melaka',
            'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis',
            'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
        ])->sort()->values();
        
        $propertyTypes = Rental::select('property_type')
            ->whereNotNull('property_type')
            ->where('property_type', '!=', '')
            ->distinct()
            ->orderBy('property_type')
            ->pluck('property_type');
        
        return view('onboarding.step1', compact('preferences', 'industries', 'states', 'propertyTypes'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'notification_frequency' => 'required|in:instant,daily,weekly,off',
            'preferred_industries' => 'nullable|array',
            'preferred_internship_locations' => 'nullable|array',
            'preferred_property_types' => 'nullable|array',
            'preferred_rental_states' => 'nullable|array',
            'max_rental_price' => 'nullable|numeric|min:0',
        ]);
        
        // Handle checkboxes (they won't be in request if unchecked)
        $validated['notify_internships'] = $request->has('notify_internships');
        $validated['notify_rentals'] = $request->has('notify_rentals');

        // Ensure array fields are set to empty arrays if not present in request (meaning user deselected all)
        $validated['preferred_industries'] = $request->input('preferred_industries', []);
        $validated['preferred_internship_locations'] = $request->input('preferred_internship_locations', []);
        $validated['preferred_property_types'] = $request->input('preferred_property_types', []);
        $validated['preferred_rental_states'] = $request->input('preferred_rental_states', []);
        
        auth()->user()->preferences()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );
        
        return back()->with('success', 'Notification preferences updated successfully!');
    }
}
