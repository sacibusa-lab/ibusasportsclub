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

        return view('home', compact('upcomingMatch', 'upcomingMatches', 'latestResult', 'heroPost', 'trendingPosts', 'stories'));
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

        return view('stats', compact('topScorers', 'topAssists', 'topCleanSheets', 'topCards', 'topMOTM'));
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

        return view('team-details', compact('team', 'nextMatch', 'recentMatches', 'rank', 'squad'));
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
        $match = MatchModel::with(['homeTeam', 'awayTeam', 'matchEvents.player', 'matchEvents.assistant', 'matchEvents.relatedPlayer', 'lineups'])->findOrFail($id);
        return view('match-details', compact('match'));
    }
}
