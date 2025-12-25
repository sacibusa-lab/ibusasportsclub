<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'home_score',
        'away_score',
        'points_earned',
        'is_processed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }

    public static function calculatePointsForMatch($matchId)
    {
        $match = MatchModel::find($matchId);
        if (!$match || $match->status !== 'finished') return;

        $predictions = self::where('match_id', $matchId)->where('is_processed', false)->get();

        foreach ($predictions as $prediction) {
            $points = 0;

            // Check for exact score
            if ($prediction->home_score == $match->home_score && $prediction->away_score == $match->away_score) {
                $points = 5;
            } else {
                // Check for correct result
                $actualResult = $match->home_score <=> $match->away_score;
                $predictedResult = $prediction->home_score <=> $prediction->away_score;

                if ($actualResult === $predictedResult) {
                    $points = 2;
                }
            }

            $prediction->update([
                'points_earned' => $points,
                'is_processed' => true
            ]);

            // Update user total points
            $prediction->user->increment('predictor_points', $points);
        }
    }
}
