<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding welcome screen
     */
    public function welcome()
    {
        $user = Auth::user();
        
        // If already completed, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('public.dashboard');
        }
        
        return view('onboarding.welcome');
    }

    /**
     * Step 1: Internship Interests
     */
    public function step1(Request $request)
    {
        $user = Auth::user();
        
        // Get existing preferences if any
        $preferences = $user->preferences;
        
        return view('onboarding.step1', compact('preferences'));
    }

    /**
     * Step 2: Notification Settings
     */
    public function step2(Request $request)
    {
        // Save step 1 data (Internship Interests)
        if ($request->isMethod('post')) {
            $this->saveStep1($request);
        }
        
        $user = Auth::user();
        $preferences = $user->preferences;
        
        return view('onboarding.step2', compact('preferences'));
    }

    /**
     * Save all preferences and complete onboarding
     */
    public function complete(Request $request)
    {
        $request->validate([
            'notification_frequency' => 'required|in:instant,daily,weekly,off',
            'notify_internships' => 'nullable|boolean',
            'notify_rentals' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        
        // Save step 4 (notification preferences)
        $preferences = $user->preferences ?? new UserPreference(['user_id' => $user->id]);
        
        $preferences->notification_frequency = $request->notification_frequency;
        $preferences->notify_internships = $request->boolean('notify_internships', true);
        $preferences->notify_rentals = $request->boolean('notify_rentals', true);
        $preferences->save();

        // Mark onboarding as completed
        $user->onboarding_completed = true;
        $user->onboarding_completed_at = now();
        $user->save();

        return redirect()->route('public.dashboard')->with('success', '🎉 Welcome to InternStay! Your preferences have been saved.');
    }

    /**
     * Skip onboarding
     */
    public function skip()
    {
        $user = Auth::user();
        
        // Mark as completed but don't save preferences
        $user->onboarding_completed = true;
        $user->onboarding_completed_at = now();
        $user->save();

        return redirect()->route('public.dashboard')->with('info', 'You can set your preferences anytime from your profile.');
    }

    /**
     * Save Step 1: Internship Interests
     */
    private function saveStep1(Request $request)
    {
        $request->validate([
            'preferred_industries' => 'nullable|array',
            'course_of_study' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $preferences = $user->preferences ?? new UserPreference(['user_id' => $user->id]);
        
        $preferences->preferred_industries = $request->preferred_industries ?? [];
        $preferences->course_of_study = $request->course_of_study;
        $preferences->save();
    }
}
