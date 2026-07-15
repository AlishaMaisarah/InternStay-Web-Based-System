<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Rental;
use App\Models\User;
use App\Models\CompanyProfile;
use App\Services\UserPreferenceService;
use App\Notifications\NewListingsNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Collections for your blade usage ($internships->count(), $rentals->where(...))
        $internships = Internship::all();
        $rentals = Rental::all();

        // Recent internships list
        $recentInternships = Internship::latest()->take(5)->get();

        // Company statistics
        $totalCompanies = User::where('role', 'company')->count();
        $pendingVerifications = CompanyProfile::where('verification_status', 'Pending')->count();
        $approvedCompanies = CompanyProfile::where('verification_status', 'Approved')->count();
        $companyPostedInternships = Internship::whereNotNull('user_id')->count();

        return view('dashboard', compact(
            'internships', 
            'rentals', 
            'recentInternships',
            'totalCompanies',
            'pendingVerifications',
            'approvedCompanies',
            'companyPostedInternships'
        ));
    }

    public function sendRecommendations(UserPreferenceService $preferenceService)
    {
        // 1. Get all users who have completed onboarding and have daily/weekly frequency selected
        $users = User::where('onboarding_completed', true)
            ->whereHas('preferences', function ($q) {
                $q->whereIn('notification_frequency', ['daily', 'weekly']);
            })
            ->with('preferences')
            ->get();

        $processedCount = $users->count();
        $sentCount = 0;
        $skippedAlreadyNotified = 0;
        $skippedNoRecommendations = 0;
        $failedCount = 0;

        if ($processedCount > 0) {
            // Find oldest lookback to preload matching listings (N+1 query optimization)
            $minLastNotified = $users->filter(function($u) {
                return !is_null($u->preferences->last_notified_at);
            })->min('preferences.last_notified_at');

            $oldestLookback = $minLastNotified ? $minLastNotified->copy() : now()->subDays(7);
            if ($oldestLookback->lt(now()->subDays(30))) {
                $oldestLookback = now()->subDays(30);
            }

            // Preload all listings scraped since the oldest lookback
            $allRecentInternships = Internship::where('created_at', '>', $oldestLookback)
                ->orderByDesc('created_at')
                ->get();

            $allRecentRentals = Rental::where('created_at', '>', $oldestLookback)
                ->where('is_available', 1)
                ->orderByDesc('created_at')
                ->get();

            foreach ($users as $user) {
                $prefs = $user->preferences;
                $frequency = $prefs->notification_frequency;
                $lastNotified = $prefs->last_notified_at;

                // 2. Check notification interval rules
                $isIntervalSatisfied = true;
                if ($lastNotified) {
                    if ($frequency === 'daily') {
                        if ($lastNotified->greaterThanOrEqualTo(now()->subHours(24))) {
                            $isIntervalSatisfied = false;
                        }
                    } elseif ($frequency === 'weekly') {
                        if ($lastNotified->greaterThanOrEqualTo(now()->subDays(7))) {
                            $isIntervalSatisfied = false;
                        }
                    }
                }

                if (!$isIntervalSatisfied) {
                    $skippedAlreadyNotified++;
                    continue;
                }

                // Determine this user's lookback window
                $userLookback = $lastNotified;
                if (!$userLookback) {
                    $userLookback = $frequency === 'weekly' ? now()->subDays(7) : now()->subHours(24);
                }

                // Filter recommendations in memory
                $newInternships = $allRecentInternships->filter(function ($internship) use ($userLookback, $user, $preferenceService) {
                    return $internship->created_at->gt($userLookback) && $preferenceService->matchesPreferences($user, $internship);
                });

                $newRentals = $allRecentRentals->filter(function ($rental) use ($userLookback, $user, $preferenceService) {
                    return $rental->created_at->gt($userLookback) && $preferenceService->matchesPreferences($user, $rental);
                });

                // Skip if no recommendations are available
                if ($newInternships->isEmpty() && $newRentals->isEmpty()) {
                    $skippedNoRecommendations++;
                    continue;
                }

                // 3. Send notification and update last_notified_at in transaction
                try {
                    DB::transaction(function () use ($user, $newInternships, $newRentals, $prefs) {
                        $user->notify(new NewListingsNotification($newInternships, $newRentals));
                        $prefs->update(['last_notified_at' => now()]);
                    });
                    $sentCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Failed to send recommendation email to user ID {$user->id} ({$user->email}): " . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('recommendation_results', [
            'processed' => $processedCount,
            'sent' => $sentCount,
            'skipped_already_notified' => $skippedAlreadyNotified,
            'skipped_no_recommendations' => $skippedNoRecommendations,
            'failed' => $failedCount,
        ]);
    }
}


