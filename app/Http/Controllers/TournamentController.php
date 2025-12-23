<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Group;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Post;
use App\Services\TournamentService;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

use App\Models\Story;

class TournamentController extends Controller
{
    protected $tournamentService;
    protected $statisticsService;

    public function __construct(TournamentService $tournamentService, StatisticsService $statisticsService)
    {
        $this->tournamentService = $tournamentService;
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $upcomingMatch = MatchModel::where('status', 'upcoming')->orderBy('match_date', 'asc')->first();
        $upcomingMatches = MatchModel::where('status', 'upcoming')->orderBy('match_date', 'asc')->limit(4)->get();
        $latestResult = MatchModel::where('status', 'finished')->orderBy('match_date', 'desc')->first();
        
        // News items
        $heroPost = Post::where('is_published', true)->with('category')->orderBy('published_at', 'desc')->first();
        $trendingPosts = Post::where('is_published', true)
            ->where('id', '!=', $heroPost->id ?? 0)
            ->with('category')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        $stories = Story::with('items')
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $highlights = MatchModel::whereNotNull('highlights_url')
            ->with(['homeTeam', 'awayTeam'])
            ->orderBy('match_date', 'desc')
            ->limit(4)
            ->get();

        $afconPosts = Post::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%Africa% Cup of Nations%');
            })
            ->with('category')
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        return view('home', compact('upcomingMatch', 'upcomingMatches', 'latestResult', 'heroPost', 'trendingPosts', 'stories', 'highlights', 'afconPosts'));
    }

    public function table()
    {
        $groups = Group::all();
        $standings = [];
        
        foreach ($groups as $group) {
            $standings[$group->name] = $this->tournamentService->getSortedStandings($group);
        }
        
        return view('table', compact('standings'));
    }

    public function fixtures()
    {
        $allFixtures = MatchModel::where('status', 'upcoming')
            ->orderBy('match_date', 'asc')
            ->get();

        $fixtures = $allFixtures->where('stage', '!=', 'novelty')->groupBy(function($match) {
                return $match->match_date->format('Y-m-d');
            });

        $noveltyFixtures = $allFixtures->where('stage', 'novelty');
            
        return view('fixtures', compact('fixtures', 'noveltyFixtures'));
    }

    public function results(Request $request)
    {
        $matchdayId = $request->query('matchday');
        $teamId = $request->query('team');
        
        $query = MatchModel::where('status', 'finished');
        
        if ($matchdayId) {
            $query->where('matchday', $matchdayId);
        }

        if ($teamId) {
            $query->where(function($q) use ($teamId) {
                $q->where('home_team_id', $teamId)->orWhere('away_team_id', $teamId);
            });
        }

        $allResults = $query->with(['homeTeam', 'awayTeam'])
            ->orderBy('match_date', 'desc')
            ->get();
            
        $resultsGrouped = $allResults->where('stage', '!=', 'novelty')->groupBy(function($match) {
            return $match->match_date->format('Y-m-d');
        });
        
        $noveltyResults = $allResults->where('stage', 'novelty');
        
        $matchdays = MatchModel::whereNotNull('matchday')->distinct()->pluck('matchday')->sortDesc();
        $teams = Team::orderBy('name')->get();
            
        return view('results', compact('resultsGrouped', 'noveltyResults', 'matchdays', 'teams', 'matchdayId', 'teamId'));
    }

    public function knockout()
    {
        $semifinals = MatchModel::where('stage', 'semifinal')->get();
        $final = MatchModel::where('stage', 'final')->first();
        
        return view('knockout', compact('semifinals', 'final'));
    }

    public function stats()
    {
        $topScorers = $this->statisticsService->getTopScorers(10);
        $topAssists = $this->statisticsService->getTopAssists(10);
        $topCleanSheets = $this->statisticsService->getTopCleanSheets(10);
        $topCards = $this->statisticsService->getTopCards(10);
        $topMOTM = $this->statisticsService->getTopMOTM(10);

        // Team Stats
        $topShots = $this->statisticsService->getTopTeamsByStat('shots', 5);
        $topCorners = $this->statisticsService->getTopTeamsByStat('corners', 5);
        $topOffsides = $this->statisticsService->getTopTeamsByStat('offsides', 5);
        $topFouls = $this->statisticsService->getTopTeamsByStat('fouls', 5);
        $topThrows = $this->statisticsService->getTopTeamsByStat('throw_ins', 5);
        $topSaves = $this->statisticsService->getTopTeamsByStat('saves', 5);
        $topGoalKicks = $this->statisticsService->getTopTeamsByStat('goal_kicks', 5);

        return view('stats', compact(
            'topScorers', 'topAssists', 'topCleanSheets', 'topCards', 'topMOTM',
            'topShots', 'topCorners', 'topOffsides', 'topFouls', 'topThrows', 'topSaves', 'topGoalKicks'
        ));
    }

    public function teams()
    {
        $teams = Team::whereHas('group', function($query) {
            $query->where('name', 'NOT LIKE', '%Friendly%');
        })->orderBy('name')->get();
        return view('teams', compact('teams'));
    }

    public function team($id)
    {
        $team = Team::with(['players' => function($q) {
            $q->withCount(['goals', 'assists', 'matchLineups']);
        }, 'group'])->findOrFail($id);

        // Calculate Rank
        $teams = Team::orderBy('points', 'desc')
            ->orderByRaw('(goals_for - goals_against) DESC')
            ->orderBy('goals_for', 'desc')
            ->get();
        $rank = $teams->search(function($t) use ($id) { return $t->id == $id; }) + 1;
        
        $nextMatch = MatchModel::where(function($q) use ($id) {
            $q->where('home_team_id', $id)->orWhere('away_team_id', $id);
        })->where('match_date', '>', now())->orderBy('match_date', 'asc')->first();

        $recentMatches = MatchModel::where(function($q) use ($id) {
            $q->where('home_team_id', $id)->orWhere('away_team_id', $id);
        })->where('match_date', '<=', now())->where('status', 'FT')->orderBy('match_date', 'desc')->take(5)->get();

        $squad = $team->players->groupBy('position');

        // Calculate additional statistics
        $allMatches = MatchModel::where(function($q) use ($id) {
            $q->where('home_team_id', $id)->orWhere('away_team_id', $id);
        })->where('status', 'FT')->get();

        $cleanSheets = $allMatches->filter(function($match) use ($id) {
            if ($match->home_team_id == $id) {
                return $match->away_score == 0;
            } else {
                return $match->home_score == 0;
            }
        })->count();

        $goalsPerGame = $team->played > 0 ? round($team->goals_for / $team->played, 2) : 0;
        $goalsConcededPerGame = $team->played > 0 ? round($team->goals_against / $team->played, 2) : 0;
        $winRate = $team->played > 0 ? round(($team->wins / $team->played) * 100, 1) : 0;

        // Calculate biggest win and biggest loss
        $biggestWin = null;
        $biggestLoss = null;
        $biggestWinMargin = 0;
        $biggestLossMargin = 0;

        foreach ($allMatches as $match) {
            $isHome = $match->home_team_id == $id;
            $teamScore = $isHome ? $match->home_score : $match->away_score;
            $opponentScore = $isHome ? $match->away_score : $match->home_score;
            $margin = $teamScore - $opponentScore;

            if ($margin > $biggestWinMargin) {
                $biggestWinMargin = $margin;
                $biggestWin = $match;
            }

            if ($margin < $biggestLossMargin) {
                $biggestLossMargin = $margin;
                $biggestLoss = $match;
            }
        }

        // Calculate form string (last 5 matches)
        $formString = '';
        foreach ($recentMatches as $match) {
            $isHome = $match->home_team_id == $id;
            $teamScore = $isHome ? $match->home_score : $match->away_score;
            $opponentScore = $isHome ? $match->away_score : $match->home_score;
            
            if ($teamScore > $opponentScore) {
                $formString .= 'W';
            } elseif ($teamScore == $opponentScore) {
                $formString .= 'D';
            } else {
                $formString .= 'L';
            }
        }

        return view('team-details', compact('team', 'nextMatch', 'recentMatches', 'rank', 'squad', 'cleanSheets', 'goalsPerGame', 'goalsConcededPerGame', 'winRate', 'biggestWin', 'biggestLoss', 'formString'));
    }

    public function player($id)
    {
        $player = Player::with(['team', 'goals', 'assists', 'matchLineups'])
            ->withCount(['goals', 'assists', 'matchLineups', 'yellowCards', 'redCards', 'motmAwards'])
            ->findOrFail($id);

        $nextMatch = MatchModel::with(['homeTeam', 'awayTeam'])
            ->where('status', 'upcoming')
            ->where(function($q) use ($player) {
                $q->where('home_team_id', $player->team_id)
                  ->orWhere('away_team_id', $player->team_id);
            })
            ->orderBy('match_date', 'asc')
            ->first();

        return view('player-details', compact('player', 'nextMatch'));
    }

    public function matchDetails($id)
    {
        $match = MatchModel::with(['homeTeam', 'awayTeam', 'matchEvents.player', 'matchEvents.assistant', 'matchEvents.relatedPlayer', 'lineups', 'commentaries'])->findOrFail($id);
        return view('match-details', compact('match'));
    }

    public function matchFeed($id)
    {
        $match = MatchModel::with(['commentaries'])->findOrFail($id);
        
        $html = '';
        if($match->commentaries->count() > 0) {
            $html .= '<div class="space-y-4">';
            foreach($match->commentaries as $log) {
                $html .= '<div class="bg-white p-4 md:p-6 rounded-3xl border border-zinc-100 shadow-sm flex gap-4 animate-fade-in-up">';
                $html .= '<div class="flex-shrink-0 w-12 text-center">';
                $html .= '<span class="block text-sm font-black text-secondary">' . $log->minute . '\'</span>';
                $html .= '</div>';
                $html .= '<div class="flex-grow pb-2 border-l-2 border-zinc-100 pl-4 relative">';
                $html .= '<div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 border-zinc-100"></div>';
                $html .= '<span class="inline-block px-2 py-0.5 rounded bg-zinc-50 border border-zinc-100 text-[9px] font-bold text-zinc-400 uppercase mb-2">' . $log->type . '</span>';
                $html .= '<p class="text-xs md:text-sm font-bold text-zinc-700 leading-relaxed">' . e($log->comment) . '</p>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        } else {
             $html .= '<div class="bg-white rounded-3xl shadow-sm border border-zinc-100 p-12 text-center">';
             $html .= '<div class="w-16 h-16 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-300">';
             $html .= '<div class="w-2 h-2 bg-zinc-400 rounded-full animate-ping"></div>';
             $html .= '</div>';
             $html .= '<h3 class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-1">Live Feed</h3>';
             $html .= '<p class="text-[10px] text-zinc-300">Waiting for match updates...</p>';
             $html .= '</div>';
        }
        
        return response($html);
    }
}
