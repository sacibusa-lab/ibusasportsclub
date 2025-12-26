<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\MatchModel;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PredictionClosingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_start_match_triggering_countdown()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $competition = Competition::create([
            'name' => 'Test Comp', 
            'slug' => 'test-comp', 
            'type' => 'league',
            'is_active' => true
        ]);
        
        $home = Team::create(['name' => 'Home', 'group_id' => 1]); // Group ID mock
        $away = Team::create(['name' => 'Away', 'group_id' => 1]);

        $match = MatchModel::create([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'competition_id' => $competition->id,
            'match_date' => now()->addHour(),
            'status' => 'upcoming',
            'stage' => 'group'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.matches.start', $match->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $match->refresh();
        $this->assertNotNull($match->prediction_closes_at);
        // Should be roughly 5 minutes from now
        $this->assertEqualsWithDelta(now()->addMinutes(5)->timestamp, $match->prediction_closes_at->timestamp, 5);
    }

    public function test_user_can_predict_within_window()
    {
        $user = User::factory()->create();
        $competition = Competition::create([
            'name' => 'Test Comp', 
            'slug' => 'test-comp', 
            'type' => 'league',
            'is_active' => true
        ]);
        $home = Team::create(['name' => 'Home', 'group_id' => 1]);
        $away = Team::create(['name' => 'Away', 'group_id' => 1]);

        // Match started 1 minute ago (closes in 4 mins)
        $match = MatchModel::create([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'competition_id' => $competition->id,
            'match_date' => now()->addHour(),
            'status' => 'upcoming',
            'stage' => 'group',
            'prediction_closes_at' => now()->addMinutes(4)
        ]);

        $response = $this->actingAs($user)
            ->post(route('predictor.predict'), [
                'match_id' => $match->id,
                'home_score' => 1,
                'away_score' => 1
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('predictions', [
            'user_id' => $user->id,
            'match_id' => $match->id,
            'home_score' => 1,
            'away_score' => 1
        ]);
    }

    public function test_user_cannot_predict_after_window_closes()
    {
        $user = User::factory()->create();
        $competition = Competition::create([
            'name' => 'Test Comp', 
            'slug' => 'test-comp', 
            'type' => 'league',
            'is_active' => true
        ]);
        $home = Team::create(['name' => 'Home', 'group_id' => 1]);
        $away = Team::create(['name' => 'Away', 'group_id' => 1]);

        // Window closed 1 minute ago
        $match = MatchModel::create([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'competition_id' => $competition->id,
            'match_date' => now()->addHour(),
            'status' => 'upcoming',
            'stage' => 'group',
            'prediction_closes_at' => now()->subMinute()
        ]);

        $response = $this->actingAs($user)
            ->post(route('predictor.predict'), [
                'match_id' => $match->id,
                'home_score' => 1,
                'away_score' => 1
            ]);

        $response->assertSessionHas('error', 'Predictions closed for this match.');
        $this->assertDatabaseMissing('predictions', [
            'user_id' => $user->id,
            'match_id' => $match->id
        ]);
    }
}
