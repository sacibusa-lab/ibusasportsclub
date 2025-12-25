<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prediction;
use Illuminate\Http\Request;

class AdminPredictorController extends Controller
{
    public function index()
    {
        $users = User::whereHas('predictions')
            ->orWhere('predictor_points', '>', 0)
            ->orderBy('predictor_points', 'desc')
            ->paginate(20);

        // Fetch top matches by prediction engagement
        $hotMatches = \App\Models\MatchModel::with(['homeTeam', 'awayTeam'])
            ->withCount('predictions')
            ->having('predictions_count', '>', 0)
            ->orderBy('predictions_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.predictor.index', compact('users', 'hotMatches'));
    }

    public function show(User $user)
    {
        $predictions = $user->predictions()
            ->with(['match.homeTeam', 'match.awayTeam'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.predictor.show', compact('user', 'predictions'));
    }
}
