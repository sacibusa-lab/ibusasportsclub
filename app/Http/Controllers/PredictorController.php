<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Prediction;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PredictorController extends Controller
{
    public function index()
    {
        $activeCompetition = \App\Models\Competition::where('slug', session('active_competition', 'main-competition'))->first() 
            ?? \App\Models\Competition::first();

        if (!$activeCompetition) {
            return view('predictor.index', [
                'upcomingMatches' => collect(),
                'leaderboard' => collect(),
                'activeCompetition' => null,
                'userPredictions' => collect()
            ]);
        }

        $userPredictions = Auth::check() 
            ? Auth::user()->predictions()->pluck('match_id')->toArray() 
            : [];

        $upcomingMatches = MatchModel::where('competition_id', $activeCompetition->id)
            ->where('status', 'upcoming')
            ->whereNotIn('id', $userPredictions)
            ->orderBy('match_date', 'asc')
            ->with(['homeTeam', 'awayTeam'])
            ->get();

        $leaderboard = User::orderBy('predictor_points', 'desc')
            ->where('predictor_points', '>', 0)
            ->limit(20)
            ->get();

        $competitions = Competition::where('is_active', true)->get();

        $myPredictions = Auth::check() 
            ? Auth::user()->predictions()->with(['match.homeTeam', 'match.awayTeam'])->orderBy('created_at', 'desc')->get()
            : collect();

        return view('predictor.index', compact('upcomingMatches', 'leaderboard', 'activeCompetition', 'userPredictions', 'competitions', 'myPredictions'));
    }

    public function predict(Request $request)
    {
        $request->validate([
            'match_id' => 'required|exists:matches,id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        $match = MatchModel::findOrFail($request->match_id);

        if ($match->match_date->isPast()) {
            return back()->with('error', 'Predictions closed for this match.');
        }

        Prediction::updateOrCreate(
            ['user_id' => Auth::id(), 'match_id' => $request->match_id],
            [
                'home_score' => $request->home_score,
                'away_score' => $request->away_score,
                'is_processed' => false
            ]
        );

        return back()->with('success', 'Prediction saved!');
    }
}
