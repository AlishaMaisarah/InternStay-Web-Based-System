<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send DAILY digest emails at 9 AM every day (for users with frequency = 'daily')
        $schedule->command('notifications:send-daily-digest --frequency=daily')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Send WEEKLY digest emails every Friday at 9 AM (for users with frequency = 'weekly')
        $schedule->command('notifications:send-daily-digest --frequency=weekly')
            ->weekly()
            ->friday()
            ->at('09:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Run full scraping pipeline (internships + rentals) every Friday at 8 AM
        $schedule->command('app:run-pipeline')
            ->weekly()
            ->friday()
            ->at('08:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Run listings availability checker every day at 3 AM to automatically close expired/occupied listings
        $schedule->command('listings:check-availability')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
