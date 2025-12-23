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

    public function index()
    {
        // Player Stats
        $topScorers = $this->statisticsService->getTopScorers(20);
        $topAssists = $this->statisticsService->getTopAssists(20);
        $topCleanSheets = $this->statisticsService->getTopCleanSheets(20);
        $topCards = $this->statisticsService->getTopCards(20);
        $topMOTM = $this->statisticsService->getTopMOTM(20);

        // Team Stats
        $topGoalsScored = Team::orderBy('goals_for', 'desc')->take(10)->get();
        $topShots = $this->statisticsService->getTopTeamsByStat('shots', 10);
        $topCorners = $this->statisticsService->getTopTeamsByStat('corners', 10);
        $topOffsides = $this->statisticsService->getTopTeamsByStat('offsides', 10);
        $topFouls = $this->statisticsService->getTopTeamsByStat('fouls', 10);
        $topThrows = $this->statisticsService->getTopTeamsByStat('throw_ins', 10);
        $topSaves = $this->statisticsService->getTopTeamsByStat('saves', 10);
        $topGoalKicks = $this->statisticsService->getTopTeamsByStat('goal_kicks', 10);

        return view('admin.stats.index', compact(
            'topScorers', 'topAssists', 'topCleanSheets', 'topCards', 'topMOTM',
            'topGoalsScored', 'topShots', 'topCorners', 'topOffsides', 'topFouls', 'topThrows', 'topSaves', 'topGoalKicks'
        ));
    }
}
