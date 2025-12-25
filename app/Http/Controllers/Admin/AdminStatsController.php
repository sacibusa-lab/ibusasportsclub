<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminStatsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index(Request $request)
    {
        $competitionId = $request->get('competition_id');
        $competitions = \App\Models\Competition::all();
        
        if (!$competitionId && $competitions->count() > 0) {
            $competitionId = $competitions->where('is_active', true)->first()->id ?? $competitions->first()->id;
        }

        // Player Stats
        $topScorers = $this->statisticsService->getTopScorers(20, $competitionId);
        $topAssists = $this->statisticsService->getTopAssists(20, $competitionId);
        $topCleanSheets = $this->statisticsService->getTopCleanSheets(20, $competitionId);
        $topCards = $this->statisticsService->getTopCards(20, $competitionId);
        $topMOTM = $this->statisticsService->getTopMOTM(20, $competitionId);

        // Team Stats
        $topGoalsScored = \App\Models\CompetitionTeam::where('competition_id', $competitionId)
            ->with('team')
            ->orderBy('goals_for', 'desc')
            ->take(10)
            ->get();

        $topShots = $this->statisticsService->getTopTeamsByStat('shots', 10, $competitionId);
        $topCorners = $this->statisticsService->getTopTeamsByStat('corners', 10, $competitionId);
        $topOffsides = $this->statisticsService->getTopTeamsByStat('offsides', 10, $competitionId);
        $topFouls = $this->statisticsService->getTopTeamsByStat('fouls', 10, $competitionId);
        $topThrows = $this->statisticsService->getTopTeamsByStat('throw_ins', 10, $competitionId);
        $topSaves = $this->statisticsService->getTopTeamsByStat('saves', 10, $competitionId);
        $topGoalKicks = $this->statisticsService->getTopTeamsByStat('goal_kicks', 10, $competitionId);
        $topMissedChances = $this->statisticsService->getTopTeamsByStat('missed_chances', 10, $competitionId);

        return view('admin.stats.index', compact(
            'topScorers', 'topAssists', 'topCleanSheets', 'topCards', 'topMOTM',
            'topGoalsScored', 'topShots', 'topCorners', 'topOffsides', 'topFouls', 'topThrows', 'topSaves', 'topGoalKicks', 'topMissedChances',
            'competitions', 'competitionId'
        ));
    }
}
