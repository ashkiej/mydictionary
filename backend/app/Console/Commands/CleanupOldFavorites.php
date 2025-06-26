<?php

namespace App\Console\Commands;

use App\Models\Favorite;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOldFavorites extends Command
{
    protected $signature = 'favorites:cleanup';
    protected $description = 'Delete favorites older than 30 days';

    public function handle()
    {
        try {
            $count = Favorite::olderThan(30)->count();
            Favorite::olderThan(30)->delete();

            $this->info("Deleted {$count} old favorites.");
            Log::info("Cleanup completed: {$count} old favorites deleted.");

            return 0;
        } catch (\Exception $e) {
            $this->error('Error during cleanup: ' . $e->getMessage());
            Log::error('Favorites cleanup failed: ' . $e->getMessage());

            return 1;
        }
    }
}
