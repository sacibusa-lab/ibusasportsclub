<?php

namespace App\Services;

use App\Models\Player;
use App\Models\MatchEvent;
use App\Models\MatchModel;

class StatisticsService
{
    public function getTopScorers($limit = 10, $competitionId = null)
    {
        return Player::withCount(['goals' => function($query) use ($competitionId) {
                $query->where('event_type', 'goal')
                      ->whereHas('match', function($q) use ($competitionId) {
                          $q->where('stage', '!=', 'novelty');
                          if ($competitionId) {
                              $q->where('competition_id', $competitionId);
                          }
                      });
            }])
            ->with(['team', 'team.competitionTeams' => function($q) use ($competitionId) {
                if ($competitionId) $q->where('competition_id', $competitionId);
            }])
            ->having('goals_count', '>', 0)
            ->orderBy('goals_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopAssists($limit = 10, $competitionId = null)
    {
        return Player::withCount(['assists' => function($query) use ($competitionId) {
                $query->where('event_type', 'goal')
                      ->whereHas('match', function($q) use ($competitionId) {
                          $q->where('stage', '!=', 'novelty');
                          if ($competitionId) {
                              $q->where('competition_id', $competitionId);
                          }
                      });
            }])
            ->with('team')
            ->having('assists_count', '>', 0)
            ->orderBy('assists_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopCards($limit = 10, $competitionId = null)
    {
        return Player::withCount(['cards' => function($query) use ($competitionId) {
                $query->whereHas('match', function($q) use ($competitionId) {
                    $q->where('stage', '!=', 'novelty');
                    if ($competitionId) {
                        $q->where('competition_id', $competitionId);
                    }
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
    public function getTopCleanSheets($limit = 10, $competitionId = null)
    {
        $gks = Player::where('position', 'GK')->with('team')->get();
        
        foreach ($gks as $gk) {
            $baseQuery = MatchModel::where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->where(function($q) use ($gk) {
                    $q->whereHas('lineups', function($lq) use ($gk) {
                        $lq->where('players.id', $gk->id)
                           ->where('match_lineups.is_substitute', false);
                    })
                    ->orWhereHas('matchEvents', function($eq) use ($gk) {
                        $eq->where('player_id', $gk->id)
                           ->where('event_type', 'sub_on');
                    });
                });

            if ($competitionId) {
                $baseQuery->where('competition_id', $competitionId);
            }

            $homeCleanSheets = (clone $baseQuery)->where('home_team_id', $gk->team_id)
                ->where('away_score', 0)
                ->count();
                
            $awayCleanSheets = (clone $baseQuery)->where('away_team_id', $gk->team_id)
                ->where('home_score', 0)
                ->count();
                
            $gk->clean_sheets_count = $homeCleanSheets + $awayCleanSheets;
        }

        return $gks->filter(function($gk) {
            return $gk->clean_sheets_count > 0;
        })->sortByDesc('clean_sheets_count')->take($limit);
    }

    public function getTopMOTM($limit = 10, $competitionId = null)
    {
        return Player::withCount(['motmAwards' => function($query) use ($competitionId) {
                $query->where('stage', '!=', 'novelty');
                if ($competitionId) {
                    $query->where('competition_id', $competitionId);
                }
            }])
            ->with('team')
            ->having('motm_awards_count', '>', 0)
            ->orderBy('motm_awards_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopTeamsByStat($stat, $limit = 10, $competitionId = null)
    {
        $teams = \App\Models\Team::all();
        
        foreach ($teams as $team) {
            $baseQuery = MatchModel::where('status', 'finished')
                ->where('stage', '!=', 'novelty');
            
            if ($competitionId) {
                $baseQuery->where('competition_id', $competitionId);
            }

            $homeStat = (clone $baseQuery)->where('home_team_id', $team->id)
                ->sum('home_' . $stat);
                
            $awayStat = (clone $baseQuery)->where('away_team_id', $team->id)
                ->sum('away_' . $stat);
                
            $team->total_stat = $homeStat + $awayStat;
        }

        return $teams->filter(function($team) {
            return $team->total_stat > 0;
        })->sortByDesc('total_stat')->take($limit);
    }
}
