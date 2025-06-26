<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CleanupOldFavorites::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('favorites:cleanup')
            ->dailyAt('02:00')
            ->timezone(config('app.timezone'))
            ->withoutOverlapping()
            ->onFailure(function () {
                Log::error('Favorite cleanup command failed');
                // Consider adding notification here
            })
            ->after(function () {
                Log::info('Favorite cleanup completed successfully');
            });
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
