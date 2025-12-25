<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionTeam extends Model
{
    protected $fillable = [
        'competition_id', 'team_id', 'played', 'wins', 'draws', 'losses',
        'goals_for', 'goals_against', 'points'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
