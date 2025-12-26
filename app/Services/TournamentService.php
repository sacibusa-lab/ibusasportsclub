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
        $competitionId = $group->competition_id;

        if (!$competitionId) {
            $competitionId = \App\Models\Competition::where('slug', 'main-competition')->value('id');
        }
        
        foreach ($teams as $team) {
            $homeMatches = MatchModel::where('home_team_id', $team->id)
                ->where('competition_id', $competitionId)
                ->where('status', 'finished')
                ->where('stage', 'group')
                ->get();
                
            $awayMatches = MatchModel::where('away_team_id', $team->id)
                ->where('competition_id', $competitionId)
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

            \App\Models\CompetitionTeam::updateOrCreate(
                ['competition_id' => $competitionId, 'team_id' => $team->id],
                [
                    'played' => $played,
                    'wins' => $wins,
                    'draws' => $draws,
                    'losses' => $losses,
                    'goals_for' => $gf,
                    'goals_against' => $ga,
                    'points' => ($wins * 3) + $draws
                ]
            );
        }
    }

    public function getSortedStandings(Group $group)
    {
        $competitionId = $group->competition_id;
        
        if (!$competitionId) {
            $competitionId = \App\Models\Competition::where('slug', 'main-competition')->value('id');
        }

        $compTeams = \App\Models\CompetitionTeam::with('team')
            ->where('competition_id', $competitionId)
            ->whereIn('team_id', $group->teams()->pluck('id'))
            ->orderBy('points', 'desc')
            ->orderByRaw('(goals_for - goals_against) desc')
            ->orderBy('goals_for', 'desc')
            ->get();

        $teams = $compTeams->map(function ($compTeam) {
            $team = $compTeam->team;
            $team->played = $compTeam->played;
            $team->wins = $compTeam->wins;
            $team->draws = $compTeam->draws;
            $team->losses = $compTeam->losses;
            $team->goals_for = $compTeam->goals_for;
            $team->goals_against = $compTeam->goals_against;
            $team->points = $compTeam->points;
            $team->goal_difference = $compTeam->goals_for - $compTeam->goals_against;
            $team->form = $this->getTeamForm($team, $compTeam->competition_id);
            $team->next_match = $this->getNextMatch($team, $compTeam->competition_id);
            return $team;
        });

        return $teams;
    }

    public function getTeamForm(Team $team, $competitionId = null)
    {
        $query = MatchModel::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'finished');
            
        if ($competitionId) {
            $query->where('competition_id', $competitionId);
        }

        $matches = $query->orderBy('match_date', 'desc')
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

    public function getNextMatch(Team $team, $competitionId = null)
    {
        $query = MatchModel::where(function($query) use ($team) {
                $query->where('home_team_id', $team->id)
                      ->orWhere('away_team_id', $team->id);
            })
            ->where('status', 'upcoming');
            
        if ($competitionId) {
            $query->where('competition_id', $competitionId);
        }

        return $query->orderBy('match_date', 'asc')
            ->first();
    }

    public function getTeamRankInGroup(Team $team, Group $group)
    {
        $standings = $this->getSortedStandings($group);
        $rank = $standings->search(function($t) use ($team) {
            return $t->id == $team->id;
        });
        return $rank !== false ? $rank + 1 : null;
    }

    public function getTeamFormString(Team $team, $matches)
    {
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
        return implode(' ', array_reverse($form));
    }

    public function getTeamCleanSheets(Team $team)
    {
        return MatchModel::where('status', 'finished')
            ->where(function($q) use ($team) {
                $q->where(fn($sq) => $sq->where('home_team_id', $team->id)->where('away_score', 0))
                  ->orWhere(fn($sq) => $sq->where('away_team_id', $team->id)->where('home_score', 0));
            })
            ->count();
    }

    public function getTeamTotalStat(Team $team, $stat)
    {
        $home = MatchModel::where('home_team_id', $team->id)->sum('home_' . $stat);
        $away = MatchModel::where('away_team_id', $team->id)->sum('away_' . $stat);
        return $home + $away;
    }

    public function getTeamBiggestWin(Team $team)
    {
        $matches = MatchModel::where('status', 'finished')
            ->where(function($q) use ($team) {
                $q->where('home_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
            })->get();

        $biggestWin = null;
        $maxDiff = -1;

        foreach ($matches as $match) {
            if ($match->home_team_id == $team->id) {
                $diff = $match->home_score - $match->away_score;
            } else {
                $diff = $match->away_score - $match->home_score;
            }

            if ($diff > $maxDiff) {
                $maxDiff = $diff;
                $biggestWin = $match;
            }
        }

        return ($maxDiff > 0) ? $biggestWin : null;
    }

    public function getTeamBiggestLoss(Team $team)
    {
        $matches = MatchModel::where('status', 'finished')
            ->where(function($q) use ($team) {
                $q->where('home_team_id', $team->id)
                  ->orWhere('away_team_id', $team->id);
            })->get();

        $biggestLoss = null;
        $maxDiff = -1;

        foreach ($matches as $match) {
            if ($match->home_team_id == $team->id) {
                $diff = $match->away_score - $match->home_score;
            } else {
                $diff = $match->home_score - $match->away_score;
            }

            if ($diff > $maxDiff) {
                $maxDiff = $diff;
                $biggestLoss = $match;
            }
        }

        return ($maxDiff > 0) ? $biggestLoss : null;
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
