<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Group;
use App\Models\MatchModel;

class TournamentService
{
    public function updateStandings(Group $group)
    {
        $teams = $group->teams;
        
        foreach ($teams as $team) {
            $homeMatches = MatchModel::where('home_team_id', $team->id)
                ->where('status', 'finished')
                ->where('stage', 'group')
                ->get();
                
            $awayMatches = MatchModel::where('away_team_id', $team->id)
                ->where('status', 'finished')
                ->where('stage', 'group')
                ->get();

            $played = $homeMatches->count() + $awayMatches->count();
            $wins = 0;
            $draws = 0;
            $losses = 0;
            $gf = 0;
            $ga = 0;

            foreach ($homeMatches as $match) {
                $gf += $match->home_score;
                $ga += $match->away_score;
                if ($match->home_score > $match->away_score) $wins++;
                elseif ($match->home_score < $match->away_score) $losses++;
                else $draws++;
            }

            foreach ($awayMatches as $match) {
                $gf += $match->away_score;
                $ga += $match->home_score;
                if ($match->away_score > $match->home_score) $wins++;
                elseif ($match->away_score < $match->home_score) $losses++;
                else $draws++;
            }

            $team->update([
                'played' => $played,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
                'goals_for' => $gf,
                'goals_against' => $ga,
                'points' => ($wins * 3) + $draws
            ]);
        }
    }

    public function getSortedStandings(Group $group)
    {
        $teams = $group->teams()
            ->orderBy('points', 'desc')
            ->orderByRaw('(goals_for - goals_against) desc')
            ->orderBy('goals_for', 'desc')
            ->get();

        foreach ($teams as $team) {
            $team->form = $this->getTeamForm($team);
            $team->next_match = $this->getNextMatch($team);
            $team->goal_difference = $team->goals_for - $team->goals_against;
        }

        return $teams;
    }

    public function getTeamForm(Team $team)
    {
        $matches = MatchModel::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'finished')
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();

        $form = [];
        foreach ($matches as $match) {
            if ($match->home_team_id == $team->id) {
                if ($match->home_score > $match->away_score) $form[] = 'W';
                elseif ($match->home_score < $match->away_score) $form[] = 'L';
                else $form[] = 'D';
            } else {
                if ($match->away_score > $match->home_score) $form[] = 'W';
                elseif ($match->away_score < $match->home_score) $form[] = 'L';
                else $form[] = 'D';
            }
        }

        return array_reverse($form); // Most recent last in visualization
    }

    public function getNextMatch(Team $team)
    {
        return MatchModel::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'upcoming')
            ->orderBy('match_date', 'asc')
            ->first();
    }

    public function generateKnockouts()
    {
        $groupA = Group::where('name', 'Group A')->first();
        $groupB = Group::where('name', 'Group B')->first();

        if (!$groupA || !$groupB) return;

        $standingsA = $this->getSortedStandings($groupA);
        $standingsB = $this->getSortedStandings($groupB);

        // SF1: A1 vs B2
        MatchModel::updateOrCreate(
            ['stage' => 'semifinal', 'venue' => 'SF1'],
            [
                'home_team_id' => $standingsA[0]->id,
                'away_team_id' => $standingsB[1]->id,
                'match_date' => now()->addDays(7),
                'status' => 'upcoming'
            ]
        );

        // SF2: B1 vs A2
        MatchModel::updateOrCreate(
            ['stage' => 'semifinal', 'venue' => 'SF2'],
            [
                'home_team_id' => $standingsB[0]->id,
                'away_team_id' => $standingsA[1]->id,
                'match_date' => now()->addDays(7),
                'status' => 'upcoming'
            ]
        );
    }
}
