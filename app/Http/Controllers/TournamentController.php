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

    private function getActiveCompetition()
    {
        $competitionSlug = request()->get('competition', session('active_competition', 'main-competition'));
        $competition = \App\Models\Competition::where('slug', $competitionSlug)->first() 
            ?? \App\Models\Competition::first();

        if ($competition) {
            session(['active_competition' => $competition->slug]);
        }

        return $competition;
    }

    public function index()
    {
        $activeCompetition = $this->getActiveCompetition();
        
        if (!$activeCompetition) {
            return view('home', [
                'activeCompetition' => null,
                'competitions' => collect(),
                'upcomingMatch' => null,
                'upcomingMatches' => collect(),
                'latestResult' => null,
                'heroPost' => Post::where('is_published', true)->with('category')->orderBy('published_at', 'desc')->first(),
                'trendingPosts' => Post::where('is_published', true)->orderBy('published_at', 'desc')->limit(4)->get(),
                'stories' => collect(),
                'highlights' => collect(),
                'afconPosts' => collect()
            ]);
        }

        $compId = $activeCompetition->id;

        $upcomingMatch = MatchModel::where('competition_id', $compId)->where('status', 'upcoming')->orderBy('match_date', 'asc')->first();
        $upcomingMatches = MatchModel::where('competition_id', $compId)->where('status', 'upcoming')->orderBy('match_date', 'asc')->limit(4)->get();
        $latestResult = MatchModel::where('competition_id', $compId)->where('status', 'finished')->orderBy('match_date', 'desc')->first();
        
        // News items (News are global, but we could link them to competitions if needed)
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

        $highlights = MatchModel::where('competition_id', $compId)
            ->whereNotNull('highlights_url')
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

        $competitions = \App\Models\Competition::where('is_active', true)->get();

        return view('home', compact('upcomingMatch', 'upcomingMatches', 'latestResult', 'heroPost', 'trendingPosts', 'stories', 'highlights', 'afconPosts', 'activeCompetition', 'competitions'));
    }

    public function table()
    {
        $activeCompetition = $this->getActiveCompetition();
        if (!$activeCompetition) {
            return view('table', ['standings' => [], 'activeCompetition' => null, 'competitions' => collect()]);
        }
        $groups = Group::where('competition_id', $activeCompetition->id)->get();
        $standings = [];
        
        foreach ($groups as $group) {
            $standings[$group->name] = $this->tournamentService->getSortedStandings($group);
        }
        
        $competitions = \App\Models\Competition::where('is_active', true)->get();
        return view('table', compact('standings', 'activeCompetition', 'competitions'));
    }

    public function fixtures()
    {
        $activeCompetition = $this->getActiveCompetition();
        if (!$activeCompetition) {
            return view('fixtures', ['fixtures' => collect(), 'noveltyFixtures' => collect(), 'activeCompetition' => null, 'competitions' => collect()]);
        }
        $allFixtures = MatchModel::where('competition_id', $activeCompetition->id)
            ->where('status', 'upcoming')
            ->orderBy('match_date', 'asc')
            ->get();

        $fixtures = $allFixtures->where('stage', '!=', 'novelty')->groupBy(function($match) {
                return $match->match_date->format('Y-m-d');
            });

        $noveltyFixtures = $allFixtures->where('stage', 'novelty');
            
        $competitions = \App\Models\Competition::where('is_active', true)->get();
        return view('fixtures', compact('fixtures', 'noveltyFixtures', 'activeCompetition', 'competitions'));
    }

    public function results(Request $request)
    {
        $activeCompetition = $this->getActiveCompetition();
        if (!$activeCompetition) {
            return view('results', [
                'resultsGrouped' => collect(), 
                'noveltyResults' => collect(), 
                'matchdays' => collect(), 
                'teams' => collect(), 
                'matchdayId' => null, 
                'teamId' => null, 
                'activeCompetition' => null, 
                'competitions' => collect()
            ]);
        }
        $matchdayId = $request->query('matchday');
        $teamId = $request->query('team');
        
        $query = MatchModel::where('competition_id', $activeCompetition->id)->where('status', 'finished');
        
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
        
        $matchdays = MatchModel::where('competition_id', $activeCompetition->id)->whereNotNull('matchday')->distinct()->pluck('matchday')->sortDesc();
        $teams = Team::whereHas('homeMatches', function($q) use ($activeCompetition) {
                $q->where('competition_id', $activeCompetition->id);
            })->orWhereHas('awayMatches', function($q) use ($activeCompetition) {
                $q->where('competition_id', $activeCompetition->id);
            })->orderBy('name')->get();
            
        $competitions = \App\Models\Competition::where('is_active', true)->get();
        return view('results', compact('resultsGrouped', 'noveltyResults', 'matchdays', 'teams', 'matchdayId', 'teamId', 'activeCompetition', 'competitions'));
    }

    public function knockout()
    {
        $activeCompetition = $this->getActiveCompetition();
        if (!$activeCompetition) {
            return view('knockout', ['semifinals' => collect(), 'final' => null, 'activeCompetition' => null, 'competitions' => collect()]);
        }
        $semifinals = MatchModel::where('competition_id', $activeCompetition->id)->where('stage', 'semifinal')->get();
        $final = MatchModel::where('competition_id', $activeCompetition->id)->where('stage', 'final')->first();
        
        $competitions = \App\Models\Competition::where('is_active', true)->get();
        return view('knockout', compact('semifinals', 'final', 'activeCompetition', 'competitions'));
    }

    public function stats()
    {
        $activeCompetition = $this->getActiveCompetition();
        
        if (!$activeCompetition) {
            return view('stats', [
                'activeCompetition' => null,
                'competitions' => collect(),
                'topScorers' => collect(),
                'topAssists' => collect(),
                'topCleanSheets' => collect(),
                'topCards' => collect(),
                'topRedCards' => collect(),
                'topMOTM' => collect(),
                'topGoalsScored' => collect(),
                'topShots' => collect(),
                'topCorners' => collect(),
                'topOffsides' => collect(),
                'topFouls' => collect(),
                'topThrows' => collect(),
                'topSaves' => collect(),
                'topGoalKicks' => collect(),
                'topMissedChances' => collect()
            ]);
        }

        $compId = $activeCompetition->id;

        $topScorers = $this->statisticsService->getTopScorers(10, $compId);
        $topAssists = $this->statisticsService->getTopAssists(10, $compId);
        $topCleanSheets = $this->statisticsService->getTopCleanSheets(10, $compId);
        $topCards = $this->statisticsService->getTopYellowCards(10, $compId);
        $topRedCards = $this->statisticsService->getTopRedCards(10, $compId);
        $topMOTM = $this->statisticsService->getTopMOTM(10, $compId);

        // Team Stats - This needs specific CompetitionTeam logic
        $topGoalsScored = Team::whereHas('competitionTeams', function($q) use ($compId) {
                $q->where('competition_id', $compId);
            })
            ->join('competition_teams', 'teams.id', '=', 'competition_teams.team_id')
            ->where('competition_teams.competition_id', $compId)
            ->orderBy('competition_teams.goals_for', 'desc')
            ->take(10)
            ->select('teams.*', 'competition_teams.goals_for')
            ->get();

        $topShots = $this->statisticsService->getTopTeamsByStat('shots', 5, $compId);
        $topCorners = $this->statisticsService->getTopTeamsByStat('corners', 5, $compId);
        $topOffsides = $this->statisticsService->getTopTeamsByStat('offsides', 5, $compId);
        $topFouls = $this->statisticsService->getTopTeamsByStat('fouls', 5, $compId);
        $topThrows = $this->statisticsService->getTopTeamsByStat('throw_ins', 5, $compId);
        $topSaves = $this->statisticsService->getTopTeamsByStat('saves', 5, $compId);
        $topGoalKicks = $this->statisticsService->getTopTeamsByStat('goal_kicks', 5, $compId);
        $topMissedChances = $this->statisticsService->getTopTeamsByStat('missed_chances', 5, $compId);

        $competitions = \App\Models\Competition::where('is_active', true)->get();

        return view('stats', compact(
            'topScorers', 'topAssists', 'topCleanSheets', 'topCards', 'topRedCards', 'topMOTM',
            'topGoalsScored', 'topShots', 'topCorners', 'topOffsides', 'topFouls', 'topThrows', 'topSaves', 'topGoalKicks', 'topMissedChances',
            'activeCompetition', 'competitions'
        ));
    }

    public function teams()
    {
        $activeCompetition = $this->getActiveCompetition();
        if (!$activeCompetition) {
            return view('teams', ['teams' => collect(), 'activeCompetition' => null, 'competitions' => collect()]);
        }
        $teams = Team::whereHas('group', function($query) use ($activeCompetition) {
            $query->where('competition_id', $activeCompetition->id)
                  ->where('name', 'NOT LIKE', '%Friendly%');
        })->orderBy('name')->get();

        $competitions = \App\Models\Competition::where('is_active', true)->get();
        return view('teams', compact('teams', 'activeCompetition', 'competitions'));
    }

    public function team($id)
    {
        $team = Team::with(['players' => function($q) {
            $q->withCount(['goals', 'assists', 'matchLineups']);
        }, 'group'])->findOrFail($id);

        $activeCompetition = $this->getActiveCompetition();
        $compId = $activeCompetition ? $activeCompetition->id : null;

        $team = Team::with(['players', 'group', 'homeMatches.awayTeam', 'awayMatches.homeTeam'])
            ->findOrFail($id);

        // Get rankings
        $rankings = [
            'goals_for' => $this->statisticsService->getTeamRank($id, 'goals_for', $compId),
            'goals_against' => $this->statisticsService->getTeamRank($id, 'goals_against', $compId),
        ];

        $rank = $this->tournamentService->getTeamRankInGroup($team, $team->group);
        
        $recentMatches = MatchModel::where('status', 'finished')
            ->where('stage', '!=', 'novelty')
            ->where(function($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->orderBy('match_date', 'desc')
            ->take(5)
            ->get();

        $nextMatch = MatchModel::where('status', 'upcoming')
            ->where(function($query) use ($id) {
                $query->where('home_team_id', $id)
                      ->orWhere('away_team_id', $id);
            })
            ->orderBy('match_date', 'asc')
            ->first();

        $formString = $this->tournamentService->getTeamFormString($team, $recentMatches);
        $cleanSheets = $this->tournamentService->getTeamCleanSheets($team);
        $winRate = $team->played > 0 ? round(($team->wins / $team->played) * 100) : 0;
        $goalsPerGame = $team->played > 0 ? round($team->goals_for / $team->played, 2) : 0;
        $goalsConcededPerGame = $team->played > 0 ? round($team->goals_against / $team->played, 2) : 0;
        $totalMissedChances = $this->tournamentService->getTeamTotalStat($team, 'missed_chances');
        $biggestWin = $this->tournamentService->getTeamBiggestWin($team);
        $biggestLoss = $this->tournamentService->getTeamBiggestLoss($team);

        $squad = $team->players->groupBy('position');

        return view('team-details', compact(
            'team', 'rank', 'recentMatches', 'nextMatch', 'formString', 'cleanSheets', 
            'winRate', 'goalsPerGame', 'goalsConcededPerGame', 'totalMissedChances',
            'biggestWin', 'biggestLoss', 'squad', 'rankings', 'activeCompetition'
        ));
    }

    public function player($id)
    {
        $activeCompetition = $this->getActiveCompetition();
        $compId = $activeCompetition ? $activeCompetition->id : null;

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

        // Get Rankings
        $rankings = [
            'goals' => $this->statisticsService->getPlayerRank($id, 'goals', $compId),
            'assists' => $this->statisticsService->getPlayerRank($id, 'assists', $compId),
            'motm' => $this->statisticsService->getPlayerRank($id, 'motm', $compId),
        ];

        if ($player->position === 'GK') {
            $rankings['clean_sheets'] = $this->statisticsService->getPlayerRank($id, 'clean_sheets', $compId);
        }

        return view('player-details', compact('player', 'nextMatch', 'rankings', 'activeCompetition'));
    }

    public function matchDetails($id)
    {
        $match = MatchModel::with(['homeTeam', 'awayTeam', 'matchEvents.player', 'matchEvents.assistant', 'matchEvents.relatedPlayer', 'lineups', 'commentaries', 'images'])->findOrFail($id);
        return view('match-details', compact('match'));
    }

    public function gallery()
    {
        $matchesWithImages = MatchModel::whereHas('images')
            ->with(['homeTeam', 'awayTeam', 'images'])
            ->orderBy('match_date', 'desc')
            ->get();
            
        return view('gallery', compact('matchesWithImages'));
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
