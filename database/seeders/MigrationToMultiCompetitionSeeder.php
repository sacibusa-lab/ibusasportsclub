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
        $comp = \App\Models\Competition::firstOrCreate(
            ['slug' => 'main-competition'],
            ['name' => 'Main Competition', 'type' => 'league', 'is_active' => true]
        );

        \App\Models\Group::whereNull('competition_id')->update(['competition_id' => $comp->id]);
        \App\Models\MatchModel::whereNull('competition_id')->update(['competition_id' => $comp->id]);

        foreach (\App\Models\Team::all() as $team) {
            if ($team->group_id) {
                $group = \App\Models\Group::find($team->group_id);
                if ($group && $group->competition_id) {
                    \App\Models\CompetitionTeam::updateOrCreate(
                        ['competition_id' => $group->competition_id, 'team_id' => $team->id],
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
        }
    }
}
