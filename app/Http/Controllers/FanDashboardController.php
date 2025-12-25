<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FanDashboardController extends Controller
{
    /**
     * Display the fan's achievement dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ensure only fans (non-admins) can access this specific hub if needed, 
        // but it's fine for admins to see their own stats too if they play.
        
        $stats = $user->getStats();
        $granularStats = $user->getGranularStats();
        $badges = $user->getBadges();
        $rankTier = $user->rank_tier;
        
        // Get recent predictions
        $recentPredictions = $user->predictions()
            ->with(['match.homeTeam', 'match.awayTeam'])
            ->latest()
            ->take(5)
            ->get();

        return view('fan.dashboard', compact('user', 'stats', 'granularStats', 'badges', 'rankTier', 'recentPredictions'));
    }
}
