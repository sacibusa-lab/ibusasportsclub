<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // 1. Overview Stats
        $totalViews = DB::table('analytics_visits')->count();
        $uniqueVisitors = DB::table('analytics_visits')->distinct('ip_address')->count();
        
        // 2. Traffic Trends (Last 30 Days)
        $dailyVisits = DB::table('analytics_visits')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = $dailyVisits->pluck('date');
        $data = $dailyVisits->pluck('count');

        // 3. Top Pages
        $topPages = DB::table('analytics_visits')
            ->select('url', DB::raw('count(*) as views'))
            ->groupBy('url')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // 4. Device Breakdown (Simple)
        $mobileVisits = DB::table('analytics_visits')
            ->where('user_agent', 'LIKE', '%Mobile%')
            ->orWhere('user_agent', 'LIKE', '%Android%')
            ->orWhere('user_agent', 'LIKE', '%iPhone%')
            ->count();
            
        $desktopVisits = $totalViews - $mobileVisits;

        // 5. Top Countries
        $topCountries = DB::table('analytics_visits')
            ->select('country', DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 6. Top Referrers
        $topReferrers = DB::table('analytics_visits')
            ->select('referer', DB::raw('count(*) as count'))
            ->whereNotNull('referer')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalViews', 
            'uniqueVisitors', 
            'labels', 
            'data', 
            'topPages',
            'mobileVisits',
            'desktopVisits',
            'topCountries',
            'topReferrers'
        ));
    }
}
