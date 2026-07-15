<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(\App\Services\UserPreferenceService $preferenceService)
    {
        $user = Auth::user();

        // Auto-analyze preferences from favorites if preferences don't exist yet
        if (!$user->preferences) {
            $preferenceService->analyzeAndUpdatePreferences($user);
            $user->load('preferences');
        }
        
        $preferences = $user->preferences ?? new \App\Models\UserPreference([
            'notification_frequency' => 'daily',
            'notify_internships' => true,
            'notify_rentals' => true,
        ]);
        
        // Get available options for dropdowns
        $industries = collect([
            'IT/Information Technology',
            'Engineering',
            'Business/Accounting/Finance',
            'Healthcare/Medical',
            'Creative/Design',
            'Admin/Human Resource',
            'Build/Architecture/Construction'
        ])->sort()->values();
        
        $states = collect([
            'Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Melaka',
            'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis',
            'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'
        ])->sort()->values();
        
        $propertyTypes = \App\Models\Rental::select('property_type')
            ->whereNotNull('property_type')
            ->where('property_type', '!=', '')
            ->distinct()
            ->orderBy('property_type')
            ->pluck('property_type');

        return view('public.profile', compact('user', 'preferences', 'industries', 'states', 'propertyTypes'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Name updated successfully.');
    }
}

