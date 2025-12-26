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

    public function getTopYellowCards($limit = 10, $competitionId = null)
    {
        return Player::withCount(['yellowCards' => function($query) use ($competitionId) {
                $query->whereHas('match', function($q) use ($competitionId) {
                    $q->where('stage', '!=', 'novelty');
                    if ($competitionId) {
                        $q->where('competition_id', $competitionId);
                    }
                });
            }])
            ->with('team')
            ->having('yellow_cards_count', '>', 0)
            ->orderBy('yellow_cards_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopRedCards($limit = 10, $competitionId = null)
    {
        return Player::withCount(['redCards' => function($query) use ($competitionId) {
                $query->whereHas('match', function($q) use ($competitionId) {
                    $q->where('stage', '!=', 'novelty');
                    if ($competitionId) {
                        $q->where('competition_id', $competitionId);
                    }
                });
            }])
            ->with('team')
            ->having('red_cards_count', '>', 0)
            ->orderBy('red_cards_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Clean Sheets are calculated by finding all matches where a team conceded 0 
     * and attributing it to their registered Goalkeeper(s) who were in the squad.
     * Since we don't have full squad tracking yet, we attribute it to any GK in the team.
     */
    /**
     * Clean Sheets are attributed to the starting Goalkeeper.
     */
    public function getTopCleanSheets($limit = 10, $competitionId = null)
    {
        // Find all GKs who played in the selected competition
        $gks = Player::where('position', 'GK')
            ->whereHas('team.competitionTeams', function($q) use ($competitionId) {
                if ($competitionId) $q->where('competition_id', $competitionId);
            })
            ->with(['team', 'team.competitionTeams' => function($q) use ($competitionId) {
                if ($competitionId) $q->where('competition_id', $competitionId);
            }])
            ->get();
        
        foreach ($gks as $gk) {
            $baseQuery = MatchModel::where('status', 'finished')
                ->where('stage', '!=', 'novelty')
                ->where(function($q) use ($gk) {
                    // Played as starter
                    $q->whereHas('lineups', function($lq) use ($gk) {
                        $lq->where('players.id', $gk->id)
                           ->where('match_lineups.is_substitute', false);
                    });
                    // Or subbed on (simplified: if they played, they get credit for CS)
                    $q->orWhereHas('matchEvents', function($eq) use ($gk) {
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
        })->sortByDesc('clean_sheets_count')->take($limit)->values();
    }

    public function getTopMOTM($limit = 10, $competitionId = null)
    {
        return Player::withCount(['motmAwards' => function($query) use ($competitionId) {
                $query->where('stage', '!=', 'novelty');
                if ($competitionId) {
                    $query->where('competition_id', $competitionId);
                }
            }])
            ->with(['team', 'team.competitionTeams' => function($q) use ($competitionId) {
                if ($competitionId) $q->where('competition_id', $competitionId);
            }])
            ->having('motm_awards_count', '>', 0)
            ->orderBy('motm_awards_count', 'desc')
            ->take($limit)
            ->get();
    }

    public function getTopTeamsByStat($stat, $limit = 10, $competitionId = null)
    {
        $teams = \App\Models\Team::whereHas('competitionTeams', function($q) use ($competitionId) {
            if ($competitionId) $q->where('competition_id', $competitionId);
        })->get();
        
        foreach ($teams as $team) {
            $baseQuery = MatchModel::where('status', 'finished')
                ->where('stage', '!=', 'novelty');
            
            if ($competitionId) {
                $baseQuery->where('competition_id', $competitionId);
            }

            // Map stats to correct columns
            $homeCol = 'home_' . $stat;
            $awayCol = 'away_' . $stat;

            if ($stat === 'for') {
                $homeCol = 'home_score';
                $awayCol = 'away_score';
            } elseif ($stat === 'against') {
                $homeCol = 'away_score';
                $awayCol = 'home_score';
            }

            $homeStat = (clone $baseQuery)->where('home_team_id', $team->id)
                ->sum($homeCol);
                
            $awayStat = (clone $baseQuery)->where('away_team_id', $team->id)
                ->sum($awayCol);
                
            $team->total_stat = $homeStat + $awayStat;

            // Optional: set the specific property if needed for views
            if ($stat === 'for') $team->goals_for = $team->total_stat;
            if ($stat === 'against') $team->goals_against = $team->total_stat;
        }

        return $teams->filter(function($team) {
            return $team->total_stat > 0;
        })->sortByDesc('total_stat')->take($limit)->values();
    }

    /**
     * Get player ranking in a category.
     */
    public function getPlayerRank($playerId, $category, $competitionId = null)
    {
        $limit = 1000; // Search widely for ranking
        $list = collect();

        switch ($category) {
            case 'goals':
                $list = $this->getTopScorers($limit, $competitionId);
                break;
            case 'assists':
                $list = $this->getTopAssists($limit, $competitionId);
                break;
            case 'clean_sheets':
                $list = $this->getTopCleanSheets($limit, $competitionId);
                break;
            case 'motm':
                $list = $this->getTopMOTM($limit, $competitionId);
                break;
        }

        $rank = $list->search(function($p) use ($playerId) {
            return $p->id == $playerId;
        });

        return $rank !== false ? $rank + 1 : null;
    }

    /**
     * Get team ranking in a category.
     */
    public function getTeamRank($teamId, $category, $competitionId = null)
    {
        $limit = 1000;
        $list = collect();

        switch ($category) {
            case 'goals_for':
                $list = $this->getTopTeamsByStat('for', $limit, $competitionId);
                break;
            case 'goals_against':
                $list = $this->getTopTeamsByStat('against', $limit, $competitionId);
                break;
            case 'shots':
                $list = $this->getTopTeamsByStat('shots', $limit, $competitionId);
                break;
            case 'clean_sheets':
                // Custom logic for team clean sheets
                $teams = \App\Models\Team::all();
                foreach ($teams as $team) {
                    $team->clean_sheets_count = MatchModel::where('status', 'finished')
                        ->where(function($q) use ($team) {
                            $q->where(fn($sq) => $sq->where('home_team_id', $team->id)->where('away_score', 0))
                              ->orWhere(fn($sq) => $sq->where('away_team_id', $team->id)->where('home_score', 0));
                        })
                        ->when($competitionId, fn($q) => $q->where('competition_id', $competitionId))
                        ->count();
                }
                $list = $teams->sortByDesc('clean_sheets_count')->values();
                $key = 'clean_sheets_count';
                break;
        }

        $rank = $list->search(function($t) use ($teamId) {
            return $t->id == $teamId;
        });

        return $rank !== false ? $rank + 1 : null;
    }
}
