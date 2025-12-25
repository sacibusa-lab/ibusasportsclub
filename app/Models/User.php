<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'predictor_points',
        'registration_ip',
        'device_token',
        'is_admin',
    ];

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    /**
     * Get the user's current rank tier based on points.
     */
    public function getRankTierAttribute()
    {
        $points = $this->predictor_points ?? 0;
        
        if ($points >= 250) return 'Platinum';
        if ($points >= 100) return 'Gold';
        if ($points >= 50)  return 'Silver';
        return 'Bronze';
    }

    /**
     * Get detailed performance statistics.
     */
    public function getStats()
    {
        $total = $this->predictions()->count();
        if ($total === 0) {
            return [
                'total' => 0,
                'exact' => 0,
                'correct_result' => 0,
                'accuracy' => 0,
                'points' => $this->predictor_points ?? 0
            ];
        }

        $exact = $this->predictions()->where('points_earned', 5)->count();
        $correctResult = $this->predictions()->where('points_earned', 2)->count();
        
        return [
            'total' => $total,
            'exact' => $exact,
            'correct_result' => $correctResult,
            'accuracy' => round((($exact + $correctResult) / $total) * 100),
            'points' => $this->predictor_points ?? 0
        ];
    }

    /**
     * Get granular accuracy breakdown by outcome type.
     */
    public function getGranularStats()
    {
        $processedPredictions = $this->predictions()->where('is_processed', true)->with('match')->get();
        
        $types = [
            'home' => ['total' => 0, 'correct' => 0],
            'away' => ['total' => 0, 'correct' => 0],
            'draw' => ['total' => 0, 'correct' => 0],
        ];

        foreach ($processedPredictions as $prediction) {
            $match = $prediction->match;
            if (!$match || $match->status !== 'finished') continue;

            $actualResult = $match->home_score <=> $match->away_score;
            $predictedResult = $prediction->home_score <=> $prediction->away_score;

            $type = $actualResult === 1 ? 'home' : ($actualResult === -1 ? 'away' : 'draw');
            
            $types[$type]['total']++;
            if ($actualResult === $predictedResult) {
                $types[$type]['correct']++;
            }
        }

        $results = [];
        foreach ($types as $key => $data) {
            $results[$key] = [
                'total' => $data['total'],
                'correct' => $data['correct'],
                'percentage' => $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100) : 0
            ];
        }

        return $results;
    }

    /**
     * Get earned badges based on achievements.
     */
    public function getBadges()
    {
        $badges = [];
        $stats = $this->getStats();

        if ($stats['total'] >= 10) {
            $badges[] = ['name' => 'Veteran', 'icon' => '🛡️', 'desc' => '10+ predictions made'];
        }
        if ($stats['exact'] >= 3) {
            $badges[] = ['name' => 'Sniper', 'icon' => '🎯', 'desc' => '3+ exact scores hit'];
        }
        if ($this->predictor_points >= 100) {
            $badges[] = ['name' => 'Century Club', 'icon' => '💯', 'desc' => 'Reached 100 points'];
        }
        if ($stats['accuracy'] >= 70 && $stats['total'] >= 5) {
            $badges[] = ['name' => 'Tactician', 'icon' => '🧠', 'desc' => '70%+ accuracy (min 5 games)'];
        }

        return $badges;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
