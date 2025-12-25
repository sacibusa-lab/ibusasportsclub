<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MigrationToMultiCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        $comp = \App\Models\Competition::firstOrCreate(
            ['slug' => 'main-competition'],
            ['name' => 'Main Competition', 'type' => 'league', 'is_active' => true]
        );

        // Ensure we have the ID correctly
        $compId = $comp->id;

        \App\Models\Group::whereNull('competition_id')->update(['competition_id' => $compId]);
        \App\Models\MatchModel::whereNull('competition_id')->update(['competition_id' => $compId]);

        foreach (\App\Models\Team::all() as $team) {
            $effectiveGroupId = $team->group_id;
            if ($effectiveGroupId) {
                $group = \App\Models\Group::find($effectiveGroupId);
                $targetCompId = ($group && $group->competition_id) ? $group->competition_id : $compId;
                
                \App\Models\CompetitionTeam::updateOrCreate(
                    ['competition_id' => $targetCompId, 'team_id' => $team->id],
                    [
                        'played' => $team->played,
                        'wins' => $team->wins,
                        'draws' => $team->draws,
                        'losses' => $team->losses,
                        'goals_for' => $team->goals_for,
                        'goals_against' => $team->goals_against,
                        'points' => $team->points,
                    ]
                );
            }
        }

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
