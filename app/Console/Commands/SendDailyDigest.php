<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Internship;
use App\Models\Rental;
use App\Services\UserPreferenceService;
use App\Notifications\NewListingsNotification;

class SendDailyDigest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-daily-digest 
                            {--force : Force send digest regardless of last notification time}
                            {--frequency=daily : Notification frequency to send (daily or weekly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send digest emails to users based on their notification preferences';

    /**
     * Execute the console command.
     */
    public function handle(UserPreferenceService $preferenceService)
    {
        $frequency = $this->option('frequency');
        $force = $this->option('force');
        
        $this->info("Starting {$frequency} digest...");
        
        // Get users with matching notification frequency (or all if forced)
        $query = User::query();
        
        if (!$force) {
            $query->whereHas('preferences', function ($q) use ($frequency) {
                $q->where('notification_frequency', $frequency)
                    ->where(function ($sub) use ($frequency) {
                        // For daily: check if last notified > 1 day ago
                        // For weekly: check if last notified > 7 days ago
                        $daysAgo = $frequency === 'weekly' ? 7 : 1;
                        $sub->where('last_notified_at', '<', now()->subDays($daysAgo))
                            ->orWhereNull('last_notified_at');
                    });
            });
        }
        
        $users = $query->with('preferences')->get();
        
        $this->info("Found {$users->count()} users with '{$frequency}' notification preference.");
        
        
        $sentCount = 0;
        
        foreach ($users as $user) {
            $newInternships = $this->getNewMatchingInternships($user, $preferenceService, $force);
            $newRentals = $this->getNewMatchingRentals($user, $preferenceService, $force);
            
            if ($newInternships->isNotEmpty() || $newRentals->isNotEmpty()) {
                try {
                    $user->notify(new NewListingsNotification($newInternships, $newRentals));
                    $user->preferences->update(['last_notified_at' => now()]);
                    $sentCount++;
                    
                    $this->info("✓ Sent to {$user->email} ({$newInternships->count()} internships, {$newRentals->count()} rentals)");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to send to {$user->email}: {$e->getMessage()}");
                }
            } else {
                $this->line("- No new listings for {$user->email}");
            }
        }
        
        $this->info("Daily digest complete! Sent {$sentCount} emails.");
        
        return Command::SUCCESS;
    }
    
    /**
     * Get new internships matching user preferences
     */
    private function getNewMatchingInternships(User $user, UserPreferenceService $preferenceService, bool $force = false)
    {
        $lastNotified = $user->preferences->last_notified_at ?? now()->subDay();
        
        // If forced, we might want to look at the last 24h even if they were notified recently
        if ($force && $lastNotified > now()->subDay()) {
            $lastNotified = now()->subDay();
        }
        
        $newInternships = Internship::where('created_at', '>', $lastNotified)
            ->orderByDesc('created_at')
            ->get();
        
        return $newInternships->filter(function ($internship) use ($user, $preferenceService) {
            return $preferenceService->matchesPreferences($user, $internship);
        });
    }
    
    /**
     * Get new rentals matching user preferences
     */
    private function getNewMatchingRentals(User $user, UserPreferenceService $preferenceService, bool $force = false)
    {
        $lastNotified = $user->preferences->last_notified_at ?? now()->subDay();

        if ($force && $lastNotified > now()->subDay()) {
            $lastNotified = now()->subDay();
        }
        
        $newRentals = Rental::where('created_at', '>', $lastNotified)
            ->where('is_available', 1)
            ->orderByDesc('created_at')
            ->get();
        
        return $newRentals->filter(function ($rental) use ($user, $preferenceService) {
            return $preferenceService->matchesPreferences($user, $rental);
        });
    }
}

