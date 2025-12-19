<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TournamentSeeder extends Seeder
{
    public function run(): void
    {
        $groupA = Group::create(['name' => 'Group A']);
        $groupB = Group::create(['name' => 'Group B']);

        $teamsA = ['Dragons FC', 'United Stars', 'River Plate', 'City Warriors', 'Golden Eagles'];
        $teamsB = ['Phoenix AFC', 'Iron Giants', 'Celtic Pride', 'North Rangers', 'Olympic Elite'];

        foreach ($teamsA as $name) {
            Team::create(['name' => $name, 'group_id' => $groupA->id]);
        }

        foreach ($teamsB as $name) {
            Team::create(['name' => $name, 'group_id' => $groupB->id]);
        }
    }
}
