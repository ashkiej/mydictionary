<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Favorite;
use Carbon\Carbon;

class CleanupOldFavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function testDeletesFavoritesOlderThan30Days()
    {
        // Create a favorite older than 30 days
        $oldFavorite = Favorite::factory()->create([
            'created_at' => Carbon::now()->subDays(31),
        ]);

        // Create a recent favorite
        $recentFavorite = Favorite::factory()->create([
            'created_at' => Carbon::now()->subDays(10),
        ]);

        // Run the command
        $this->artisan('favorites:cleanup')
            ->expectsOutput('Deleted 1 old favorites.')
            ->assertExitCode(0);

        // Assert the old favorite is deleted, recent one remains
        $this->assertDatabaseMissing('favorites', ['id' => $oldFavorite->id]);
        $this->assertDatabaseHas('favorites', ['id' => $recentFavorite->id]);
    }
}
