<?php

namespace App\Services;

use App\Models\Player;
use App\Models\MatchEvent;
use App\Models\MatchModel;

class StatisticsService
{
    public function getTopScorers($limit = 10)
    {
        return Player::withCount(['goals' => function($query) {
                $query->where('event_type', 'goal')
                      ->whereHas('match', function($q) {
                          $q->where('stage', '!=', 'novelty');
                      });
            }])
            ->with('team')
            ->having('goals_count', '>', 0)
            ->orderBy('goals_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopAssists($limit = 10)
    {
        return Player::withCount(['assists' => function($query) {
                $query->where('event_type', 'goal')
                      ->whereHas('match', function($q) {
                          $q->where('stage', '!=', 'novelty');
                      });
            }])
            ->with('team')
            ->having('assists_count', '>', 0)
            ->orderBy('assists_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopCards($limit = 10)
    {
        return Player::withCount(['cards' => function($query) {
                $query->whereHas('match', function($q) {
                    $q->where('stage', '!=', 'novelty');
                });
            }])
            ->with('team')
            ->having('cards_count', '>', 0)
            ->orderBy('cards_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Clean Sheets are calculated by finding all matches where a team conceded 0 
     * and attributing it to their registered Goalkeeper(s) who were in the squad.
     * Since we don't have full squad tracking yet, we attribute it to any GK in the team.
     */
    public function getTopCleanSheets($limit = 10)
    {
        $gks = Player::where('position', 'GK')->with('team')->get();
        
        foreach ($gks as $gk) {
            $homeCleanSheets = MatchModel::where('home_team_id', $gk->team_id)
                ->where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->where('away_score', 0)
                ->count();
                
            $awayCleanSheets = MatchModel::where('away_team_id', $gk->team_id)
                ->where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->where('home_score', 0)
                ->count();
                
            $gk->clean_sheets_count = $homeCleanSheets + $awayCleanSheets;
        }

        return $gks->filter(function($gk) {
            return $gk->clean_sheets_count > 0;
        })->sortByDesc('clean_sheets_count')->take($limit);
    }

    public function getTopMOTM($limit = 10)
    {
        return Player::withCount(['motmAwards' => function($query) {
                $query->where('stage', '!=', 'novelty');
            }])
            ->with('team')
            ->having('motm_awards_count', '>', 0)
            ->orderBy('motm_awards_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopTeamsByStat($stat, $limit = 10)
    {
        $teams = \App\Models\Team::all();
        
        foreach ($teams as $team) {
            $homeStat = MatchModel::where('home_team_id', $team->id)
                ->where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->sum('home_' . $stat);
                
            $awayStat = MatchModel::where('away_team_id', $team->id)
                ->where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->sum('away_' . $stat);
                
            $team->total_stat = $homeStat + $awayStat;
        }

        return $teams->filter(function($team) {
            return $team->total_stat > 0;
        })->sortByDesc('total_stat')->take($limit);
    }
}
